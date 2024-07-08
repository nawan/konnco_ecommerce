<?php

use App\Http\Controllers\CheckOutController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LandingpageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/', [LandingpageController::class, 'index']);

Auth::routes();

Route::get('/admin', function () {
    return view('admin.login');
});


Route::get('/home', [HomeController::class, 'index'])->middleware(['auth'])->name('home');

Route::controller(ProfileController::class)->middleware(['auth'])->group(function () {
    Route::get('/profile', 'index')->name('profile');
    Route::post('/profile', 'update')->name('profile.update');
    Route::post('/change-password', 'change_password')->name('profile.change_password');
});

Route::middleware(['auth'])->resource('/product', ProductController::class);
Route::controller(ProductController::class)->middleware(['auth'])->group(function () {
    Route::get('/transaksi', 'transaction')->name('product.transaction');
});

Route::delete('product/{id}', [ProductController::class, 'destroy'])->name('admin.destroy');

Route::middleware(['auth'])->resource('/customer', CustomerController::class);
//Route::middleware(['auth'])->resource('/checkout', CheckOutController::class);
Route::controller(CustomerController::class)->middleware(['auth'])->group(function () {
    Route::get('/customer-profile', 'index')->name('customer.profile');
    Route::post('/customer-update', 'update')->name('customer.update');
    Route::post('/customer-update-password', 'change_password')->name('customer.change_password');
    Route::get('confirmation/{id}', 'confirmation')->name('customer.confirmation');
    Route::get('/cart', 'cart')->name('customer.cart');
    Route::get('/payment', 'paymentList')->name('customer.payment');
    Route::post('/checkout', 'checkout')->name('customer.checkout');
    // Route::post('/pay', 'pay')->name('customer.pay');
    // Route::post('/midtrans-callback', 'callback')->name('callback');
});


Route::get('/about', function () {
    return view('about');
})->name('about');
