<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthenticateAdmin;
use App\Http\Controllers\Api\Auth\Admin\AdminAuthController;
use App\Http\Controllers\UsaMarry\Api\Admin\Plans\PlanController;

Route::prefix('auth/admin')->group(function () {
    Route::post('login', [AdminAuthController::class, 'login'])->name('admin.login');
    Route::post('register', [AdminAuthController::class, 'register']);

    Route::middleware(AuthenticateAdmin::class)->group(function () { // Applying admin middleware
        Route::post('logout', [AdminAuthController::class, 'logout']);
        Route::get('me', [AdminAuthController::class, 'me']);
        Route::post('/change-password', [AdminAuthController::class, 'changePassword']);
        Route::get('check-token', [AdminAuthController::class, 'checkToken']);

    });
});


Route::prefix('admin')->group(function () {

    Route::middleware(AuthenticateAdmin::class)->group(function () {

        Route::prefix('plans')->group(function () {
            Route::get('/', [PlanController::class, 'index']);  // List all plans
            Route::get('{id}', [PlanController::class, 'show']); // Get single plan by ID
            Route::post('/', [PlanController::class, 'store']);  // Create new plan
            Route::put('{id}', [PlanController::class, 'update']); // Update existing plan
            Route::delete('{id}', [PlanController::class, 'destroy']); // Delete a plan
        });


    });
});
