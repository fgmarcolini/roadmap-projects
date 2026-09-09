<?php

use App\Http\Controllers\ArticleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/home');
});

Route::get('/home', [ArticleController::class, 'index']);
Route::get('/article/{article}', [ArticleController::class, 'show']);
Route::get('/admin', [ArticleController::class, 'admin']);
