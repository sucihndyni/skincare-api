<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        return response()->json([
            'success' => true,
            'message' => 'Berikut ini info kategori produk yang tersedia.🧴',
            'data' => $categories
            
        ]);
    }

    public function show($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'response' => false,
                'message' => 'Category tidak ditemukan.⚠️'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Berikut adalah info Categorinya.😊',
            'data' => $category
        ]);
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'response' => false,
                'message' => 'Category tidak ada, mau ngapain.😒'
            ], 404);
        }

        $request->validate([
            'nama_kategori' => 'required'
        ]);

        $category->update([
            'nama_kategori' => $request->nama_kategori
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Yey, Category berhasil diperbarui.🎉',
            'data' => $category
        ]);
    }

    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'response' => false,
                'message' => 'Category yang mau dihapus ga ada.😒',
            ], 404);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Yey, Category berhasil dihapus.🙌'
        ]);
    }
}
