<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderDetail;

class OrderDetailController extends Controller
{
    public function index()
    {
        $details = OrderDetail::with(['order', 'product'])->get();

        return response()->json([
            'success' => true,
            'message' => 'Berikut adalah semua detail order yang ada.📋',
            'data' => $details
        ]);
    }   

    public function show($id)
    {
        $detail = OrderDetail::with(['order', 'product'])->find($id);

        if (!$detail) {
            return response()->json([
                'response' => false,
                'message' => 'Detail ordernya ga ketemu.😢'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Berikut adalah info tentang detail order yang dicari.📄',
            'data' => $detail
        ]);
    }
    
    public function store(Request $request)
    {
        try {

            $request->validate([
                'order_id' => 'required',
                'product_id' => 'required',
                'qty' => 'required|numeric',
                'subtotal' => 'required|numeric'
            ]);

            $detail = OrderDetail::create([
                'order_id' => $request->order_id,
                'product_id' => $request->product_id,
                'qty' => $request->qty,
                'subtotal' => $request->subtotal
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Yey, item berhasil ditambahkan.🎉',
                'data' => $detail
            ], 201);

        } catch (\Exception $e) {

            return response()->json([
                'response' => false,
                'message' => 'Item gagal ditambahkan ke transaksi😢',
                'error' => $e->getMessage()
            ], 500);

        }
    }

    public function destroy(Request $request)
    {
        $detail = OrderDetail::where('order_id', $request->order_id)
                    ->where('id', $request->detail_id)
                    ->first();

        if (!$detail) {
            return response()->json([
                'response' => false,
                'message' => 'Item ga ada, mau hapus apa?😒'
            ], 404);
        }

        $detail->delete();

        return response()->json([
            'success' => true,
            'message' => 'Yeay, item berhasil dihapus dari transaksi.👋'
        ]);
    }

}
