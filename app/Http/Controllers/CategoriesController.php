<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Categories;
use Carbon\Carbon;

class CategoriesController extends Controller
{
    public function Index(){
        return view('categories.index');
    }

    public function Create(){
        return view('categories.create');
    }

    public function Show($id)
    {
        $category = Categories::findOrFail($id);

        return view('categories.edit', ['category' => $category]);
    }

    public function Store(Request $request){


        $messages = [
            'name.required' => 'Please enter category name',     
            'name.unique' => 'Category already exist',
            'description.required' => 'Please enter description',
        ];

        $validator = \Validator::make($request->all(), [
            'name' => 'required|unique:categories',
            'description' => 'required',
        ],$messages);
        
        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }

        $userId = Auth::id();
        $category = new Categories();
        $current_time = Carbon::now('Asia/Manila');
        
        $category->name = $request->input('name');
        $category->description = $request->input('description');
        $category->created_at = $current_time->toDateTimeString();
        $category->user_id = $userId;
        $category->save();

        return response()->json(['success'=>'Record is successfully added','errors'=>[]]);
    }

    public function Update(Request $request, $id)
    {
        $messages = [
            'name.required' => 'Please enter category name',     
            'description.required' => 'Please enter description',
        ];

        $validator = \Validator::make($request->all(), [
            'name' => 'required|unique:categories,name,'. $id,
            'description' => 'required',
        ],$messages);

        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()->all()]);
        } else {
            $userId = Auth::id();
            $current_time = Carbon::now('Asia/Manila');
            
            $category = Categories::findOrFail($id);
            $category->name = $request->input('name');
            $category->description = $request->input('description');
            $category->updated_at = $current_time->toDateTimeString();
            $category->user_id = $userId;
            
            $category->save();
            return response()->json(['success'=>'Record is successfully added','errors'=>[]]);
        }
    }

    public function Destroy($id)
    {
        $nerd = Categories::find($id);
        $nerd->delete();
        
        return response()->json(['success'=>'Record is successfully added','errors'=>[]]);
    }
}
