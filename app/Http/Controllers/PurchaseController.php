<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Purchase;
use App\PurchaseDetails;
use Validator;
use Carbon\Carbon;

class PurchaseController extends Controller
{
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
        return view('purchases.create');
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
      ];

      $validator = Validator::make($request->all(),
          [
              'order_date' => 'required',
              'supplier_id' => 'required',
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
        'success' => 'New purchase was successfully added',
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

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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
}
