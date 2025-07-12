<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Helpers\AESCBC;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->paginate(10);
$decryptedToken = null;

    $userId = Auth::id();

if ($userId && Storage::exists("tokens/{$userId}.token")) {
    $token = Storage::get("tokens/{$userId}.token");

    $decryptedToken = AESCBC::decrypt(
        $token,
        config('app.aes_key'),
        config('app.aes_iv')
    );
}

    return view('products.index', [
        'products' => $products,
        'decryptedToken' => $decryptedToken
    ]);
}   
    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($validated);

        return redirect()->route('products.index')
                        ->with('success', 'Produk berhasil ditambahkan');
    }

    public function show($id)
{
    $product = Product::with('category')->findOrFail($id);
    $category = $product->category; // Ambil category dari relasi product
    
    return view('products.show', compact('product', 'category'));
}

public function edit($id)
{
    $product = Product::findOrFail($id);
    $categories = Category::all(); // Untuk dropdown kategori
    
    return view('products.edit', compact('product', 'categories'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'category_id' => 'required|exists:categories,id',
    ]);

    $product = Product::findOrFail($id);
    $product->update($request->all());

    return redirect()->route('products.index')
                     ->with('success', 'Product berhasil diupdate');
}

    public function destroy(Product $product)
    {
        // Hapus gambar jika ada
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('products.index')
                        ->with('success', 'Produk berhasil dihapus');
    }
}