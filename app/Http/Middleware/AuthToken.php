<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Log;

class AuthToken
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'Authorization header (Bearer token) diperlukan'], 401);
        }

        $admin = Admin::where('token', $token)->first();
        if (!$admin) {
            return response()->json(['message' => 'Token tidak valid'], 401);
        }

        if (!$admin->token_created_at) {
            Log::warning('Token tanpa token_created_at', ['token' => substr($token, 0, 10)]);
            return response()->json(['message' => 'Token tidak valid atau sudah kedaluwarsa'], 401);
        }

        $ttl = (int) env('TOKEN_TTL_SECONDS', 60);
        $expiresAt = $admin->token_created_at->copy()->addSeconds($ttl);

        if (now()->greaterThanOrEqualTo($expiresAt)) {
            $admin->token = null;
            $admin->token_created_at = null;
            $admin->save();

            Log::info('Access token kedaluwarsa', ['token' => substr($token, 0, 10)]);
            return response()->json(['message' => 'Token sudah kedaluwarsa'], 401);
        }

        return $next($request);
    }
}