<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Purchase;
use App\PurchaseDetails;
use App\Supplier;
use App\PurchaseStatus;
use App\Product;
use Validator;
use Carbon\Carbon;

class PurchaseController extends Controller
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


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('purchases.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $purchase = Purchase::select('po_no')->orderBy('created_at', 'desc')->first();
        return view('purchases.create')->with('po_no', $purchase->po_no);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $messages = [
        'order_date.required' => 'Please enter an order date',
        'supplier.required' => 'Please select a supplier',
        'po_no.required' => 'Please enter a PO no.'
      ];

      $validator = Validator::make($request->all(),
          [
              'order_date' => 'required',
              'supplier_id' => 'required',
              'po_no' => 'required'
          ], $messages
      );

      if ($validator->fails()) {
        return response()->json(['errors'=>$validator->errors()->all()]);
      } else {
        $purchase = new Purchase();
        $purchase->order_date = date("Y-m-d h:m:s", strtotime($request->order_date));
        $purchase->description = $request->description;
        $purchase->supplier_id = $request->supplier_id;
        $purchase->status_id = '1';
        $purchase->po_no = $request->po_no;
        $purchase->created_at = Carbon::now('Asia/Manila')->toDateTimeString();
        $purchase->user_id = Auth::id();
        $purchase->save();

        foreach ($request->items as $value) {
          $details = New PurchaseDetails();
          $details->quantity = $value['qty'];
          $details->price = $value['unit_price'];
          $details->purchase_id = $purchase->id;
          $details->product_id = $value['product_id'];
          $details->created_at = Carbon::now('Asia/Manila')->toDateTimeString();
          $details->save();
        }
      }

      $response = array(
        'success' => 'New purchase order was successfully added',
        'errors' => [],
        'id' => $purchase->id
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

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $purchase = Purchase::findOrFail($id);
        $details = DB::table('purchases AS p')
          ->leftjoin('purchase_details AS pd', 'pd.purchase_id', '=', 'p.id')
          ->leftjoin('products AS pr', 'pr.id', '=', 'pd.product_id')
          ->select('pd.id', 'pr.id as product_id', 'pr.name', 'pd.quantity', 'pd.price', 'pd.quantity_received')
          ->where('p.id', $id)
          ->get();
        $suppliers = Supplier::all();
        $status = PurchaseStatus::all();
        $getstatus = DB::table('purchases as p')
        ->leftjoin('purchase_status as ps', 'ps.id', '=', 'p.status_id')
        ->select('ps.name')
        ->where('p.id', $id)
        ->first();

        return view('purchases.edit', ['purchase' => $purchase, 'details' => $details, 'suppliers' => $suppliers, 'status' => $status,
      'getstatus' => $getstatus->name]);
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
        $messages = [
          'order_date.required' => 'Please enter an order date',
          'supplier.required' => 'Please select a supplier',
          'po_no.required' => 'Please enter a PO no.'
        ];

        $validator = Validator::make($request->all(),
            [
                'order_date' => 'required',
                'supplier_id' => 'required',
                'po_no' => 'required'
            ], $messages
        );

        if ($validator->fails()) {
          return response()->json(['errors'=>$validator->errors()->all()]);
        } else {
          $purchase = Purchase::findOrFail($id);
          $purchase->order_date = date("Y-m-d h:m:s", strtotime($request->order_date));
          $purchase->description = $request->description;
          $purchase->supplier_id = $request->supplier_id;
          $purchase->status_id = $this::getStatusId($request->status);
          $purchase->updated_at = Carbon::now('Asia/Manila')->toDateTimeString();
          $purchase->user_id = Auth::id();
          $purchase->save();

          foreach ($request->items as $value) {
            $details = PurchaseDetails::findOrFail($value['id']);
            $details->quantity = $value['qty'];
            $details->price = $value['unit_price'];
            $details->purchase_id = $purchase->id;
            $details->product_id = $value['product_id'];
            $details->updated_at = Carbon::now('Asia/Manila')->toDateTimeString();
            $details->quantity_received = $value['qty_received'];
            $details->save();

            if ($request->status == 'Received') {
              $this::updateProductQty($value['product_id'], $value['qty_received']);
            }
          }
        }

        $response = array(
          'success' => 'Purchase order was successfully updated',
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
        //
    }

    function getStatusId($name){
      $status = DB::table('purchase_status')
      ->where('name', $name)
      ->select('id')
      ->first();

      return $status->id;
    }

    function updateProductQty($id, $qty){
      $getproduct = Product::findOrFail($id);

      $product = Product::findOrFail($id);
      $product->stock_on_hand = $getproduct->stock_on_hand + $qty;
      $product->updated_at = Carbon::now('Asia/Manila')->toDateTimeString();
      $product->user_id = Auth::id();
      $product->save();
    }
}
