<?php

use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\CategoryController;
use App\Http\Controllers\Api\v1\DashboardController;
use App\Http\Controllers\Api\v1\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application.
| All routes are prefixed with /api automatically.
|
*/

Route::prefix('v1')->group(function () {
    // Authentication Endpoints
    Route::post('/auth/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/auth/me', [AuthController::class, 'me']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);
    });

    // Public / Client Accessible Endpoints
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{category}', [CategoryController::class, 'show']);
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{product}', [ProductController::class, 'show']);

    // Admin Protected Endpoints (Client role gets 403 Forbidden)
    Route::middleware('admin')->group(function () {
        Route::post('/categories', [CategoryController::class, 'store']);
        Route::put('/categories/{category}', [CategoryController::class, 'update']);
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);

        Route::post('/products', [ProductController::class, 'store']);
        Route::put('/products/{product}', [ProductController::class, 'update']);
        Route::delete('/products/{product}', [ProductController::class, 'destroy']);
    });
});
