<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(5);

        return response()->json([
            'success' => true,
            'message' => 'Berikut ini adalah daftar user yang tersedia.👥',
            'data' => $users
        ]);
    }

    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'response' => false,
                'message' => 'User itu tidak terdaftar disini.😠'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Berikut ini adalah info tentang user.💻',
            'data' => $user
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'response' => false,
                'message' => 'Apa yang mau diubah, usernya tidak ada.😡'
            ], 404);
        }

        $request->validate([
            'nama' => 'required',
            'email' => 'required|email'
        ]);

        $user->update([
            'nama' => $request->nama,
            'email' => $request->email
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Yeay, info user berhasil diperbarui✨',
            'data' => $user
        ]);
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'response' => false,
                'message' => 'User ga ada, mau hapus apa?😒'
            ], 404);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Yeay, user berhasil dihapus.🎉'
        ]);
    }
}