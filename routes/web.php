<?php

use Illuminate\Routing\Route as RoutingRoute;
use Illuminate\Support\Facades\Route;
use PhpParser\Node\Stmt\GroupUse;

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

Route::view('/', 'login');
Route::post('login', 'LoginController');
Route::get('dashboard', 'DashboardController@index');
// Route::resource('retribusi', "RetribusiController");
// Route::post('search', 'RetribusiController@searchProduct')->name('search');
Route::group(['prefix' => 'cashier', 'as' => 'cashier.'], function () {
    Route::get('create', 'RetribusiController@create')->name('create');
    Route::post('fetch_data', 'RetribusiController@fetchData');
    Route::post('search', 'RetribusiController@searchProduct');
    Route::post('list', 'RetribusiController@listProduct');
    Route::post('store', 'RetribusiController@store');
});

/**
 * ======================================================================
 * MASTER
 * ======================================================================
 */

Route::group(['prefix' => 'productPrices', 'as' => 'productPrices.'], function () {
    Route::get('/', 'ProductPricesController@index')->name('index');
    Route::post('update', 'ProductPricesController@update')->name('update');
    Route::post('store', 'ProductPricesController@store')->name('store');
    Route::post('destroy', 'ProductPricesController@destroy')->name('delete');
    Route::post('detail', 'ProductPricesController@detailProduct')->name('detail');
    Route::post('selectpicker', 'ProductPricesController@selectpicker');
    Route::post('show', 'ProductPricesController@show');
});

Route::group(['prefix' => 'distributorProduct', 'as' => 'distributorProduct.'], function () {
    Route::get('/', 'DistributorProductController@index')->name('index');
    Route::post('store', 'DistributorProductController@store');
    Route::post('destroy', 'DistributorProductController@destroy')->name('delete');
    Route::post('show', 'DistributorProductController@show');
});

Route::group(['prefix' => 'product', 'as' => 'product.'], function () {
    Route::get('/', 'ProductController@index')->name('create');
    Route::post('update', 'ProductController@update')->name('update');
    Route::post('store', 'ProductController@store')->name('store');
    Route::post('destroy', 'ProductController@destroy')->name('delete');
    Route::post('show', 'ProductController@show');
});

Route::group(['prefix' => 'stockOpname', 'as' => 'stockOpname.'], function () {
    Route::get('/', 'StockOpnameController@index')->name('index');
    Route::post('update', 'StockOpnameController@update')->name('update');
    Route::post('store', 'StockOpnameController@store')->name('store');
    Route::post('destroy', 'StockOpnameController@destroy')->name('delete');
    Route::post('show', 'StockOpnameController@show');
    Route::post('detail', 'StockOpnameController@detailProduct')->name('detail');
    Route::post('selectpicker', 'StockOpnameController@selectpicker');
});

Route::group(['prefix' => 'supply', 'as' => 'supply.'], function () {
    Route::get('/', 'SupplyController@index')->name('index');
    Route::post('update', 'SupplyController@update')->name('update');
    Route::post('store', 'SupplyController@store')->name('store');
    Route::post('destroy', 'SupplyController@destroy')->name('delete');
    Route::post('show', 'SupplyController@show');
    Route::post('search', 'SupplyController@searchProduct');
    Route::post('list', 'SupplyController@listProduct');
});

/**
 * ======================================================================
 * REPORT
 * ======================================================================
 */

Route::group(['prefix' => 'report', 'as' => 'report.'], function () {
    Route::get('salestransaction', 'ReportController@indexSalesTransaction')->name('salestransaction');
    Route::post('listtransaction', 'ReportController@listSalesTransaction');
    Route::get('exportSalesTransaction', 'ReportController@exportSalesTransaction')->name('exportTransaction');
    Route::get('productStock', 'ReportController@indexProductStock')->name('stock');
    Route::post('liststock', 'ReportController@listProductStock');
    // Route::post('destroy', 'ReportController@destroy')->name('delete');
    // Route::post('show', 'ReportController@show')->name('delete');
});

// Route::get('url/{model}','controler@function'); // otomatis yang dipanggil id
// Route::get('url/{model:kolom}','controler@function');
