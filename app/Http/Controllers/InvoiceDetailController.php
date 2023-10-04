<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\InvoicesAttachment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use File;

class InvoiceDetailController extends Controller
{

    function __construct()
    {


    $this->middleware('permission:تفاصيل الفاتورة', ['only' => ['show']]);

    }

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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $invoices = Invoice::where('id',$id)->first();
        $details = InvoiceDetail::where('id_invoice',$id)->get();
        $attachments= InvoicesAttachment::where('invoice_id',$id)->get();
        $not_id = DB::table('notifications')->where('data->invoice_id',$id)->pluck('id');
        DB::table('notifications')->where('id',$not_id)->update([
            'read_at' => now(),
        ]);
       return view('invoices.details_invoice',compact('invoices','details','attachments'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InvoiceDetail $invoiceDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InvoiceDetail $invoiceDetail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InvoiceDetail $invoiceDetail)
    {
        //
    }
     public function get_file($id)

    {
    //     $path='Attachments';
    //   $pathToFile=public_path($path.'/'.$invoice_number.'/'.$file_name);
    //     return response()->download( $pathToFile);
    $attachment = InvoicesAttachment::where('id',$id)->first();
    $file = public_path(Storage::url($attachment->file_name));
        return response()->download( $file);
    }



    public function open_file($id)

    {

        //لو هستعمل في التخزين طريقة disk
    //   $path='Attachments';
    //   $pathToFile=public_path($path.'/'.$invoice_number.'/'.$file_name);
    //     return response()->file($pathToFile);
    //second way
    $attachment = InvoicesAttachment::where('id',$id)->first();
    $file = public_path(Storage::url($attachment->file_name));
    return response()->file($file);
    }
    public  function  markAll(){
       $user = User::findOrFail(auth()->user()->id);
        foreach ($user->unreadNotifications as $notification) {
            $notification->markAsRead();
            
            
        }
        return redirect()->back();
    }
}
