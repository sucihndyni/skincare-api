<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $admin = Admin::where('username', $request->username)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {

            return response()->json([
                'message' => 'Username atau password salah'
            ], 401);
        }

        $token = Str::random(40);

        $admin->token = $token;
        $admin->save();

        return response()->json([
            'message' => 'Yeay Login berhasil, ini token nya.🎉',
            'token' => $token,
        ]);
    }
}
