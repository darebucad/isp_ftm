<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Supplier;

use Carbon\Carbon;

class SuppliersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('suppliers.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('suppliers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $_token = $request->_token;
        $array_data = $request->arrData;
        $current_time = Carbon::now('Asia/Manila');

        foreach ($array_data as $value) {
          // $_token = $value['_token'];
          $supplier_name = $value['supplier_name'];
          $supplier_address = $value['supplier_address'];

        }

        $data = array(
          'name' => $supplier_name,
          'address' => $supplier_address,
          'created_at' => $current_time->toDateTimeString(),
        );

        Supplier::insert($data);


        $response = array(
          'data' => $array_data,
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
