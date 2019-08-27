<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\UnitOfMeasure;
use Carbon\Carbon;

class UnitOfMeasureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('unitofmeasure.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('unitofmeasure.create');
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
            'name.required' => 'Please enter Unit of Measure',     
            'name.unique' => 'Unit of Measure already exist',
        ];

        $validator = \Validator::make($request->all(), [
            'name' => 'required|unique:unitofmeasure',
        ],$messages);
        
        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }

        $userId = Auth::id();
        $unitofmeasure = new UnitOfMeasure();
        $current_time = Carbon::now('Asia/Manila');
        
        $unitofmeasure->name = $request->input('name');
        $unitofmeasure->created_at = $current_time->toDateTimeString();
        $unitofmeasure->user_id = $userId;
        $unitofmeasure->save();

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
        $unitofmeasure = UnitOfMeasure::findOrFail($id);

        return view('unitofmeasure.edit', ['unitofmeasure' => $unitofmeasure]);
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
            'name.required' => 'Please enter Unit of Measure',     
        ];

        $validator = \Validator::make($request->all(), [
            'name' => 'required|unique:unitofmeasure,name,'. $id,
        ],$messages);

        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()->all()]);
        } else {
            $userId = Auth::id();
            $current_time = Carbon::now('Asia/Manila');
            
            $unitofmeasure = UnitOfMeasure::findOrFail($id);
            $unitofmeasure->name = $request->input('name');
            $unitofmeasure->updated_at = $current_time->toDateTimeString();
            $unitofmeasure->user_id = $userId;
            
            $unitofmeasure->save();
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
        $nerd = UnitOfMeasure::find($id);
        $nerd->delete();
        
        return response()->json(['success'=>'Record is successfully added','errors'=>[]]);
    }
}
