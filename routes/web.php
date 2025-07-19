<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
});

Route::get('/login',[UserController::class,'login'])->name('login');
Route::get('/signup',[UserController::class,'signup'])->name('register');
Route::post('login',[UserController::class,'logincheck'])->name('logincheck');
Route::post('signup',[UserController::class,'registercheck'])->name('registercheck');

Route::get('kasir',[UserController::class,'goKasir'])->name('kasir');
Route::get('logout',[UserController::class,'logout'])->name('logout');


