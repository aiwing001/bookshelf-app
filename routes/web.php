<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\GenreController;

Route::resource('books', BookController::class)->
    only(['index', 'show']);

Route::middleware('auth')->group(function () {
    Route::resource('books', BookController::class)
        ->only(['create', 'store', 'edit', 'update', 'destroy']);
});


Route::resource('genres', GenreController::class);

Route::get('/ranking', [RankingController::class, 'index'])
    ->name('ranking.index');

Route::get('/favorites', [FavoriteController::class, 'index'])
    ->name('favorites.index');
