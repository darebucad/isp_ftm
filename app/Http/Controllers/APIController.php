<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Categories;
use App\Supplier;
use App\Warehouse;
use App\Sections;
use App\Product;
use App\Brand;
use App\Store;
use App\UnitOfMeasure;
use App\PurchaseStatus;

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

    // Get list of Sections
    public function getSections(){
        $query = Sections::select('Id', 'Name', 'Description');

        return datatables($query)->make(true);
    }

    // Get the brands
    public function getBrands(){
      $query = Brand::select('Id','Name','Description');
      return datatables($query)->make(true);
    }


    // Get the stores
    public function getStores(){
      $query = Store::select('Id','Name','Address');
      return datatables($query)->make(true);
    }

    //Get Unit of Measure
    public function getUOM(){
      $query = UnitOfMeasure::select('Id','Name');
      return datatables($query)->make(true);
    }

    // Get list of Products
    public function getProducts($type){

      $query = DB::table('products AS p')
      ->leftjoin('categories AS c', 'c.id', '=', 'p.category_id')
      ->leftjoin('suppliers AS s', 's.id', '=', 'p.supplier_id')
      ->leftjoin('warehouse AS w', 'w.id', '=', 'p.warehouse_id')
      ->leftjoin('sections AS se', 'se.id', '=', 'p.supplier_id')
      ->leftjoin('users AS u', 'u.id', '=', 'p.user_id')
      ->leftjoin('brand AS b', 'b.id', '=', 'p.brand_id')
      ->select('p.id', 'p.name', 'p.description', 'b.name AS brand','p.content', 'p.net_weight', 'p.stock_on_hand', 'p.purchase_price', 'p.unit_price',
     'c.name AS category', 's.name AS supplier', 'w.name AS warehouse', 'se.name AS section', 'u.name AS user', 'p.created_at', 'p.updated_at', 'p.type');


      if($type === "0" || $type === 0){
        $query = DB::table('products AS p')
        ->leftjoin('categories AS c', 'c.id', '=', 'p.category_id')
        ->leftjoin('suppliers AS s', 's.id', '=', 'p.supplier_id')
        ->leftjoin('warehouse AS w', 'w.id', '=', 'p.warehouse_id')
        ->leftjoin('sections AS se', 'se.id', '=', 'p.supplier_id')
        ->leftjoin('users AS u', 'u.id', '=', 'p.user_id')
        ->leftjoin('brand AS b', 'b.id', '=', 'p.brand_id')
        ->where('p.type', 0)
        ->select('p.id', 'p.name', 'p.description', 'b.name AS brand','p.content', 'p.net_weight', 'p.stock_on_hand', 'p.purchase_price', 'p.unit_price',
       'c.name AS category', 's.name AS supplier', 'w.name AS warehouse', 'se.name AS section', 'u.name AS user', 'p.created_at', 'p.updated_at', 'p.type');
      }else if ($type === "1" || $type === 1){
        $query = DB::table('products AS p')
        ->leftjoin('categories AS c', 'c.id', '=', 'p.category_id')
        ->leftjoin('suppliers AS s', 's.id', '=', 'p.supplier_id')
        ->leftjoin('warehouse AS w', 'w.id', '=', 'p.warehouse_id')
        ->leftjoin('sections AS se', 'se.id', '=', 'p.supplier_id')
        ->leftjoin('users AS u', 'u.id', '=', 'p.user_id')
        ->leftjoin('brand AS b', 'b.id', '=', 'p.brand_id')
        ->where('p.type', 1)
        ->select('p.id', 'p.name', 'p.description', 'b.name AS brand','p.content', 'p.net_weight', 'p.stock_on_hand', 'p.purchase_price', 'p.unit_price',
       'c.name AS category', 's.name AS supplier', 'w.name AS warehouse', 'se.name AS section', 'u.name AS user', 'p.created_at', 'p.updated_at', 'p.type');
      }

       return datatables($query)->make(true);
    }


    // Search list of categories
    public function searchCategories(Request $request){
      $term = $request->q;

      $categories = Categories::where('name', 'LIKE', '%' . $term . '%')
      ->select('id', 'name AS text')
      ->orderBy('name', 'ASC')
      ->get();

      $response = array(
        'items'  =>  $categories
      );

      return response()->json($response);
    }

    // Search list of suppliers
    public function searchSuppliers(Request $request){
      $term = $request->q;

      $suppliers = Supplier::where('name', 'LIKE', '%' . $term . '%')
      ->select('id', 'name AS text')
      ->orderBy('name', 'ASC')
      ->get();

      $response = array(
        'items'  =>  $suppliers
      );

      return response()->json($response);
    }


    // Search list of warehouse
    public function searchWarehouse(Request $request){
      $term = $request->q;

      $warehouse = Warehouse::where('name', 'LIKE', '%' . $term . '%')
      ->select('id', 'name AS text')
      ->orderBy('name', 'ASC')
      ->get();

      $response = array(
        'items'  =>  $warehouse
      );

      return response()->json($response);
    }

    // Search list of sections
    public function searchSections(Request $request){
      $term = $request->q;

      $sections = Sections::where('name', 'LIKE', '%' . $term . '%')
      ->select('id', 'name AS text')
      ->orderBy('name', 'ASC')
      ->get();

      $response = array(
        'items'  =>  $sections
      );

      return response()->json($response);
    }

    // Search list of brands
    public function searchBrands(Request $request){
      $term = $request->q;

      $brands = Brand::where('name', 'LIKE', '%' . $term . '%')
      ->select('id', 'name AS text')
      ->orderBy('name', 'ASC')
      ->get();

      $response = array(
        'items' => $brands
      );

      return response()->json($response);
    }

    // Search list of status
    public function searchPurchaseStatus(Request $request){
      $term = $request->q;

      $status = PurchaseStatus::where('name', 'LIKE', '%' . $term . '%')
      ->select('id', 'name AS text')
      ->orderBy('name', 'ASC')
      ->get();

      $response = array(
        'items'  =>  $status
      );

      return response()->json($response);
    }


    // Populate list of products
    public function populateProducts($id){
      $products = Product::where('supplier_id', $id)->where('type', '0')->get();

      return response()->json($products);
    }
}
