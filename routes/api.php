<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Auth\Authenticate;

use App\Http\Controllers\Api\V1\User\UserController;

use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\LogoutController;
use App\Http\Controllers\Api\V1\Book\BookController;
use App\Http\Controllers\Api\V1\Loan\LoanController;
use App\Http\Controllers\Api\V1\Loan\LoanDevolutionController;
Route::prefix('v1')->group(function () {

    Route::get('/ping', static function () {
        return response()->json(['message' => 'pong']);
    });


    Route::post('register', [UserController::class, 'store'])
        ->name('register');

    Route::post('login', [LoginController::class, '__invoke'])
        ->name('login');

    Route::group(['middleware' => Authenticate::class], static function () {

        Route::post('logout', [LogoutController::class, '__invoke'])
            ->name('logout');

        Route::apiResource('books', BookController::class)
            ->names(
                [
                    'index' => 'books.index',
                    'store' => 'books.store',
                    'show' => 'books.show',
                    'update' => 'books.update',
                    'destroy' => 'books.destroy',
                ]
            );

        Route::apiResource('loans', LoanController::class)
            ->names(
                [
                    'index' => 'loans.index',
                    'store' => 'loans.store',
                    'show' => 'loans.show',
                    'update' => 'loans.update',
                    'destroy' => 'loans.destroy',
                ]
            );

        Route::post('loans/{loan}/devolution', [LoanDevolutionController::class, '__invoke'])
            ->name('loans.devolution');

        Route::prefix('users')->group(function () {
            Route::get('/', [UserController::class, 'index'])
                ->name('users.index');

            Route::get('/{user}', [UserController::class, 'show'])
                ->name('users.show');

            Route::put('/{user}', [UserController::class, 'update'])
                ->name('users.update');

            Route::put('/{user}/password', [UserController::class, 'updatePassword'])
                ->name('users.password.update');

            Route::delete('/{user}', [UserController::class, 'destroy'])
                ->name('users.destroy');
        });


        require_once __DIR__ . '/administration.php';


    });


});

