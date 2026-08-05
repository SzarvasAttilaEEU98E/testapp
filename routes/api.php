<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;

Route::get('\books\search', [BookController::class, 'searchBooks']);
Route::get('\books', [BookController::class, 'getAllBooks']);
Route::get('\books\{id}', [BookController::class, 'getOneBook']);
Route::get('\books\{id}\reviews', [BookController::class, 'getBookReviews']);
Route::post('\books', [BookController::class, 'createBook']);
Route::put('\books\{id}', [BookController::class, 'updateBook']);
Route::delete('\books\{id}', [BookController::class, 'deleteBook']);