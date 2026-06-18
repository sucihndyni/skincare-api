<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with([
            'user:id,nama,email',
            'details.product.category',
            'details.product:id,nama_produk,brand,harga,category_id'
        ])->paginate(5);

        $orders->getCollection()->transform(function ($order) {
            return [
                'id' => $order->id,
                'tanggal' => $order->tanggal,
                'total_harga' => $order->total_harga,
                'payment' => $order->payment,
                'user' => [
                    'id' => $order->user->id ?? null,
                    'nama' => $order->user->nama ?? null,
                    'email' => $order->user->email ?? null,
                ],
                'details' => $order->details->map(function ($detail) {
                    return [
                        'id' => $detail->id,
                        'qty' => $detail->qty,
                        'subtotal' => $detail->subtotal,
                        'product' => [
                            'id' => $detail->product->id ?? null,
                            'nama_produk' => $detail->product->nama_produk ?? null,
                            'brand' => $detail->product->brand ?? null,
                            'harga' => $detail->product->harga ?? null,
                            'category' => [
                                'id' => $detail->product->category->id ?? null,
                                'nama_kategori' => $detail->product->category->nama_kategori ?? null,
                            ]
                        ]
                    ];
                })
            ];
        });

        return response()->json([
            'message' => 'Berikut daftar transaksi 🛍️',
            'data' => $orders->items(),
            'pagination' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total(),
                'next_page' => $orders->nextPageUrl(),
                'prev_page' => $orders->previousPageUrl(),
            ]
        ]);
    }

    public function show($id)
    {
        $order = Order::with([
            'user:id,nama,email',
            'details.product.category',
            'details.product:id,nama_produk,brand,harga,category_id'
        ])->find($id);

        if (!$order) {
            return response()->json(['message' => 'Order tidak ditemukan 😢'], 404);
        }

        $data = [
            'id' => $order->id,
            'tanggal' => $order->tanggal,
            'total_harga' => $order->total_harga,
            'payment' => $order->payment,
            'user' => [
                'id' => $order->user->id ?? null,
                'nama' => $order->user->nama ?? null,
                'email' => $order->user->email ?? null,
            ],
            'details' => $order->details->map(function ($detail) {
                return [
                    'id' => $detail->id,
                    'qty' => $detail->qty,
                    'subtotal' => $detail->subtotal,
                    'product' => [
                        'id' => $detail->product->id ?? null,
                        'nama_produk' => $detail->product->nama_produk ?? null,
                        'brand' => $detail->product->brand ?? null,
                        'harga' => $detail->product->harga ?? null,
                        'category' => [
                            'id' => $detail->product->category->id ?? null,
                            'nama_kategori' => $detail->product->category->nama_kategori ?? null,
                        ]
                    ]
                ];
            })
        ];

        return response()->json(['message' => 'Berikut detail transaksi 📄', 'data' => $data]);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required',
                'tanggal' => 'required',
                'payment' => 'required'
            ]);

            $order = Order::create([
                'user_id' => $request->user_id,
                'tanggal' => $request->tanggal,
                'payment' => $request->payment,
                'total_harga' => 0
            ]);

            return response()->json(['message' => 'Transaksi berhasil dibuat 🎉', 'data' => $order], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal membuat transaksi 😢', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $order = Order::find($id);
        if (!$order) return response()->json(['message' => 'Transaksi tidak ditemukan 😒'], 404);
        $order->delete();
        return response()->json(['message' => 'Transaksi berhasil dihapus 👋']);
    }
}