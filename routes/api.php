<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;

// Autentikasi API (jika pakai Sanctum)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API untuk Menu (GET)
Route::get('/menus', [MenuController::class, 'apiIndex']);       // List semua menu
Route::get('/menus/{id}', [MenuController::class, 'show']);      // Detail menu berdasarkan ID

// API untuk menyimpan Order (POST)
Route::post('/save-order', [OrderController::class, 'store']);   // Simpan order baru
