<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BarangController;
use Illuminate\Support\Facades\Route;

// ubah route default ke dashboard atau halaman produk
Route::get('/', function () {
    return redirect()->route('login');
});

// login routes
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// protected routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('products', ProductController::class);
});
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('categories', CategoryController::class);
});
Route::middleware(['auth', 'admin'])->group(function (){
    Route::resource('barang', BarangController::class);
});

Route::middleware(['auth', 'customer'])->group(function () {
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
});


Route::middleware('auth')->get('/customers', [CustomerController::class, 'index'])->name('customers.index');


// Routes untuk Categories
//Route::resource('categories', CategoryController::class);

// Routes untuk Products
//Route::resource('products', ProductController::class);

// Routes untuk Orders
Route::resource('orders', OrderController::class);
