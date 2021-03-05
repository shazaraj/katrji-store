<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//customers
Route::resource('/clients','App\Http\Controllers\ClientController');

//suppliers
Route::resource('/suppliers','App\Http\Controllers\SupplierController');

//store

//Route::get('/materials_store', 'MaterialController@stored_mat');
Route::resource('/store','App\Http\Controllers\StoreController');

//medicines
Route::resource('/materials','App\Http\Controllers\MaterialController');

//invoices
Route::resource('/client_invoices','App\Http\Controllers\ClientInvoicesController');
Route::resource('/supplier_invoices','App\Http\Controllers\SupplierInvoicesController');

//in/out box
Route::resource('/purchases','App\Http\Controllers\PurchaseController');
Route::resource('/sells','App\Http\Controllers\SellsController');

//sale to client supplier.sale1
Route::get('client_bills', 'App\Http\Controllers\ClientController@index')->name("client.sale1");
Route::get('client_bills/{id}/edit', 'App\Http\Controllers\ClientInvoicesController@getbills')->name("client.sale");

//pay from supplier
Route::get('supplier_bills', 'App\Http\Controllers\SupplierController@index')->name("supplier.sale1");
Route::get('supplier_bills/{id}/edit', 'App\Http\Controllers\SupplierInvoicesController@getbills')->name("supplier.sale");

//print client invoices
Route::get('print_invoices', 'App\Http\Controllers\ClientInvoicesController@printInvoices')->name("print.invoices");
Route::post('print_invoices/{id}', 'App\Http\Controllers\ClientInvoicesController@printInvoices')->name("print.invoices");

////print supplier invoices
//Route::get('print_suppliers', 'App\Http\Controllers\SupplierInvoicesController@printInvoices')->name("print.suppliers");
//Route::post('print_suppliers/{id}', 'App\Http\Controllers\SupplierInvoicesController@printInvoices')->name("print.suppliers");


//print client bills
Route::get('print_bills', 'App\Http\Controllers\BillController@printBill')->name("print.bills");
Route::post('print_bills/{id}', 'App\Http\Controllers\BillController@printBill')->name("print.bills");

// client bills
Route::resource('/bills','App\Http\Controllers\BillController');

//get bill filters
Route::get('bills.filter/{id}/edit', 'App\Http\Controllers\BillController@filter')->name("bills.filter1");
Route::get('bills.filter', 'App\Http\Controllers\BillController@filter')->name("bills.filter");

//get all bill to client
Route::get('bills.get/{id}/edit', 'App\Http\Controllers\BillController@get')->name("bills.get1");
Route::get('bills.get', 'App\Http\Controllers\BillController@get')->name("bills.get");

// datatable of bills
Route::get('/bills.index', 'App\Http\Controllers\BillController@index');
Route::get('/bills.index1', 'App\Http\Controllers\BillController@index1');

//notification of expiry
Route::get('/notify', 'App\Http\Controllers\StoreController@notify');
Route::get('/notify1', 'App\Http\Controllers\StoreController@notify1')->name('noti.add');

//reports
Route::get('/report', 'App\Http\Controllers\TodayReportController@getRepo');

//today reports
Route::get('/getDay/{day_repo}', 'App\Http\Controllers\TodayReportController@getReport');
Route::get('/getDay', 'App\Http\Controllers\TodayReportController@dayReport');

//monthly reports
Route::get('/month/report/{from_date}/{to_date}', 'App\Http\Controllers\TodayReportController@getMonth');
Route::get('/month/report', 'App\Http\Controllers\TodayReportController@monthReport');

    Route::get('/backup',function (){
       $back=     Artisan::call('database:backup');
        return view('welcome',compact('back'));
        echo 'تم إنشاء نسخة احتياطية';
    });
