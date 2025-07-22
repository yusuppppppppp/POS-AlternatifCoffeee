<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MenuController;

Route::get('/', fn() => view('login'));

// Auth
Route::get('/login', [UserController::class, 'login'])->name('login');
Route::get('/signup', [UserController::class, 'signup'])->name('register');
Route::post('/login', [UserController::class, 'logincheck'])->name('logincheck');
Route::post('/signup', [UserController::class, 'registercheck'])->name('registercheck');

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/kasir', [UserController::class, 'goKasir'])->name('kasir');
    Route::get('/order-list', [UserController::class, 'orderList'])->name('order-list');
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/sales-report', [UserController::class, 'salesReport'])->name('sales-report');
    Route::get('/account-management', [UserController::class, 'accountManagement'])->name('account-management');

    // Menu Management View
    Route::get('/menu-management', [MenuController::class, 'index'])->name('menu-management');

    // CRUD Menus
    Route::post('/menus', [MenuController::class, 'store'])->name('menus.store');
    Route::post('/menus/{id}', [MenuController::class, 'update'])->name('menus.update');
    Route::delete('/menus/{id}', [MenuController::class, 'destroy'])->name('menus.destroy');

    // Logout
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
});
