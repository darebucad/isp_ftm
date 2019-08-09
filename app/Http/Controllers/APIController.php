<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Categories;
use App\Supplier;
use App\Warehouse;

class APIController extends Controller
{
    // Get the product categories
    public function getCategories(){
        $query = Categories::select('Id','Name','Description');
        return datatables($query)->make(true);
    }

    // Get list of suppliers
    public function getSuppliers(){
      $query = Supplier::select('id', 'name', 'address');

      return datatables($query)->make(true);
    }

    // Get list of Warehouse
    public function getWarehouse(){
        $query = Warehouse::select('Id', 'Name', 'Address');
  
        return datatables($query)->make(true);
    }
}
