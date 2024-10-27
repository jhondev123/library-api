<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Auth\Authenticate;


Route::prefix('v1')->group(function () {

    Route::post('login', [App\Http\Controllers\Api\V1\Auth\LoginController::class, '__invoke'])
        ->name('login');

    Route::post('logout', [App\Http\Controllers\Api\V1\Auth\LogoutController::class, '__invoke'])
        ->name('logout')->middleware(Authenticate::class);


    Route::apiResource('books', App\Http\Controllers\Api\V1\Book\BookController::class)
        ->names(
        [
            'index' => 'books.index',
            'store' => 'books.store',
            'show' => 'books.show',
            'update' => 'books.update',
            'destroy' => 'books.destroy',
        ]
    )->middleware(Authenticate::class);
});
