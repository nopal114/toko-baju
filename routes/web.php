<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// Ubah route default ke dashboard atau halaman produk
Route::get('/', function () {
    return redirect()->route('products.index');
});

// Atau buat halaman dashboard khusus
// Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Routes untuk Categories
Route::resource('categories', CategoryController::class);

// Routes untuk Products
Route::resource('products', ProductController::class);

// Routes untuk Orders
Route::resource('orders', OrderController::class);