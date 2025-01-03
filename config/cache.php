<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Cache Store
    |--------------------------------------------------------------------------
    |
    | Redis digunakan sebagai default driver untuk performa yang optimal
    | di lingkungan produksi. Untuk pengembangan lokal, gunakan 'file'.
    |
    */

    'default' => env('CACHE_DRIVER', 'redis'),

    /*
    |--------------------------------------------------------------------------
    | Cache Stores
    |--------------------------------------------------------------------------
    |
    | Konfigurasi driver cache yang digunakan. Fokus pada 'file' untuk lokal
    | dan 'redis' untuk produksi. Konfigurasi lain bisa dihapus jika tidak digunakan.
    |
    */

    'stores' => [

        // Driver File (untuk lokal atau pengembangan)
        'file' => [
            'driver' => 'file',
            'path' => storage_path('framework/cache/data'),
        ],

        // Driver Redis (default untuk produksi)
        'redis' => [
            'driver' => 'redis',
            'connection' => 'cache',
            'lock_connection' => 'default',
        ],

        // Driver Array (digunakan untuk testing atau sementara)
        'array' => [
            'driver' => 'array',
            'serialize' => false,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Key Prefix
    |--------------------------------------------------------------------------
    |
    | Prefix unik untuk cache key digunakan untuk menghindari konflik nama
    | cache antar aplikasi yang menggunakan server Redis atau database sama.
    |
    */

    'prefix' => env('CACHE_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_cache'),

];
