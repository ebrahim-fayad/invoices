<?php

use App\Models\Invoice;

function allInvoices(){
    $invoices = Invoice::all();
    return $invoices;
}
