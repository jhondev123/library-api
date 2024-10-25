<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::prefix('v1')->group(function () {

    Route::post('login', [App\Http\Controllers\Api\V1\Auth\LoginController::class, '__invoke'])
        ->name('login');


    Route::apiResource('books', App\Http\Controllers\Api\V1\Books\BookController::class)
        ->names(
        [
            'index' => 'books.index',
            'store' => 'books.store',
            'show' => 'books.show',
            'update' => 'books.update',
            'destroy' => 'books.destroy',
        ]
    );
});
