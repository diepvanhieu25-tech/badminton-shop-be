<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\BrandController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\CartController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\PaymentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::get('/health', fn() => ['status' => 'ok', 'timestamp' => now()]);

// =========================================================================
// VERSION V1
// =========================================================================
Route::prefix('v1')->group(function () {

    // =====================================================================
    // 1. PUBLIC ROUTES (Không cần đăng nhập)
    // =====================================================================

    // --- Authentication (Guest) ---
    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');

        // Forgot Password
        Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
        Route::post('/reset-password', [AuthController::class, 'resetPassword']);

        // Social Login
        Route::get('/{provider}/redirect', [AuthController::class, 'redirectToProvider']);
        Route::get('/{provider}/callback', [AuthController::class, 'handleProviderCallback']);
    });

    // --- Catalog (Sản phẩm, Danh mục, Thương hiệu) ---
    Route::prefix('brands')->group(function () {
        Route::get('/', [BrandController::class, 'index']);
    });

    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
    });

    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/{id}', [ProductController::class, 'show']);
    });

    // --- Payment Callback (VNPay gọi vào đây nên phải để Public) ---
    Route::get('payment/vnpay/callback', [PaymentController::class, 'vnpayCallback']);


    // =====================================================================
    // 2. PROTECTED ROUTES (Yêu cầu Token - auth:sanctum)
    // =====================================================================
    Route::middleware('auth:sanctum')->group(function () {

        // --- User Profile & Auth ---
        Route::prefix('auth')->group(function () {
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::get('/me', [ProfileController::class, 'show']);
            // Dùng POST cho update profile vì thường có upload file (avatar)
            // Laravel xử lý multipart/form-data với method PUT rất tệ
            Route::post('/me', [ProfileController::class, 'update']);
        });

        // --- Cart (Giỏ hàng) ---
        Route::prefix('cart')->group(function () {
            Route::get('/', [CartController::class, 'index']);             // Xem giỏ
            Route::post('/items', [CartController::class, 'addToCart']);   // Thêm món
            Route::put('/items/{item_id}', [CartController::class, 'updateItem']); // Sửa số lượng
            Route::delete('/items/{item_id}', [CartController::class, 'removeItem']); // Xóa món
            Route::delete('/clear', [CartController::class, 'clear']);     // Xóa sạch
            Route::put('/select', [CartController::class, 'updateSelection']); // Chọn để mua
        });

        // --- Orders (Đơn hàng) ---
        Route::prefix('orders')->group(function () {
            Route::get('/', [OrderController::class, 'index']);            // Danh sách đơn
            Route::post('/', [OrderController::class, 'store']);           // Tạo đơn mới
            Route::get('/{code}', [OrderController::class, 'show']);       // Chi tiết đơn
            Route::put('/{code}/cancel', [OrderController::class, 'cancel']); // Hủy đơn

            // Payment (Tạo URL thanh toán cần user đã login)
            Route::post('/payment/vnpay/create-url', [PaymentController::class, 'createVnpayUrl']);
        });
    });
    Route::get('/payment/vnpay/callback', [PaymentController::class, 'vnpayCallback']);
});
