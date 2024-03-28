<?php

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

Auth::routes();

Route::prefix('/admin')
    ->middleware('auth', 'admin')
    ->group(function() {
        Route::get('/', "App\Http\Controllers\HomeController@index");
        Route::resource('user', 'App\Http\Controllers\UserController');
        Route::resource('product', 'App\Http\Controllers\ProductController');
        Route::resource('pembelian', 'App\Http\Controllers\PembelianController');
        Route::resource('product-category', 'App\Http\Controllers\ProductCategoryController');
        Route::resource('customer', 'App\Http\Controllers\CustomerController');
        Route::resource('coupon', 'App\Http\Controllers\CouponController');

        Route::get('/company', 'App\Http\Controllers\CompanyProfileController@index')->name('companyProfile.index');
        Route::post('/company', 'App\Http\Controllers\CompanyProfileController@save')->name('companyProfile.save');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', 'App\Http\Controllers\HomeController@index')->name('home');
    Route::resource('product', 'App\Http\Controllers\ProductController');
    Route::resource('retur', 'App\Http\Controllers\ReturController');
    Route::post('/retur/report', 'App\Http\Controllers\ReturController@report')->name('retur.report');
    Route::post('/product/report', 'App\Http\Controllers\ProductController@report')->name('product.report');
    Route::post('/pembelian/report', 'App\Http\Controllers\PembelianController@report')->name('pembelian.report');
    Route::post('/pembelian/{id}', 'App\Http\Controllers\PembelianController@acc')->name('pembelian.acc');
    Route::resource('product-category', 'App\Http\Controllers\ProductCategoryController');
    Route::post('/sale/getCoupon', 'App\Http\Controllers\SaleController@getCoupon')->name('sale.getCoupon');
    Route::get('/sale/hapus/{id}', 'App\Http\Controllers\SaleController@returBarang')->name('sale.retur');
    Route::resource('sale', 'App\Http\Controllers\SaleController');
    Route::post('/transaction/storeTransaction', 'App\Http\Controllers\TransactionController@storeTransaction')->name('transaction.storeTransaction');
    Route::post('/transaction/report', 'App\Http\Controllers\TransactionController@report')->name('transaction.report');
    Route::get('/struk/{transaction_code?}', 'App\Http\Controllers\TransactionController@struk')->name('transaction.struk');
    Route::resource('transaction', 'App\Http\Controllers\TransactionController')->except([
        'create'
    ]);
    Route::get('/transaction/create/{transaction_code?}', 'App\Http\Controllers\TransactionController@create')->name('transaction.create');
    Route::get('/autocomplete', 'App\Http\Controllers\AutosuggestController@autocomplete')->name('suggest');

    Route::get('/profile', 'App\Http\Controllers\ProfileController@index')->name('profile.index');
    Route::put('/profile', 'App\Http\Controllers\ProfileController@update')->name('profile.update');
});

Auth::routes(['verify' => true]);