<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Categories;
use App\Supplier;

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
}
