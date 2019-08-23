<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;

use App\Product;
use App\Categories;
use App\Supplier;
use App\Warehouse;
use App\Sections;
use App\Brand;

use Carbon\Carbon;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('products.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $user_id = Auth::id();
      $current_time = Carbon::now('Asia/Manila');

        $messages = [
          'name.required' => 'Please enter a product name',
          'name.unique' => 'Product name already exist',
          'category.required' => 'Please select a category',
          'content.required' => 'Please enter a content value',
          'unit_price.required' => 'Please enter a unit price',
          'type.required' => 'Please select a type',
        ];

        $validator = Validator::make($request->all(),
            [
                'name' => 'required|unique:products',
                'category_id' => 'required',
                'content'  => 'required',
                'unit_price' => 'required',
                'type' => 'required'
            ], $messages
        );

        if ($validator->fails()) {
          return response()->json(['errors'=>$validator->errors()->all()]);
        } else {

          $product = new Product();
          $product->name = $request->input('name');
          $product->category_id = $request->input('category_id');
          $product->description = $request->input('description');
          $product->content = $request->input('content');
          $product->net_weight = $request->input('net_weight');
          $product->stock_on_hand = $request->input('stock_on_hand');
          $product->purchase_price = $request->input('purchase_price');
          $product->unit_price = $request->input('unit_price');
          $product->supplier_id = $request->input('supplier_id');
          $product->warehouse_id = $request->input('warehouse_id');
          $product->section_id = $request->input('section_id');
          $product->brand_id = $request->input('brand_id');
          $product->created_at = $current_time->toDateTimeString();
          $product->user_id = $user_id;
          $product->type = $request->input('type');
          // dd($product);
          $product->save();

        }

        $response = array(
          'success' => 'New product was successfully added',
          'errors' => []
        );

        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Categories::all();
        $suppliers = Supplier::all();
        $warehouses = Warehouse::all();
        $sections = Sections::all();
        $brands = Brand::all();

        return view('products.edit', ['product' => $product, 'categories' => $categories, 'suppliers' => $suppliers, 'warehouses' => $warehouses, 'sections' => $sections, 'brands' => $brands]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      $user_id = Auth::id();
      $current_time = Carbon::now('Asia/Manila');

        $messages = [
          'name.required' => 'Please enter a product name',
          'category.required' => 'Please select a category',
          'content.required' => 'Please enter a content value',
          'unit_price.required' => 'Please enter a unit price',
          'type.required' => 'Please select a type',
        ];

        $validator = Validator::make($request->all(),
            [
                'name' => 'required|unique:products,name,'. $id,
                'category_id' => 'required',
                'content'  => 'required',
                'unit_price' => 'required',
                'type' => 'required',
            ], $messages
        );

        if ($validator->fails()) {
          return response()->json(['errors'=>$validator->errors()->all()]);
        } else {

          $product = Product::findOrFail($id);
          $product->name = $request->input('name');
          $product->category_id = $request->input('category_id');
          $product->description = $request->input('description');
          $product->content = $request->input('content');
          $product->net_weight = $request->input('net_weight');
          $product->stock_on_hand = $request->input('stock_on_hand');
          $product->purchase_price = $request->input('purchase_price');
          $product->unit_price = $request->input('unit_price');
          $product->supplier_id = $request->input('supplier_id');
          $product->warehouse_id = $request->input('warehouse_id');
          $product->section_id = $request->input('section_id');
          $product->brand_id = $request->input('brand_id');
          $product->created_at = $current_time->toDateTimeString();
          $product->user_id = $user_id;
          $product->type = $request->input('type');
          // dd($product);
          $product->save();

        }

        $response = array(
          'success' => 'Product was successfully edited',
          'errors' => []
        );

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        $product->delete();

        $response = array(
          'success' => 'Product was successfully deleted',
          'errors' => [],
        );

        return response()->json($response);
    }
}
