<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'orderItems.product'])->paginate(10);
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $products = Product::where('stock', '>', 0)->get();
        return view('orders.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1'
        ]);

        DB::transaction(function () use ($validated) {
            $totalPrice = 0;
            $orderItems = [];

            // Hitung total dan siapkan order items
            foreach ($validated['products'] as $item) {
                $product = Product::find($item['product_id']);
                
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stok {$product->name} tidak mencukupi");
                }

                $subtotal = $product->price * $item['quantity'];
                $totalPrice += $subtotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $subtotal
                ];
            }

            // Buat order
            $order = Order::create([
                'user_id' => $validated['user_id'],
                'total_price' => $totalPrice,
                'status' => 'pending'
            ]);

            // Buat order items dan update stok
            foreach ($orderItems as $item) {
                $order->orderItems()->create($item);
                
                $product = Product::find($item['product_id']);
                $product->decrement('stock', $item['quantity']);
            }
        });

        return redirect()->route('orders.index')
                        ->with('success', 'Pesanan berhasil dibuat');
    }

    public function show(Order $order)
    {
        $order->load(['user', 'orderItems.product']);
        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        return view('orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
        ]);

        $order->update($validated);

        return redirect()->route('orders.index')
                        ->with('success', 'Status pesanan berhasil diperbarui');
    }

    public function destroy(Order $order)
    {
        if ($order->status !== 'pending') {
            return redirect()->route('orders.index')
                           ->with('error', 'Hanya pesanan dengan status pending yang dapat dihapus');
        }

        // Kembalikan stok produk
        foreach ($order->orderItems as $item) {
            $product = Product::find($item->product_id);
            $product->increment('stock', $item->quantity);
        }

        $order->delete();

        return redirect()->route('orders.index')
                        ->with('success', 'Pesanan berhasil dihapus');
    }
}