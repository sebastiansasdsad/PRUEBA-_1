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
    // Public Authentication Endpoint
    Route::post('/auth/login', [AuthController::class, 'login']);

    // Protected Auth Endpoints
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/auth/me', [AuthController::class, 'me']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);
    });

    // Dashboard analytics endpoint
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);

    // Full CRUD endpoints for categories
    Route::apiResource('categories', CategoryController::class);

    // Full CRUD endpoints for products
    Route::apiResource('products', ProductController::class);
});
