<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ShopListController;

Route::get('/', [ShopListController::class, 'index'])->name('index');

Route::get('/items', [ShopListController::class, 'items'])->name('items');

Route::post('/login', [UserController::class, 'login'])->name('login');

Route::post('/signup', [UserController::class, 'signup'])->name('signup');

Route::post('/logout', [UserController::class, 'logout'])->name('logout');
