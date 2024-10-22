<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'username' => 'required|unique:accounts',
            'password' => 'required|min:8',
            // Add other validation rules as needed
        ]);

        $account = new Account();
        $account->username = $validatedData['username'];
        $account->password = Hash::make($validatedData['password']);
        // Set other fields...
        $account->save();

        // Return a response or redirect
    }
}
