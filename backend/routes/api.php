<?php

use App\Http\Controllers\Api\V1\BrandController;
use App\Http\Controllers\Api\V1\AuthController as V1AuthController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\SocialAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/health', fn() => ['ok' => true]);

Route::prefix('v1/brands')->group(function () {
    Route::get('/', [BrandController::class, 'index']);
});

Route::prefix('v1/categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index']);
});

Route::prefix('v1/products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/{id}', [ProductController::class, 'show']);
});


// Route::middleware(['auth:sanctum'])->group(function () {
//     Route::post('/auth/logout', [AuthController::class, 'logout']);

//     Route::prefix('cart')->group(function () {
//         Route::get('/', [CartController::class, 'index']);
//     });
// });

Route::prefix('v1/auth')->group(function () {
    // POST /api/v1/auth/register
    Route::post('/register', [V1AuthController::class, 'register']);
    // POST /api/v1/auth/login
    Route::post('/login', [V1AuthController::class, 'login']);

    // Protected routes (Phải đăng nhập mới gọi được)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [V1AuthController::class, 'logout']);
        Route::post('/me', [ProfileController::class, 'update']);
    });

    Route::get('/{provider}/redirect', [SocialAuthController::class, 'redirectToProvider']);
    Route::get('/{provider}/callback', [SocialAuthController::class, 'handleProviderCallback']);
});