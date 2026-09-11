<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\Auth\Login;
use App\Http\Controllers\Auth\Logout;
use App\Http\Controllers\Auth\Register;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('articles.index'));
Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');

// 1. PUBLIC ROUTES (Accessible to everyone)
Route::middleware('guest')->group(function () {
    Route::view('/register', 'auth.register')->name('register');
    Route::post('/register', Register::class);
    Route::view('/login', 'auth.login')->name('login');
    Route::post('/login', Login::class);
});

// 2. PROTECTED ROUTES (Accessible only to logged-in users)
Route::middleware('auth')->group(function () {
    Route::get('/admin', [ArticleController::class, 'admin'])->name('articles.admin');
    Route::post('/logout', Logout::class)->name('logout');

    // Only protect the destructive/editing actions of the resource
    Route::resource('articles', ArticleController::class)->except(['index', 'show']);
});

Route::get('/articles/{article}', [ArticleController::class, 'show'])->name('articles.show');
