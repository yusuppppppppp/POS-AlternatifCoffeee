<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Tambahkan endpoint API menu
Route::get('/menus', [MenuController::class, 'apiIndex']);
Route::get('/menus/{id}', [MenuController::class, 'show']);
