<?php

namespace App\Http\Controllers;

use App\Http\Requests\SectionRequest;
use App\Models\Section;
use App\Models\User;
use App\Notifications\AddSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class SectionController extends Controller
{
      function __construct()
{

$this->middleware('permission:الاقسام', ['only' => ['index']]);
}
    public function index()
    {
        $sections = Section::all();
        return view('sections.index',compact('sections'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SectionRequest $request)
    {
      $section =   Section::create([
            'section_name'=>$request->section_name,
            'description'=>$request->description,
            'created_by'=>auth()->user()->name,
        ]);
              $users = User::where('id','!=',auth()->user()->id)->get();
      $user_create = auth()->user()->name;
      
        return redirect()->route('sections.index')->with('success','تمت اضافة القسم بنجاح');

    }

    /**
     * Display the specified resource.
     */
    public function show(Section $section)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Section $section)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Section $section)
    {
       $section->update([
            'section_name'=>$request->section_name,
            'description'=>$request->description,
            'created_by'=>auth()->user()->name,
        ]);
        return redirect()->route('sections.index')->with('success','تم تعديل القسم بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Section $section)
    {
        $section->delete();
        return redirect()->route('sections.index')->with('success','تم حذف القسم بنجاح');
    }
}
