<?php

use App\Filament\Pages\Auth\Login;
use Illuminate\Support\Facades\Route;

// Single login route for all users
Route::get('/', Login::class)->name('login');

// Filament will handle these panel routes automatically
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    // Filament admin panel will handle the routes
});

Route::middleware(['auth', 'role:dosen'])->prefix('dosen')->group(function () {
    // Filament dosen panel will handle the routes
});

Route::middleware(['auth', 'role:mahasiswa'])->prefix('mahasiswa')->group(function () {
    // Filament mahasiswa panel will handle the routes
});
