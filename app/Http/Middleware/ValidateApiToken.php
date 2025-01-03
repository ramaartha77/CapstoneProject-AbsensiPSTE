<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ApiToken;

class ValidateApiToken
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('Authorization');

        if (!$token || !str_starts_with($token, 'Bearer ')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $token = substr($token, 7); // Hilangkan "Bearer "

        // Validasi token di database
        $apiToken = ApiToken::where('token', $token)->first();
        if (!$apiToken) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return $next($request);
    }
}
