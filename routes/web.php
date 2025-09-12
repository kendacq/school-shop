<?php

use App\Http\Controllers\ShopListController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', [ShopListController::class, 'index'])->name('shop');

Route::post('/login', [UserController::class, 'login'])->name(name: 'login');

Route::post('/signup', [UserController::class, 'signup'])->name('signup');

Route::post('/logout', [UserController::class, 'logout'])->name('logout');