<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'details.product'])->get();

        return response()->json([
            'success' => true,
            'message' => 'Berikut ini adalah daftar semua order pelanggan.🛍️',
            'data' => $orders
        ]);
    }

    public function show($id)
    {
        $order = Order::with(['user', 'details.product'])->find($id);

        if (!$order) {
            return response()->json([
                'response' => false,
                'message' => 'Order itu ga ada di database.😢'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Berikut adalah info order yang diminta.📄',
            'data' => $order
        ]);
    }

    public function store(Request $request)
{
    try {

        $request->validate([
            'user_id' => 'required',
            'tanggal' => 'required',
            'total_harga' => 'required|numeric'
        ]);

        $order = Order::create([
            'user_id' => $request->user_id,
            'tanggal' => $request->tanggal,
            'total_harga' => $request->total_harga
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Yey, order baru berhasil ditambahkan.🎉',
            'data' => $order
        ], 201);

    } catch (\Exception $e) {

        return response()->json([
            'response' => false,
            'message' => 'Gagal menambahkan order.😢',
            'error' => $e->getMessage()
        ], 500);

    }
}

    public function destroy($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'response' => false,
                'message' => 'Transaksi ga ada, mau hapus apa?😒'
            ], 404);
        }

        $order->delete();

        return response()->json([
            'success' => true,
            'message' => 'Yeay, transaksi berhasil dihapus.👋'
        ]);
    }
}
