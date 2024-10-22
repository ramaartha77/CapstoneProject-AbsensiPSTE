<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Account;
use Illuminate\Support\Facades\Hash;

class AccountSeeder extends Seeder
{
    public function run(): void
    {
        Account::create([
            'nama' => 'Admin User',
            'email' => 'admin@example.com',
            'username' => 'admin',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        Account::create([
            'nama' => 'Dosen User',
            'email' => 'dosen@example.com',
            'username' => 'dosen',
            'password' => Hash::make('password'),
            'role' => 'dosen',
        ]);

        Account::create([
            'nama' => 'Mahasiswa User',
            'email' => 'mahasiswa@example.com',
            'username' => 'mahasiswa',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
        ]);
    }
}
