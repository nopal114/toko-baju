<?php

namespace App\Http\Controllers;

use App\Models\Product;

class CustomerController extends Controller
{
    public function index()
    {
        $products = Product::paginate(10);
        return view('customers.index', compact('products'));
    }
}

