<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

use App\Store;

use Carbon\Carbon;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("store.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('store.create');
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
      $store = new Store();
      $current_time = Carbon::now('Asia/Manila');

        $messages = [
          'name.required' => 'Please enter Store name',
          'name.unique' => 'Store name already exist',
          'address.required' => 'Please enter store address',
        ];

        $validator = Validator::make($request->all(),
            [
                'name' => 'required|unique:store',
                'address'  => 'required',
            ], $messages
        );

        if ($validator->fails()) {
          return response()->json(['errors'=>$validator->errors()->all()]);
        } else {


          $store->name = $request->input('name');
          $store->address = $request->input('address');
          $store->created_at = $current_time->toDateTimeString();
          $store->user_id = $user_id;
          $store->save();

        }

        $response = array(
          'success' => 'New store was successfully added',
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
        $store = Store::findOrFail($id);

        return view('store.edit')->with('store', $store);
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
          'name.required' => 'Please enter store name',
          'address.required' => 'Please enter store address',
        ];

        $validator = Validator::make($request->all(),
            [
                'name' => 'required|unique:store,name,'. $id,
                'address'  => 'required',
            ], $messages
        );

        if ($validator->fails()) {
          return response()->json(['errors'=>$validator->errors()->all()]);
        } else {

          $store = Store::findOrFail($id);
          $store->name = $request->input('name');
          $store->address = $request->input('address');
          $store->created_at = $current_time->toDateTimeString();
          $store->user_id = $user_id;
          $store->save();

        }

        $response = array(
          'success' => 'Store was successfully edited',
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
        $store = Store::find($id);
        $store->delete();

        $response = array(
          'success' => 'Store was successfully deleted',
          'errors' => [],
        );

        return response()->json($response);
    }
}
