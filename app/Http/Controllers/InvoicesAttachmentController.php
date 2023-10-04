<?php

namespace App\Http\Controllers;

use App\Models\InvoicesAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InvoicesAttachmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(Request $request)
    {
        $image = $request->file('file_name')->getClientOriginalName();
        $path = $request->file('file_name')->storeAs('public/invoices/'.$request->invoice_number,$image);
       InvoicesAttachment::create([
          'invoice_number'=>$request->invoice_number,
          'invoice_id'=>$request->invoice_id ,
          'Created_by'=>auth()->user()->name,
          'file_name'=>$path,
       ]);
       return redirect()->back()->with('success','تم اضافة مرفق جديد بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(InvoicesAttachment $invoicesAttachment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InvoicesAttachment $invoicesAttachment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InvoicesAttachment $invoicesAttachment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InvoicesAttachment $invoicesAttachment)
    {
         $invoicesAttachment->delete();

         Storage::delete($invoicesAttachment->file_name);
         return redirect()->back()->with('success','تم حذف المرفق  بنجاح');
    }
}
