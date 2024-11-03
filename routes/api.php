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

    Route::apiResource('loans', App\Http\Controllers\Api\V1\Loan\LoanController::class)
        ->names(
            [
                'index' => 'loans.index',
                'store' => 'loans.store',
                'show' => 'loans.show',
                'update' => 'loans.update',
                'destroy' => 'loans.destroy',
            ]
        )->middleware(Authenticate::class);

    Route::post('loans/{loan}/devolution', [App\Http\Controllers\Api\V1\Loan\LoanDevolutionController::class, '__invoke'])
        ->name('loans.devolution')->middleware(Authenticate::class);

    Route::apiResource('users', App\Http\Controllers\Api\V1\User\UserController::class)
        ->names(
            [
                'index' => 'users.index',
                'store' => 'users.store',
                'show' => 'users.show',
                'update' => 'users.update',
                'destroy' => 'users.destroy',
            ]
        )->middleware(Authenticate::class);
});
