<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ApiTokensSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Contoh membuat beberapa token
        DB::table('api_tokens')->insert([
            'id_alat_absen' => '24:DC:C3:45:82:F8',
            'token' => Str::random(64),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('api_tokens')->insert([
            'id_alat_absen' => '24:DC:C3:45:B5:08',
            'token' => Str::random(64),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Tambahkan sebanyak yang diinginkan
    }
}
