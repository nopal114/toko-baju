<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;


class CustomerController extends Controller
{
    public function index()
{
    // Cek apakah user bukan customer
    if (!Auth::check() || Auth::user()->role !== 'customer') {
    abort(403, 'Akses hanya untuk customer.');
}
    $products = Product::paginate(10);
    return view('customers.index', compact('products'));
}

}

