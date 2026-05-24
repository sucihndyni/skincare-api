<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Order;

class OrderDetailController extends Controller
{
    public function store(Request $request, $id)
    {
        try {

            $request->validate([
                'product_id' => 'required',
                'qty' => 'required|numeric|min:1'
            ]);

            $product = Product::find($request->product_id);

            if (!$product) {
                return response()->json([
                    'message' => 'Produk tidak ditemukan 😢'
                ], 404);
            }

            $subtotal = $product->harga * $request->qty;

            $detail = OrderDetail::create([
                'order_id' => $id,
                'product_id' => $request->product_id,
                'qty' => $request->qty,
                'subtotal' => $subtotal
            ]);

            $total = OrderDetail::where('order_id', $id)
                        ->sum('subtotal');

            Order::where('id', $id)
                ->update([
                    'total_harga' => $total
                ]);

            return response()->json([
                'message' => 'Yey, item berhasil ditambahkan.🎉',
                'data' => $detail
            ], 201);

        } catch (\Exception $e) {

            return response()->json([
                'message' => 'Item gagal ditambahkan 😢',
                'error' => $e->getMessage()
            ], 500);

        }
    }

    public function destroy($orderId, $detailId)
    {
        $detail = OrderDetail::where('order_id', $orderId)
                    ->where('id', $detailId)
                    ->first();

        if (!$detail) {
            return response()->json([
                'message' => 'Item ga ada 😒'
            ], 404);
        }

        $detail->delete();

        $total = OrderDetail::where('order_id', $orderId)
                    ->sum('subtotal');

        Order::where('id', $orderId)
            ->update([
                'total_harga' => $total
            ]);

        return response()->json([
            'message' => 'Yeay, item berhasil dihapus 👋'
        ]);
    }
}
