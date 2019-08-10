<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

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
      $user_id = Auth::id();
      $supplier = new Supplier();
      $current_time = Carbon::now('Asia/Manila');

        $messages = [
          'name.required' => 'Please enter supplier name',
          'name.unique' => 'Supplier name already exist',
          'address.required' => 'Please enter supplier address',
        ];

        $validator = Validator::make($request->all(),
            [
                'name' => 'required|unique:suppliers',
                'address'  => 'required',
            ], $messages
        );

        if ($validator->fails()) {
          return response()->json(['errors'=>$validator->errors()->all()]);
        } else {


          $supplier->name = $request->input('name');
          $supplier->address = $request->input('address');
          $supplier->created_at = $current_time->toDateTimeString();
          $supplier->user_id = $user_id;
          $supplier->save();

        }

        $response = array(
          'success' => 'New supplier was successfully added',
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
        $supplier = Supplier::findOrFail($id);

        return view('suppliers.edit')->with('supplier', $supplier);
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
          'name.required' => 'Please enter supplier name',
          'address.required' => 'Please enter supplier address',
        ];

        $validator = Validator::make($request->all(),
            [
                'name' => 'required|unique:suppliers,name,'. $id,
                'address'  => 'required',
            ], $messages
        );

        if ($validator->fails()) {
          return response()->json(['errors'=>$validator->errors()->all()]);
        } else {

          $supplier = Supplier::findOrFail($id);
          $supplier->name = $request->input('name');
          $supplier->address = $request->input('address');
          $supplier->created_at = $current_time->toDateTimeString();
          $supplier->user_id = $user_id;
          $supplier->save();

        }

        $response = array(
          'success' => 'Supplier was successfully edited',
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
        $supplier = Supplier::find($id);
        $supplier->delete();

        $response = array(
          'success' => 'Supplier was successfully deleted',
          'errors' => [],
        );

        return response()->json($response);
    }
}
