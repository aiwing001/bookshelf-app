<?php

use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\GenreController;

Route::redirect('/', '/books');

// 要認証
Route::middleware('auth')->group(function () {
    Route::resource('books', BookController::class)
        ->only(['create', 'store', 'edit', 'update', 'destroy']);

    Route::get('/favorites', [FavoriteController::class, 'index'])
        ->name('favorites.index');

    Route::post('/books/{book}/favorite', [FavoriteController::class, 'toggle'])
        ->name('favorites.toggle');

    Route::post('/books/{book}/reviews', [ReviewController::class, 'store'])
        ->name('reviews.store');

    Route::post('/reviews/{review}/like', [ReviewController::class, 'like'])
        ->name('reviews.like');

    Route::get('/reviews/{review}/edit', [ReviewController::class, 'edit'])
        ->name('reviews.edit');

    Route::put('/reviews/{review}', [ReviewController::class, 'update'])
        ->name('reviews.update');

    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])
        ->name('reviews.destroy');

    Route::resource('genres', GenreController::class);

    Route::get('/notifications', [NotificationController::class, 'index'])
        ->name('notifications.index');

    Route::patch('/notifications/{Notification}/read', [NotificationController::class, 'read'])
        ->name('notifications.read');

    Route::get('/reports', [ReportController::class, 'index'])
        ->name('reports.index');

    // TODO: 読書計画機能実装時にControllerへ置き換える
    Route::get('/reading-plans', function () {
        return '読書計画準備中';
    })->name('reading-plans.index');

    Route::get('/books/isbn/{isbn}', [BookController::class, 'searchByIsbn'])
        ->name('books.search-isbn');
});

// 認証不要
Route::resource('books', BookController::class)
    ->only(['index', 'show']);

Route::get('/ranking', [RankingController::class, 'index'])
    ->name('ranking.index');