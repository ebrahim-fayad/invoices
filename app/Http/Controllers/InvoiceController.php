<?php

namespace App\Http\Controllers;

use App\Exports\InvoicesExport;
use App\Mail\AddInvoice;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\InvoicesAttachment;
use App\Models\Product;
use App\Models\Section;
use App\Models\User;

use App\Notifications\Add_Invoice;
use App\Notifications\TestNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class InvoiceController extends Controller
{
    function __construct()
    {

    $this->middleware('permission:تغير حالة الدفع', ['only' => ['Status_Update','show']]);
    $this->middleware('permission:قائمة الفواتير', ['only' => ['index']]);
    $this->middleware('permission:الفواتير المدفوعة|الفواتير المدفوعة جزئيا|الفواتير الغير مدفوعة', ['only' => ['invoicesStatus']]);
    $this->middleware('permission:اضافة فاتورة', ['only' => ['store','create']]);
    $this->middleware('permission:تعديل الفاتورة', ['only' => ['edit','update']]);
    $this->middleware('permission:طباعةالفاتورة', ['only' => ['printInvoice']]);
    $this->middleware('permission:الفواتير المحذوفة', ['only' => ['trashedInvoices']]);
    }
    public function index()
    {

            $invoices = Invoice::all();

        return view('invoices.index',compact('invoices'));
    }
    public function invoicesStatus(Request $request){

            $invoices = Invoice::where('Value_Status',$request->Value_Status)->get();


        return view('invoices.index',compact('invoices'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sections = Section::all();
        return view('invoices.add_invoices',compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         Invoice::create([
            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'Due_date' => $request->Due_date,
            'product' => $request->product,
            'section_id' => $request->Section,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount' => $request->Discount,
            'Value_VAT' => $request->Value_VAT,
            'Rate_VAT' => $request->Rate_VAT,
            'Total' => $request->Total,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $request->note,
        ]);

        $invoice_id = Invoice::latest()->first()->id;
        InvoiceDetail::create([
            'id_Invoice' => $invoice_id,
            'invoice_number' => $request->invoice_number,
            'product' => $request->product,
            'Section' => $request->Section,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $request->note,
            'user' => auth()->user()->name,
        ]);
        //  if ($request->hasFile('pic')) {

        //     $invoice_id = Invoice::latest()->first()->id;
        //     $image = $request->file('pic');
        //     $file_name = $image->getClientOriginalName();
        //     $invoice_number = $request->invoice_number;

        //     $attachments = new InvoicesAttachment();
        //     $attachments->file_name = $file_name;
        //     $attachments->invoice_number = $invoice_number;
        //     $attachments->Created_by = Auth::user()->name;
        //     $attachments->invoice_id = $invoice_id;
        //     $attachments->save();

        //     // move pic
        //     $imageName = $request->pic->getClientOriginalName();
        //     $request->pic->move(public_path('Attachments/' . $invoice_number), $imageName);


        // }
        if ($request->has('pic')) {
            $invoice_id = Invoice::latest()->first()->id;
            $invoice_number = Invoice::latest()->first()->invoice_number;
            $image = $request->file('pic')->getClientOriginalName();

            $path =  $request->file('pic')->storeAs('public/invoices/'.$invoice_number,$image);
            InvoicesAttachment::create([
               'file_name'=>$path,
               'invoice_id'=>$invoice_id,
               'invoice_number'=>$request->invoice_number,
                'Created_by'=>auth()->user()->name,

            ]);
        }
        $users = User::where('id','!=',auth()->user()->id)->get();
        $user = auth()->user()->email;
        $invoice = Invoice::latest()->first();

        Mail::to($user)->send(new AddInvoice($invoice));
        Notification::send($users,new TestNote($invoice->id));
        return redirect()->route('invoices.index')->with('success','تمت اضافة الفاتورة بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        return view('invoices.status',compact('invoice'));
    }

    public function Status_Update(Request $request, $id)
    {
        $invoice =Invoice::findOrFail($id);
        if ($request->Status == 'مدفوعة') {
            $invoice->update([
                'Value_Status'=>1,
                'Status'=>$request->Status,
                'Payment_Date'=>$request->Payment_Date
            ]);
            InvoiceDetail::create([
                'id_Invoice' =>$invoice-> id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'Section' => $request->Section,
                'Status' => $request->Status,
                'Value_Status' => 1,
                'note' => $request->note,
                'Payment_Date'=>$request->Payment_Date,
                'user' => auth()->user()->name,
            ]);
        }else{
             $invoice->update([
                'Value_Status'=>3,
                'Status'=>$request->Status,
                'Payment_Date'=>$request->Payment_Date
            ]);
            InvoiceDetail::create([
                'id_Invoice' =>$invoice-> id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'Section' => $request->Section,
                'Status' => $request->Status,
                'Value_Status' => 3,
                'note' => $request->note,
                'Payment_Date'=>$request->Payment_Date,
                'user' => auth()->user()->name,
            ]);
        }
        return redirect()->route('invoices.index')->with('success','تم دفع الفاتورة');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        $sections = Section::all();
        return view('invoices.edit',compact('sections','invoice'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        $invoice->update([
            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'Due_date' => $request->Due_date,
            'product' => $request->product,
            'section_id' => $request->Section,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount' => $request->Discount,
            'Value_VAT' => $request->Value_VAT,
            'Rate_VAT' => $request->Rate_VAT,
            'Total' => $request->Total,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $request->note,
        ]);
        return redirect()->route('invoices.index')->with('success','تم تعديل الفاتورة بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {


            $invoice->delete();
            return redirect()->back()->with('success','تم حذف الفاتورة  بنجاح');


    }
    public function getProducts($id)
    {
        $products = Product::where('section_id',$id)->pluck('product_name','id');

        return json_encode($products);
    }
    public function  trashedInvoices (){

        $invoices = Invoice::onlyTrashed()->get();
        return view('invoices.trashed',compact('invoices'));
    }
    public function  restoreInvoices ($id){
       $invoice = Invoice::withTrashed()->where('id',$id);
        $invoice->restore();
        return redirect()->route('invoices.index')->with('success','تم استرداد الفاتورة بنجاح');

    }
    public function hardDeleteInvoices ($id){

          $invoice = Invoice::withTrashed()->where('id',$id)->first();
          Storage::deleteDirectory('public/invoices/'.$invoice->invoice_number);
          $invoice->forceDelete();
          return redirect()->route('invoices.index')->with('success','تم حذف الفاتورة نهائيا بنجاح');
    }
    public function printInvoice ($id){
        $invoices = Invoice::where('id',$id)->first();
          return view('invoices.print_invoice',compact('invoices'));
    }
     public function export()
    {
         return Excel::download(new InvoicesExport(), 'invoices.xlsx');
//        return 1;
    }
}
