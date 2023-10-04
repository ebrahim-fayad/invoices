<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoiceDetailController;
use App\Http\Controllers\InvoicesAttachmentController;
use App\Http\Controllers\InvoicesReports;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\TestLogOutController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
})->name('logging');


// Auth::routes();
Auth::routes(['register' => false]);
Route::middleware(['auth', 'enterUser'])->group(function () {

    Route::get('/home', [HomeController::class, 'index'])->name('home');
});
Route::group(['prefix' => 'admin','middleware'=>['auth']], function() {
    //

Route::resources([
    'invoices'=>InvoiceController::class,
    'sections'=>SectionController::class,
    'products'=>ProductController::class,
    'invoicesDetails'=>InvoiceDetailController::class,
    'invoicesAttachments'=>InvoicesAttachmentController::class,
    'roles'=>RoleController::class,
    'users'=>UserController::class,
    ]);
    Route::post('invoices/invoicesStatus',[InvoiceController::class,'invoicesStatus'])->name('invoices.invoicesStatus');
    Route::get('export/invoices', [InvoiceController::class, 'export'])->name('invoices.export');
    Route::post('invoices/Status_Update/{id}', [InvoiceController::class,'Status_Update'])->name('invoices.Status_Update');
    Route::get('trashed/invoices',[InvoiceController::class,'trashedInvoices'])->name('invoices.trashedInvoices');
    Route::get('trashed/restoreInvoices/{id}',[InvoiceController::class,'restoreInvoices'])->name('invoices.restoreInvoices');
    Route::delete('trashed/hardDeleteInvoices/{id}',[InvoiceController::class,'hardDeleteInvoices'])->name('invoices.hardDeleteInvoices');
    Route::get('invoices/printInvoice/{id}',[InvoiceController::class,'printInvoice'])->name('invoices.printInvoice');
    Route::get('View_file/{id}',[InvoiceDetailController::class,'open_file'])->name('invoiceDetail.open_file');
    Route::get('download/{id}',[InvoiceDetailController::class,'get_file'])->name('invoiceDetail.get_file');
    Route::get('invoicesReports',[InvoicesReports::class,'index'])->name('invoicesReports.index');
    Route::post('invoicesReports/searchInvoices',[InvoicesReports::class,'searchInvoices'])->name('invoicesReports.searchInvoices');
    Route::get('customersReports',[InvoicesReports::class,'customer'])->name('invoicesReports.customer');
    Route::post('customersReports/Search_customers',[InvoicesReports::class,'Search_customers'])->name('invoicesReports.Search_customers');
    Route::get('markAll',[InvoiceDetailController::class,'markAll'])->name('markAll');
});

Route::get('section/{id}',[InvoiceController::class,'getProducts'])->name('sections.getProducts');






Route::get('/{page}', [AdminController::class,'index'])->name('admin.index');
