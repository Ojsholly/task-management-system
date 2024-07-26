<?php

use App\Http\Controllers\API\v1\AuthController;
use App\Http\Controllers\API\v1\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('logout-all-devices', [AuthController::class, 'logoutAllDevices']);
    });

    Route::post('users', [UserController::class, 'store']);
    Route::apiResource('users', UserController::class)->except(['index', 'store'])->middleware(['auth:sanctum']);
});
