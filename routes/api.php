<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExpController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\Authorize;
use Illuminate\Support\Facades\Route;

/*
    |--------------------------------------------------------------------------
    | API Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register API routes for your application. These
    | routes are loaded by the RouteServiceProvider and all of them will
    | be assigned to the "api" middleware group. Make something great!
    |
*/

// Matches /login.
Route::post('login', [AuthController::class, 'login']);

Route::prefix('user')->middleware([Authenticate::class])->group(
    function () {
        // Matches /api/user/{id}.
        Route::get('/{id}', [UserController::class, 'get'])
            ->middleware(Authorize::class . ':GPRS_USR_GET');

        // Matches /api/user.
        Route::post('/', [UserController::class, 'create'])
            ->middleware(Authorize::class . ':GPRS_USR_CREATE');

        // Matches /api/user/{id}.
        Route::patch('/{id}', [UserController::class, 'update'])
            ->middleware(Authorize::class . ':GPRS_USR_UPDATE');

        // Matches /api/user/{id}.
        Route::delete('/{id}', [UserController::class, 'suspend'])
            ->middleware(Authorize::class . ':GPRS_USR_DELETE');
    }
);

Route::prefix('exp')->middleware([Authenticate::class])->group(
    function () {
        // Matches /api/exp/{id}.
        Route::get('/{id}', [ExpController::class, 'get'])
            ->middleware(Authorize::class . ':GPRS_EXP_GET');

        // Matches /api/exp.
        Route::post('/', [ExpController::class, 'create'])
            ->middleware(Authorize::class . ':GPRS_EXP_CREATE');

        // Matches /api/exp/{id}.
        Route::patch('/{id}', [ExpController::class, 'update'])
            ->middleware(Authorize::class . ':GPRS_EXP_UPDATE');

        // Matches /api/exp/{id}.
        Route::delete('/{id}', [ExpController::class, 'suspend'])
            ->middleware(Authorize::class . ':GPRS_EXP_DELETE');
    }
);
