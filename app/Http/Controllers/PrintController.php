<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PHPJasper\PHPJasper;

class PrintController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function printPurchaseOrder($id){

      $input = public_path() . '/reports/purchase_order.jasper';
      $output = public_path() . '/reports/purchase_order_' . time();
      // $jdbc_dir = base_path() . '/vendor/geekcom/phpjasper-laravel/bin/jasperstarter/jdbc';
      $options = [
          'format' => ['pdf'],
          'locale' => 'en',
          'params' => ['p_id' => $id],
          'db_connection' => [
              'driver' => 'mysql',
              'username' => 'root',
              'password' => 'root',
              'host' => 'localhost',
              'database' => 'isp_ftm',
              'port' => '3306',
              // 'jdbc_driver' => 'org.mariadb.jdbc.Driver',
              // 'jdbc_url' => 'jdbc:mariadb://localhost:3306/isp_ftm',
              // 'jdbc_dir' => $jdbc_dir
          ]
      ];

      $ext = "pdf";
      $report = new PHPJasper;
      $report->process(
          $input,
          $output,
          $options
          )->execute();
          // )->output();

          // dd($report);

      // "usage: jasperstarter process [-h] -f <fmt> [<fmt> ...] [-o <output>] [-w]"

      // "jasperstarter process "C:\xampp\htdocs\isp_ftm\public/reports/purchase_order.jasper" -o
      // "C:\xampp\htdocs\isp_ftm\public/reports/purchase_order_1569109076" -f pdf -t mysql -u root -p
      // -H localhost -n isp_ftm --db-port 3306 --db-driver org.mariadb.jdbc.Driver --db-url jdbc:mariadb://localhost:3306/isp_ftm
      // --jdbc-dir C:\xampp\htdocs\isp_ftm/vendor/geekcom/phpjasper-laravel/bin/jasperstarter/jdbc 2>&1 ◀"

      header('Content-Description: File Transfer');
      header('Content-Type: application/octet-stream');
      header('Content-Disposition: attachment; filename=Purchase Order -'.time().'.'.$ext);
      header('Content-Transfer-Encoding: binary');
      header('Expires: 0');
      header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
      header('Content-Length: ' . filesize($output.'.'.$ext));
      flush();
      readfile($output.'.'.$ext);
      unlink($output.'.'.$ext);
    }
}
