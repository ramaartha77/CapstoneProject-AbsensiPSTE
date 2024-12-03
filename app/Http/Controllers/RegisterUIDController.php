<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use App\Models\Akun;
use Illuminate\Support\Facades\Validator;
use Exception;

class RegisterUIDController extends Controller
{
    // Fungsi untuk menyimpan UID ke file
    public function storeUID(Request $request)
    {
        // Validasi input UID
        $validator = Validator::make($request->all(), [
            'uid' => 'required|string|max:45'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422); // 422 Unprocessable Entity
        }

        $uid = $request->input('uid');

        // Menyimpan UID ke file
        try {
            // Simpan UID ke dalam file
            file_put_contents(storage_path('app/uid.txt'), $uid);

            return response()->json(['success' => true, 'message' => 'UID Tersimpan']);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal Simpan',
                'error' => $e->getMessage()
            ], 500); // 500 Internal Server Error
        }
    }
}
