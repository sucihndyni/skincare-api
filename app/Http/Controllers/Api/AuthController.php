<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $admin = Admin::where('username', $request->username)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return response()->json(['message' => 'Username atau password salah'], 401);
        }

        $token = Str::random(40);
        $refresh = Str::random(60);

        $admin->token = $token;
        $admin->token_created_at = now();
        $admin->refresh_token = $refresh;
        $admin->refresh_token_created_at = now();
        $admin->save();

        return response()->json([
            'message' => 'Login berhasil',
            'token' => $token,
            'refresh_token' => $refresh,
            'token_created_at' => $admin->token_created_at->toISOString(),
            'refresh_token_created_at' => $admin->refresh_token_created_at->toISOString(),
            'expires_in' => (int) env('TOKEN_TTL_SECONDS', 60),
            'refresh_expires_in' => (int) env('REFRESH_TTL_SECONDS', 604800),
        ]);
    }

    public function refresh(Request $request): JsonResponse
    {
        $refresh = $request->bearerToken();

        if (!$refresh) {
            return response()->json(['message' => 'Refresh token diperlukan di header Authorization'], 401);
        }

        $admin = Admin::where('refresh_token', $refresh)->first();
        if (!$admin) {
            return response()->json(['message' => 'Refresh token tidak valid'], 401);
        }

        $refreshTtl = (int) env('REFRESH_TTL_SECONDS', 604800);
        if ($admin->refresh_token_created_at && now()->greaterThanOrEqualTo($admin->refresh_token_created_at->copy()->addSeconds($refreshTtl))) {
            $admin->refresh_token = null;
            $admin->refresh_token_created_at = null;
            $admin->save();

            return response()->json(['message' => 'Refresh token sudah kedaluwarsa'], 401);
        }

        $newToken = Str::random(40);
        $newRefresh = Str::random(60);

        $admin->token = $newToken;
        $admin->token_created_at = now();
        $admin->refresh_token = $newRefresh;
        $admin->refresh_token_created_at = now();
        $admin->save();

        return response()->json([
            'message' => 'Token diperbarui',
            'token' => $newToken,
            'refresh_token' => $newRefresh,
            'token_created_at' => $admin->token_created_at->toISOString(),
            'refresh_token_created_at' => $admin->refresh_token_created_at->toISOString(),
            'expires_in' => (int) env('TOKEN_TTL_SECONDS', 60),
            'refresh_expires_in' => $refreshTtl,
        ]);
    }
}
