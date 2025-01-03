<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    // User Login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        // Find the account by username
        $account = Account::where('username', $credentials['username'])->first();

        // Check credentials
        if (!$account || !Hash::check($credentials['password'], $account->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        // Create token
        $token = $account->createToken('API Token')->plainTextToken;

        return response()->json([
            'user' => $account,
            'token' => $token,
            'role' => $account->role
        ]);
    }

    // User Logout
    public function logout(Request $request)
    {
        // Revoke all tokens for the authenticated user
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    // Get Authenticated User Profile
    public function profile(Request $request)
    {
        return response()->json($request->user());
    }

    // Update User Profile
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validatedData = $request->validate([
            'nama' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:t_akun,email,' . $user->id_akun,
            'password' => 'sometimes|min:8|confirmed'
        ]);

        if (isset($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        }

        $user->update($validatedData);

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user
        ]);
    }
}
