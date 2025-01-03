<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Queue Connection Name
    |--------------------------------------------------------------------------
    |
    | Redis menjadi pilihan default untuk produksi karena performa yang cepat 
    | dan efisiensi memori yang baik.
    |
    */

    'default' => env('QUEUE_CONNECTION', 'redis'),

    /*
    |--------------------------------------------------------------------------
    | Queue Connections
    |--------------------------------------------------------------------------
    |
    | Hanya konfigurasi yang digunakan disimpan di sini agar lebih ringkas.
    |
    */

    'connections' => [

        // Driver Sync (untuk pengembangan lokal)
        'sync' => [
            'driver' => 'sync',
        ],

        // Driver Database (fallback jika Redis tidak tersedia)
        'database' => [
            'driver' => 'database',
            'table' => 'jobs',
            'queue' => env('DB_QUEUE', 'default'),
            'retry_after' => 90,
            'after_commit' => true,
        ],

        // Driver Redis (default untuk produksi)
        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
            'queue' => env('REDIS_QUEUE', 'default'),
            'retry_after' => env('REDIS_RETRY_AFTER', 60), // Lebih cepat untuk retry
            'block_for' => 5, // Menunggu selama 5 detik jika queue kosong
            'after_commit' => true,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Failed Queue Jobs
    |--------------------------------------------------------------------------
    |
    | Logging untuk pekerjaan yang gagal, agar mudah dianalisa dan diperbaiki.
    |
    */

    'failed' => [
        'driver' => env('QUEUE_FAILED_DRIVER', 'database-uuids'),
        'database' => env('DB_CONNECTION', 'mysql'),
        'table' => 'failed_jobs',
    ],

];
