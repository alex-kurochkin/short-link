<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ShortLinkController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Аутентификация
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Главная страница
Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/admin');
    }
    return redirect('/login');
});

// Редирект короткой ссылки (должен быть последним!)
Route::get('/{code}', [ShortLinkController::class, 'redirect'])
    ->where('code', '[A-Za-z0-9]{' . config('short-links.code_length') . '}')
    ->name('short-link.redirect');
