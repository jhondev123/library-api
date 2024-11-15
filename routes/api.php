<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Auth\Authenticate;


Route::prefix('v1')->group(function () {

    Route::get('/ping', function () {
        return response()->json(['message' => 'pong']);
    });


    Route::post('register', [App\Http\Controllers\Api\V1\User\UserController::class, 'store'])
        ->name('register');

    Route::post('login', [App\Http\Controllers\Api\V1\Auth\LoginController::class, '__invoke'])
        ->name('login');

    Route::group(['middleware' => Authenticate::class], function () {

        Route::post('logout', [App\Http\Controllers\Api\V1\Auth\LogoutController::class, '__invoke'])
            ->name('logout');

        Route::apiResource('books', App\Http\Controllers\Api\V1\Book\BookController::class)
            ->names(
                [
                    'index' => 'books.index',
                    'store' => 'books.store',
                    'show' => 'books.show',
                    'update' => 'books.update',
                    'destroy' => 'books.destroy',
                ]
            );

        Route::apiResource('loans', App\Http\Controllers\Api\V1\Loan\LoanController::class)
            ->names(
                [
                    'index' => 'loans.index',
                    'store' => 'loans.store',
                    'show' => 'loans.show',
                    'update' => 'loans.update',
                    'destroy' => 'loans.destroy',
                ]
            );

        Route::post('loans/{loan}/devolution', [App\Http\Controllers\Api\V1\Loan\LoanDevolutionController::class, '__invoke'])
            ->name('loans.devolution');

        Route::prefix('users')->group(function () {
            Route::get('/', [App\Http\Controllers\Api\V1\User\UserController::class, 'index'])
                ->name('users.index');

            Route::get('/{user}', [App\Http\Controllers\Api\V1\User\UserController::class, 'show'])
                ->name('users.show');

            Route::put('/{user}', [App\Http\Controllers\Api\V1\User\UserController::class, 'update'])
                ->name('users.update');

            Route::put('/{user}/password', [App\Http\Controllers\Api\V1\User\UserController::class, 'updatePassword'])
                ->name('users.password.update');

            Route::delete('/{user}', [App\Http\Controllers\Api\V1\User\UserController::class, 'destroy'])
                ->name('users.destroy');
        });

    });


});

