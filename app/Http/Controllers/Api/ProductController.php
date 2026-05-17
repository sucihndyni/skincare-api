<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->get();

        return response()->json([
            'success' => true,
            'message' => 'Berikut adalah semua daftar produk yan ada.💄',
            'data' => $products
        ]);
    }

    public function show($id)
    {
        $product = Product::with('category')->find($id);

        if (!$product) {
            return response()->json([
                'response' => false,
                'message' => 'Produk tidak ada, coba cari yang lain.😢'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Berikut adalah detail produk yang dicari ✨',
            'data' => $product
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return response()->json([
                    'response' => false,
                    'message' => 'Produk tidak ditemukan, mau ubah apa?😢'
                ], 404);
            }

            $request->validate([
                'nama_produk' => 'required',
                'brand'       => 'required',
                'harga'       => 'required|numeric',
                'stok'        => 'required|numeric',
                'gambar'      => 'required',
                'category_id' => 'required'
            ]);

            $product->update([
                'nama_produk' => $request->nama_produk,
                'brand'       => $request->brand,
                'harga'       => $request->harga,
                'stok'        => $request->stok,
                'gambar'      => $request->gambar,
                'category_id' => $request->category_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Yeay, info produk berhasil diperbarui✨',
                'data'    => $product
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'response' => false,
                'message' => 'Produk gagal diperbarui.😢',
                'error'    => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'response' => false,
                'message' => 'Produk ga ada, mau hapus apa?😒'
            ], 404);
        }

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Yeay, produk berhasil dihapus👋'
        ]);
    }
}
