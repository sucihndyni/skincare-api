<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::paginate(5);

        return response()->json([
            'message' => 'Berikut ini info kategori produk yang tersedia 🧴',
            'data' => $categories->items(),
            'pagination' => [
                'current_page' => $categories->currentPage(),
                'last_page' => $categories->lastPage(),
                'per_page' => $categories->perPage(),
                'total' => $categories->total(),
                'next_page' => $categories->nextPageUrl(),
                'prev_page' => $categories->previousPageUrl(),
            ]
        ]);
    }

    public function show($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'message' => 'Category tidak ditemukan 😢'
            ], 404);
        }

        return response()->json([
            'message' => 'Berikut detail category 😊',
            'data' => $category
        ]);
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'message' => 'Category tidak ditemukan 😢'
            ], 404);
        }

        $request->validate([
            'nama_kategori' => 'sometimes'
        ]);

        $category->update(
            $request->only([
                'nama_kategori'
            ])
        );

        return response()->json([
            'message' => 'Category berhasil diperbarui 🎉',
            'data' => $category
        ]);
    }

    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'message' => 'Category tidak ditemukan 😢'
            ], 404);
        }

        $category->delete();

        return response()->json([
            'message' => 'Category berhasil dihapus 👋'
        ]);
    }
}
