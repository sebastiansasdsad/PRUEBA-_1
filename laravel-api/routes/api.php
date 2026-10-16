<?php

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
    // Dashboard analytics endpoint
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);

    // Full CRUD endpoints for categories
    Route::apiResource('categories', CategoryController::class);

    // Full CRUD endpoints for products
    Route::apiResource('products', ProductController::class);
});
