<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ShopListController;

Route::get('/', [ShopListController::class, 'index'])->name('index');

Route::post('/login', [UserController::class, 'login'])->name('login');

Route::post('/signup', [UserController::class, 'signup'])->name('signup');

Route::post('/logout', [UserController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/cart', [App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [App\Http\Controllers\CartController::class, 'store'])->name('cart.store');
    Route::put('/cart', [App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart', [App\Http\Controllers\CartController::class, 'destroy'])->name('cart.destroy');
});
