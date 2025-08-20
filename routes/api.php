<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('authors/{author}/books', [AuthorController::class, 'books'])
    ->name('authors.books');
Route::apiResource('authors', AuthorController::class);
Route::apiResource('books', BookController::class);
