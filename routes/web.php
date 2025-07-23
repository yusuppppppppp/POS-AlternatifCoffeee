<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MenuController;

// Public Pages
Route::get('/', fn() => view('login'));

// Auth Routes
Route::get('/login', [UserController::class, 'login'])->name('login');
Route::get('/signup', [UserController::class, 'signup'])->name('register');
Route::post('/login', [UserController::class, 'logincheck'])->name('logincheck');
Route::post('/signup', [UserController::class, 'registercheck'])->name('registercheck');

// Protected Routes (Require Auth)
Route::middleware(['auth'])->group(function () {
    // Dashboard & Pages
    Route::get('/kasir', [UserController::class, 'goKasir'])->name('kasir');
    Route::get('/order-list', [UserController::class, 'orderList'])->name('order-list');
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/sales-report', [UserController::class, 'salesReport'])->name('sales-report');
    Route::get('/account-management', [UserController::class, 'accountManagement'])->name('account-management');

    // Menu Management Page
    Route::get('/menu-management', [MenuController::class, 'index'])->name('menu-management');

    // CRUD Menus (Fix PUT method)
    Route::post('/menus', [MenuController::class, 'store'])->name('menus.store');
    Route::put('/menus/{id}', [MenuController::class, 'update'])->name('menus.update'); 
    Route::delete('/menus/{id}', [MenuController::class, 'destroy'])->name('menus.destroy');

    // Logout
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');
});
