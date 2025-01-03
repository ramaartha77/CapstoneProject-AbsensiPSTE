<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AbsensiController;

use App\Http\Controllers\RegisterUIDController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TokenController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
});



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/absensi', [AbsensiController::class, 'store']);
Route::post('/store-uid', [RegisterUIDController::class, 'storeUID']);
Route::middleware('validate_token')->post('/absensi', [AbsensiController::class, 'store']);
Route::middleware('validate_token')->post('/rotate-token', [TokenController::class, 'rotateToken']);
Route::post('/get-initial-token', [TokenController::class, 'getInitialToken']);
