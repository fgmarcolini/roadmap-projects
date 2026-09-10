<?php

use App\Http\Controllers\ArticleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()
        ->route('articles.index');
});

Route::get('/admin', [ArticleController::class, 'admin'])
    ->name('articles.admin');
Route::resource('articles', ArticleController::class);
