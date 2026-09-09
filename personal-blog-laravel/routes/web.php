<?php

use App\Http\Controllers\ArticleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/home');
});

Route::get('/home', [ArticleController::class, 'index']);
Route::get('/article/{article}', [ArticleController::class, 'show']);
Route::get('/admin', [ArticleController::class, 'admin']);
Route::get('/new', [ArticleController::class, 'create']);
Route::post('/article', [ArticleController::class, 'store']);
Route::get('/edit/{article}', [ArticleController::class, 'edit']);
Route::put('/update/{article}', [ArticleController::class, 'update']);
Route::delete('/delete/{article}', [ArticleController::class, 'destroy']);
