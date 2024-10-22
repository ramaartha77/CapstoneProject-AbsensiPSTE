<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


// Group routes based on role
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    // Admin panel routes
});

Route::middleware(['auth', 'role:dosen'])->prefix('dosen')->group(function () {
    // Dosen panel routes
});

Route::middleware(['auth', 'role:mahasiswa'])->prefix('mahasiswa')->group(function () {
    // Mahasiswa panel routes
});
