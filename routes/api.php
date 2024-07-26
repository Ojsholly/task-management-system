<?php

use App\Http\Controllers\API\v1\AuthController;
use App\Http\Controllers\API\v1\TaskController;
use App\Http\Controllers\API\v1\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class, 'login'])->name('auth.login');
        Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');
        Route::post('logout-all-devices', [AuthController::class, 'logoutAllDevices'])->name('auth.logout-all-devices');
    });

    Route::post('users', [UserController::class, 'store'])->name('users.store');
    Route::apiResource('users', UserController::class)->except(['index', 'store'])->middleware(['auth:sanctum']);

    Route::apiResource('tasks', TaskController::class)->middleware(['auth:sanctum']);
});
