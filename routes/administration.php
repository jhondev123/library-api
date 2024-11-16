<?php

use App\Http\Middleware\Auth\AuthenticateAdmin;
use App\Http\Controllers\Api\V1\Admin\DashboardController;

Route::prefix('admin')
    ->middleware(AuthenticateAdmin::class)
    ->group(function () {

        Route::get('ping', static function () {
            return response()->json(['message' => 'pong']);
        });

        Route::get('dashboard', [DashboardController::class, 'index'])
            ->name('admin.dashboard');


    });


