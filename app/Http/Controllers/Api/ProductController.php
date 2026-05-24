<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')
                    ->paginate(5);

        $products->getCollection()->transform(function ($product) {

            return [
                'id' => $product->id,
                'nama_produk' => $product->nama_produk,
                'brand' => $product->brand,
                'harga' => $product->harga,
                'stok' => $product->stok,
                'gambar' => $product->gambar,

                'category' => [
                    'id' => $product->category->id ?? null,
                    'nama_kategori' => $product->category->nama_kategori ?? null,
                ]
            ];
        });

        return response()->json([
            'message' => 'Berikut daftar produk 💄',
            'data' => $products->items(),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
                'next_page' => $products->nextPageUrl(),
                'prev_page' => $products->previousPageUrl(),
            ]
        ]);
    }

    public function show($id)
    {
        $product = Product::with('category')->find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Produk tidak ditemukan 😢'
            ], 404);
        }

        $data = [
            'id' => $product->id,
            'nama_produk' => $product->nama_produk,
            'brand' => $product->brand,
            'harga' => $product->harga,
            'stok' => $product->stok,
            'gambar' => $product->gambar,

            'category' => [
                'id' => $product->category->id ?? null,
                'nama_kategori' => $product->category->nama_kategori ?? null,
            ]
        ];

        return response()->json([
            'message' => 'Berikut detail produk ✨',
            'data' => $data
        ]);
    }

    public function update(Request $request, $id)
    {
        try {

            $product = Product::find($id);

            if (!$product) {
                return response()->json([
                    'message' => 'Produk tidak ditemukan 😢'
                ], 404);
            }

            $request->validate([
                'nama_produk' => 'sometimes',
                'brand'       => 'sometimes',
                'harga'       => 'sometimes|numeric',
                'stok'        => 'sometimes|numeric',
                'gambar'      => 'sometimes',
                'category_id' => 'sometimes'
            ]);

            $product->update(
                $request->only([
                    'nama_produk',
                    'brand',
                    'harga',
                    'stok',
                    'gambar',
                    'category_id'
                ])
            );

            return response()->json([
                'message' => 'Produk berhasil diperbarui 🎉',
                'data' => $product->load('category')
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'message' => 'Produk gagal diperbarui 😢',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Produk tidak ditemukan 😢'
            ], 404);
        }

        $product->delete();

        return response()->json([
            'message' => 'Produk berhasil dihapus 👋'
        ]);
    }
}
