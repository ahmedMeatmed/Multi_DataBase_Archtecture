<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\AdminController;
use Modules\Admin\Http\Controllers\Api\V1\Auth\AuthController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    // Route::apiResource('admins', AdminController::class)->names('admin');

    Route::post('admin/login',[AuthController::class, 'login']);
    Route::post('admin/logout',[AuthController::class, 'logout']);
});
