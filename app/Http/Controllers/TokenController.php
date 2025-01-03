<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\ApiToken;

class TokenController extends Controller
{
    public function rotateToken(Request $request)
    {
        $token = $request->header('Authorization');

        if (!$token || !str_starts_with($token, 'Bearer ')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $token = substr($token, 7);

        // Validasi token lama
        $apiToken = ApiToken::where('token', $token)->first();
        if (!$apiToken) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Generate token baru
        $newToken = Str::random(64);

        // Update token di database
        $apiToken->update(['token' => $newToken]);

        return response()->json(['message' => 'Token updated', 'new_token' => $newToken], 200);
    }


    public function getInitialToken(Request $request)
    {
        // Mendapatkan token pertama dari database
        $tokenRecord = ApiToken::first(); 

        if ($tokenRecord) {
            return response()->json([
                'token' => $tokenRecord->token
            ], 200);
        }

        return response()->json([
            'message' => 'No initial token found.'
        ], 404);
    }
}
