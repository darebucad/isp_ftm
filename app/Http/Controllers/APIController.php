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
use App\SalesOrder;

class APIController extends Controller
{

    // /**
    //  * Create a new controller instance.
    //  *
    //  * @return void
    //  */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    // GET

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
     'c.name AS category', 's.name AS supplier', 'w.name AS warehouse', 'se.name AS section', 'u.name AS user', 'p.created_at', 'p.updated_at', 'p.type', 'p.actual_on_hand');


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
       'c.name AS category', 's.name AS supplier', 'w.name AS warehouse', 'se.name AS section', 'u.name AS user', 'p.created_at', 'p.updated_at', 'p.type', 'p.actual_on_hand');
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
       'c.name AS category', 's.name AS supplier', 'w.name AS warehouse', 'se.name AS section', 'u.name AS user', 'p.created_at', 'p.updated_at', 'p.type', 'p.actual_on_hand');
      }

       return datatables($query)->make(true);
    }

    // Get list of purchase orders
    public function getPurchaseOrders(){
      $query = DB::table('purchases AS p')
      ->leftjoin('suppliers AS s', 's.id', '=', 'p.supplier_id')
      ->leftjoin('purchase_status AS ps', 'ps.id', '=', 'p.status_id')
      ->select('p.id', DB::raw('lpad(p.po_no, 6, "0") as po_no'), 'p.order_date', 's.name', 'p.description', 'p.receipt_date', 'ps.name AS status', 'p.created_at');

      return datatables($query)->make(true);
    }

    public function getSalesOrders(){
      $query = DB::table('sales_orders as so')
      ->leftjoin('store as s', 's.id', '=', 'so.store_id')
      ->leftjoin('statuses as st', 'st.id', '=', 'so.status_id')
      ->leftjoin('users as u', 'u.id', '=', 'so.user_id')
      ->select('so.id', DB::raw('lpad(so.so_no, 8, "0") as so_no'), 'so.order_date', 'so.delivery_date', 'so.description', 's.name as store',
      'st.name as status', 'u.name as user', 'so.created_at');

      return datatables($query)->make(true);
    }

    // SEARCH

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

    // Search list of products
    public function searchProducts(Request $request){
      $term = $request->q;

      $products = Product::where('name', 'LIKE', '%' . $term . '%')
      ->select('id', 'name AS text')
      ->orderBy('name', 'ASC')
      ->get();

      $response = array(
        'items' => $products
      );

      return response()->json($response);
    }

    // Search list of finished products
    public function searchFinishedProducts(Request $request){
      $term = $request->q;

      $products = Product::where('name', 'LIKE', '%' . $term . '%')
      ->where('type', '1')
      ->select('id', 'name AS text')
      ->orderBy('name', 'ASC')
      ->get();

      $response = array(
        'items' => $products
      );

      return response()->json($response);
    }


    // Search list of unit of measurements
    public function searchUnitOfMeasure(Request $request){
      $unitofmeasures = UnitOfMeasure::where('name', 'LIKE', '%' . $request->q . '%')
      ->select('id', 'name AS text')
      ->orderBy('name', 'ASC')
      ->get();

      $response = array(
        'items' => $unitofmeasures
      );
      return response()->json($response);
    }


    // Search list of stores
    public function searchStores(Request $request){
      $stores = Store::where('name', 'LIKE', '%' . $request->q . '%')
      ->select('id', 'name AS text')
      ->orderBy('name', 'ASC')
      ->get();

      $response = array(
        'items' => $stores
      );
      return response()->json($response);
    }

    // POPULATE or FETCH

    // Populate list of products
    public function populateProducts($id){
      $products = Product::where('supplier_id', $id)->where('type', '0')->get();

      return response()->json($products);
    }


    // Populate list of finished Products
    public function populateFinishedProducts($id){
      $products = DB::table('store_products as sp')
      ->where('sp.store_id', $id)
      ->leftjoin('products as p', 'p.id', '=', 'sp.product_id')
      ->select('p.id', 'p.name', 'sp.quantity', 'p.unit_price')
      ->get();

      return response()->json($products);

    }


}
