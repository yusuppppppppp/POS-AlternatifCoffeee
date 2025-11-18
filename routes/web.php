<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;

Route::get('/', fn() => view('login'));

// Auth Routes
Route::middleware(['redirect.if.authenticated'])->group(function () {
    Route::get('/login', [UserController::class, 'login'])->name('login');
    Route::get('/signup', [UserController::class, 'signup'])->name('register');
    Route::post('/login', [UserController::class, 'logincheck'])->name('logincheck');
    Route::post('/signup', [UserController::class, 'registercheck'])->name('registercheck');    
});

Route::middleware(['auth', 'prevent.back.history'])->group(function () {
    // Kasir dan Dashboard
    Route::get('/kasir', [UserController::class, 'goKasir'])->name('kasir');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/sales-report', [UserController::class, 'salesReport'])->name('sales-report');
    Route::get('/account-management', [UserController::class, 'accountManagement'])->name('account-management');
    Route::get('/change-password', [UserController::class, 'changePassword'])->name('change-password');
    Route::post('/change-password', [UserController::class, 'updatePassword'])->name('update-password');
    Route::put('/update-profile', [UserController::class, 'updateProfile'])->name('update.profile');

    // Menu Management
    Route::get('/menu-management', [MenuController::class, 'index'])->name('menu-management');
    Route::post('/menus', [MenuController::class, 'store'])->name('menus.store');
    Route::put('/menus/{id}', [MenuController::class, 'update'])->name('menus.update'); 
    Route::patch('/menus/{id}/toggle-status', [MenuController::class, 'toggleStatus'])->name('menus.toggle-status');
    Route::delete('/menus/{id}', [MenuController::class, 'destroy'])->name('menus.destroy');

    // Category Management
    Route::get('/category-management', [CategoryController::class, 'index'])->name('category-management');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Order
    Route::get('/order-list', [UserController::class, 'orderList'])->name('order-list'); // Menampilkan halaman
    Route::get('/order-list/download-pdf', [UserController::class, 'downloadOrderListPdf'])->name('order-list.download-pdf'); // Download PDF
    Route::get('/sales-report/download-pdf', [UserController::class, 'downloadSalesReportPdf'])->name('sales-report.download-pdf'); // Download Sales Report PDF
    Route::get('/order-data', [OrderController::class, 'todayOrders'])->name('order.data'); // Ambil data pesanan (HARUS JSON)
    Route::post('/save-order', [OrderController::class, 'store'])->name('save-order');

    // User CRUD API Routes
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/api/users', [UserController::class, 'getUsers'])->name('users.api.index');
    Route::get('/api/users/{id}', [UserController::class, 'getUser'])->name('users.api.show');

    // Logout
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');
});
