<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index()
    {
        $categories = Barang::with('products')->paginate(10);
        return view('barang.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name'
        ]);

        Barang::create($validated);

        return redirect()->route('barang.index')
                        ->with('success', 'Kategori berhasil ditambahkan');
    }

    public function show(Barang $category)
    {
        $category->load('products');
        return view('categories.show', compact('category'));
    }

    public function edit(Barang $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Barang $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id
        ]);

        $category->update($validated);

        return redirect()->route('categories.index')
                        ->with('success', 'Kategori berhasil diperbarui');
    }

    public function destroy(Barang $category)
    {
        if ($category->products()->count() > 0) {
            return redirect()->route('categories.index')
                           ->with('error', 'Kategori tidak dapat dihapus karena masih memiliki produk');
        }

        $category->delete();

        return redirect()->route('categories.index')
                        ->with('success', 'Kategori berhasil dihapus');
    }
}