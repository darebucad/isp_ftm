<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Sections;
use Carbon\Carbon;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('sections.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('sections.create');
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
            'name.required' => 'Please enter section name',     
            'name.unique' => 'Section already exist',
            'description.required' => 'Please enter description',
        ];

        $validator = \Validator::make($request->all(), [
            'name' => 'required|unique:sections',
            'description' => 'required',
        ],$messages);
        
        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }

        $userId = Auth::id();
        $section = new Sections();
        $current_time = Carbon::now('Asia/Manila');
        
        $section->name = $request->input('name');
        $section->description = $request->input('description');
        $section->created_at = $current_time->toDateTimeString();
        $section->user_id = $userId;
        $section->save();

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
        $section = Sections::findOrFail($id);

        return view('sections.edit', ['section' => $section]);
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
        $messages = [
            'name.required' => 'Please enter section name',     
            'description.required' => 'Please enter description',
        ];

        $validator = \Validator::make($request->all(), [
            'name' => 'required|unique:sections,name,'. $id,
            'description' => 'required',
        ],$messages);

        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()->all()]);
        } else {
            $userId = Auth::id();
            $current_time = Carbon::now('Asia/Manila');
            
            $section = Sections::findOrFail($id);
            $section->name = $request->input('name');
            $section->description = $request->input('description');
            $section->updated_at = $current_time->toDateTimeString();
            $section->user_id = $userId;
            
            $section->save();
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
        $nerd = Sections::find($id);
        $nerd->delete();
        
        return response()->json(['success'=>'Record is successfully added','errors'=>[]]);
    }
}
