<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\SalesOrder;
use App\Http\Resources\SalesOrder as SalesOrderResource;
use Validator;
use App\SalesOrderDetail;
use Carbon\Carbon;

class SalesOrderController extends Controller
{

    protected $request;


    public function __construct(Request $request) {
          $this->request = $request;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get sales order
        $salesorders = SalesOrder::paginate(50);

        // Return specified json resource
        return SalesOrderResource::collection($salesorders);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate fields
        $validator = Validator::make($request->all(),
        [
            'order_date' => 'required',
            'so_no' => 'required',
            'store' => 'required'
        ]);


        // Check whether put or post method
        $salesorder = $request->isMethod('put') ? SalesOrder::findOrFail($request->salesorder_id) : new SalesOrder();

        // Initialize values
        $salesorder->id = $request->input('sales_order_id');
        $salesorder->so_no = $request->input('so_no');
        $salesorder->order_date = date("Y-m-d h:m:s", strtotime($request->input('order_date')));
        $salesorder->delivery_date = $request->input('delivery_date');
        $salesorder->description = $request->input('description');
        $salesorder->store_id = $request->input('store_id');
        $salesorder->status_id = 1;
        $salesorder->user_id = 1;
        $salesorder->created_at = Carbon::now('Asia/Manila')->toDateTimeString();

        // Check if it will save
        if($salesorder->save()) {

          // Store single resource
          foreach ($request->items as $item) {
            $details = new SalesOrderDetail();
            $details->quantity = $item['qty'];
            $details->price = $item['unit_price'];
            $details->sales_order_id = $salesorder->id;
            $details->product_id = $item['product_id'];
            $details->save();
          }

          // Return specified resource
          return new SalesOrderResource($salesorder);
        } else {

          // Return specified resource
          // return response()->json(['errors'=>$validator->errors()->all()]);
          return SalesOrderResource($validator->errors()->all());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Get sales order
        $sales_order = SalesOrder::findOrFail($id);

        // Return single sales order as resource
        return new SalesOrderResource($sales_order);
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
        // Get sales order
        $salesorder = SalesOrder::findOrFail($id);

        if($saleorder->delete()) {
            return new SalesOrderResource($salesorder);
        }
    }
}
