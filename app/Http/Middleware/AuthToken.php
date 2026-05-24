<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Admin;

class AuthToken
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'message' => 'Token nya mana ya?'
            ], 401);
        }

        $admin = Admin::where('token', $token)->first();

        if (!$admin) {
            return response()->json([
                'message' => 'Token tidak valid'
            ], 401);
        }

        return $next($request);
    }
}