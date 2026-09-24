<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Auth\Login;
use App\Http\Controllers\Auth\Logout;
use App\Http\Controllers\Auth\Register;
use Illuminate\Support\Facades\Route;


// 1. PUBLIC ROUTES (Accessible to everyone)
Route::view('/register', 'auth.register')->name('register');
Route::post('/register', Register::class);
Route::view('/login', 'auth.login')->name('login');
Route::post('/login', Login::class);

// 2. PROTECTED ROUTES (Accessible only to logged-in users)
Route::middleware('auth')->group(function () {
    Route::get('/', fn() => redirect()->route('articles.index'));
    Route::post('/logout', Logout::class)->name('logout');

    // USERS RESOURCE
    Route::resource('users', UserController::class);

    // ARTICLE ADMIN CUSTOM
    Route::get('/admin', [ArticleController::class, 'admin'])
        ->name('articles.admin');

    // ROLES RESOURCE
    Route::resource('roles', RoleController::class);

    // ARTICLES RESOURCE
    Route::resource('articles', ArticleController::class);
});
