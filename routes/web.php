<?php

use App\Http\Controllers\DefaultController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DefaultController::class, 'index'])->name('index.books');
Route::get('/top10', [DefaultController::class, 'topIndex'])->name('index.top');
Route::get('/rating', [DefaultController::class, 'ratingIndex'])->name('index.rating');
Route::post('/data', [DefaultController::class, 'dataBooks'])->name('books.data');
Route::get('/authors', [DefaultController::class, 'listAuthors'])->name('authors.list');
Route::get('/books', [DefaultController::class, 'listBooks'])->name('books.list');
Route::post('/rating/submit', [DefaultController::class, 'ratingSubmit'])->name('rating.submit');