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
            'message' => 'Berikut daftar user 👥',
            'data' => $users->items(),
            'pagination' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
                'next_page' => $users->nextPageUrl(),
                'prev_page' => $users->previousPageUrl(),
            ]
        ]);
    }

    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'User tidak ditemukan 😢'
            ], 404);
        }

        return response()->json([
            'message' => 'Berikut detail user 💻',
            'data' => $user
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'User tidak ditemukan 😢'
            ], 404);
        }

        $request->validate([
            'nama'  => 'sometimes',
            'email' => 'sometimes|email'
        ]);

        $user->update(
            $request->only([
                'nama',
                'email'
            ])
        );

        return response()->json([
            'message' => 'User berhasil diperbarui 🎉',
            'data' => $user
        ]);
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'User tidak ditemukan 😢'
            ], 404);
        }

        $user->delete();

        return response()->json([
            'message' => 'User berhasil dihapus 👋'
        ]);
    }
}
