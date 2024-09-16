<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CertController;
use App\Http\Controllers\EduController;
use App\Http\Controllers\ExpController;
use App\Http\Controllers\SkillController;
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

Route::prefix('cert')->middleware([Authenticate::class])->group(
    function () {
        // Matches /api/cert/{id}.
        Route::get('/{id}', [CertController::class, 'get'])
            ->middleware(Authorize::class . ':GPRS_CERT_GET');

        // Matches /api/cert.
        Route::post('/', [CertController::class, 'create'])
            ->middleware(Authorize::class . ':GPRS_CERT_CREATE');

        // Matches /api/cert/{id}.
        Route::patch('/{id}', [CertController::class, 'update'])
            ->middleware(Authorize::class . ':GPRS_CERT_UPDATE');

        // Matches /api/cert/{id}.
        Route::delete('/{id}', [CertController::class, 'suspend'])
            ->middleware(Authorize::class . ':GPRS_CERT_DELETE');
    }
);

Route::prefix('edu')->middleware([Authenticate::class])->group(
    function () {
        // Matches /api/edu/{id}.
        Route::get('/{id}', [EduController::class, 'get'])
            ->middleware(Authorize::class . ':GPRS_EDU_GET');

        // Matches /api/edu.
        Route::post('/', [EduController::class, 'create'])
            ->middleware(Authorize::class . ':GPRS_EDU_CREATE');

        // Matches /api/edu/{id}.
        Route::patch('/{id}', [EduController::class, 'update'])
            ->middleware(Authorize::class . ':GPRS_EDU_UPDATE');

        // Matches /api/edu/{id}.
        Route::delete('/{id}', [EduController::class, 'suspend'])
            ->middleware(Authorize::class . ':GPRS_EDU_DELETE');
    }
);

Route::prefix('skill')->middleware([Authenticate::class])->group(
    function () {
        // Matches /api/skill/{id}.
        Route::get('/{id}', [SkillController::class, 'get'])
            ->middleware(Authorize::class . ':GPRS_SKILL_GET');
        // Matches /api/skill.
        Route::post('/', [SkillController::class, 'create'])
            ->middleware(Authorize::class . ':GPRS_SKILL_CREATE');
        // Matches /api/skill/{id}.
        Route::patch('/{id}', [SkillController::class, 'update'])
            ->middleware(Authorize::class . ':GPRS_SKILL_UPDATE');
        // Matches /api/skill/{id}.
        Route::delete('/{id}', [SkillController::class, 'suspend'])
            ->middleware(Authorize::class . ':GPRS_SKILL_DELETE');
    }
);
