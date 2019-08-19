<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Brand;
use Carbon\Carbon;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('brand.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('brand.create');
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
            'name.required' => 'Please enter brand name',     
            'name.unique' => 'Brand already exist',
            'description.required' => 'Please enter description',
        ];

        $validator = \Validator::make($request->all(), [
            'name' => 'required|unique:brand',
            'description' => 'required',
        ],$messages);
        
        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }

        $userId = Auth::id();
        $brand = new Brand();
        $current_time = Carbon::now('Asia/Manila');
        
        $brand->name = $request->input('name');
        $brand->description = $request->input('description');
        $brand->created_at = $current_time->toDateTimeString();
        $brand->user_id = $userId;
        $brand->save();

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
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $brand = Brand::findOrFail($id);

        return view('brand.edit', ['brand' => $brand]);
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
            'name.required' => 'Please enter brand name',     
            'description.required' => 'Please enter description',
        ];

        $validator = \Validator::make($request->all(), [
            'name' => 'required|unique:brand,name,'. $id,
            'description' => 'required',
        ],$messages);

        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()->all()]);
        } else {
            $userId = Auth::id();
            $current_time = Carbon::now('Asia/Manila');
            
            $brand = Brand::findOrFail($id);
            $brand->name = $request->input('name');
            $brand->description = $request->input('description');
            $brand->updated_at = $current_time->toDateTimeString();
            $brand->user_id = $userId;
            
            $brand->save();
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
        $nerd = Brand::find($id);
        $nerd->delete();
        
        return response()->json(['success'=>'Record is successfully added','errors'=>[]]);
    }
}
