<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// Ubah route default ke dashboard atau halaman produk
Route::get('/', function () {
    return redirect()->route('login');
});

// Login routes
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware(['products','index'])->group(function () {
    Route::get('/products', function () {
        return view('products.index');
    });
});

Route::middleware(['customers','index'])->group(function () {
    Route::get('/customers', function () {
        return view('customers.index');
    });
});



Route::middleware('auth')->get('/customers', [CustomerController::class, 'index'])->name('customers.index');


// Atau buat halaman dashboard khusus
// Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Routes untuk Categories
Route::resource('categories', CategoryController::class);

// Routes untuk Products
Route::resource('products', ProductController::class);

// Routes untuk Orders
Route::resource('orders', OrderController::class);
