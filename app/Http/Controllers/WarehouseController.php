<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Warehouse;
use Carbon\Carbon;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('warehouse.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('warehouse.create');
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
            'name.required' => 'Please enter warehouse name',     
            'name.unique' => 'Warehouse already exist',
            'address.required' => 'Please enter address',
        ];

        $validator = \Validator::make($request->all(), [
            'name' => 'required|unique:warehouse',
            'address' => 'required',
        ],$messages);
        
        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }

        $userId = Auth::id();
        $warehouse = new Warehouse();
        $current_time = Carbon::now('Asia/Manila');
        
        $warehouse->name = $request->input('name');
        $warehouse->address = $request->input('address');
        $warehouse->created_at = $current_time->toDateTimeString();
        $warehouse->user_id = $userId;
        $warehouse->save();

        return response()->json(['success'=>'Record is successfully added','errors'=>[]]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $warehouse = Warehouse::findOrFail($id);

        return view('warehouse.edit', ['warehouse' => $warehouse]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

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
            'name.required' => 'Please enter warehouse name',     
            'address.required' => 'Please enter address',
        ];

        $validator = \Validator::make($request->all(), [
            'name' => 'required|unique:warehouse,name,'. $id,
            'address' => 'required',
        ],$messages);

        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()->all()]);
        } else {
            $userId = Auth::id();
            $current_time = Carbon::now('Asia/Manila');
            
            $warehouse = Warehouse::findOrFail($id);
            $warehouse->name = $request->input('name');
            $warehouse->address = $request->input('address');
            $warehouse->updated_at = $current_time->toDateTimeString();
            $warehouse->user_id = $userId;
            
            $warehouse->save();
            return response()->json(['success'=>'Record is successfully added','errors'=>[]]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $nerd = Warehouse::find($id);
        $nerd->delete();
        
        return response()->json(['success'=>'Record is successfully added','errors'=>[]]);
    }
}
