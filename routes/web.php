<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('store');
})->name(name: 'store');

// Route::middleware('auth')->group(function () {
    
// });

Route::get('/profile', function () {
    return view('profile');
})->name('profile');

Route::post('/login', [UserController::class, 'login'])->name(name: 'login');

Route::post('/signup', [UserController::class, 'signup'])->name('signup');

Route::post('/logout', [UserController::class, 'logout'])->name('logout');
