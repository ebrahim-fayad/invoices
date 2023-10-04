<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Section;
use Illuminate\Http\Request;

class InvoicesReports extends Controller
{
        function __construct()
    {
        $this->middleware('permission:تقرير الفواتير', ['only' => ['index','searchInvoices']]);
    }
    public function index(){

        return view('reports.invoices_report');
    }
    public function searchInvoices (Request $request)
    {
        $radio = $request->radio;
        $type = $request->type;
        if ($request->type == 'كل الفواتير' && $request->start_at == null && $request->end_at == null) {
            $invoices = Invoice::all();
            return view('reports.invoices_report', compact('type', 'invoices'));
        }else{
            $start_at = date($request->start_at);
                $end_at = date($request->end_at);
                $invoices = Invoice::whereBetween('invoice_Date', [$start_at, $end_at])->get();
                return view('reports.invoices_report', compact('type', 'invoices', 'start_at', 'end_at'));

        }

        if ($radio == 1) {
            if ($request->type && $request->start_at == null && $request->end_at == null) {
                $type = $request->type;
                $invoices = Invoice::where('Status', $type)->get();
                return view('reports.invoices_report', compact('type', 'invoices'));
            } else {
                $type = $request->type;
                $start_at = date($request->start_at);
                $end_at = date($request->end_at);
                $invoices = Invoice::whereBetween('invoice_Date', [$start_at, $end_at])->where('Status', $type)->get();
                return view('reports.invoices_report', compact('type', 'invoices', 'start_at', 'end_at'));
            }

        }else {
            $invoice_number = $request->invoice_number;
            $invoices = Invoice::where('invoice_number', $invoice_number)->get();
            return view('reports.invoices_report', compact('invoice_number', 'invoices'));
        }
    }
        public function customer(){
            $sections = Section::all();
        return view('reports.customers_report',compact('sections'));
    }
     public function Search_customers(Request $request){
        $Section = $request->Section;
        $product = $request->product;
         $sections = Section::all();
        if($Section && $product && $request->start_at == null && $request->end_at == null){
            $invoices = Invoice::where('section_id',$Section)->where('product',$product)->get();
            return view('reports.customers_report',compact('invoices','Section','sections'));

        }else{
            $start_at = date($request->start_at);
          $end_at = date($request->end_at);
          $invoices = Invoice::whereBetween('invoice_Date',[$start_at,$end_at])->where('section_id',$Section)->where('product',$product)->get();
            return view('reports.customers_report',compact('invoices','Section','sections','start_at','end_at'));
        }
    }
}
