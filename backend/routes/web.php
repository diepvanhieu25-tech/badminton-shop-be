<?php

use Illuminate\Support\Facades\Route;
// Import các Controller Admin
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;

Route::get('/', function () {
    return view('welcome');
});

// --- NHÓM ROUTE ADMIN ---
Route::prefix('admin')->name('admin.')->group(function () {

    // 1. Route Đăng nhập (KHÔNG dùng middleware auth/admin để người lạ còn vào được)
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.submit');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // 2. Route Cần bảo vệ (Phải đăng nhập VÀ là Admin mới vào được)
    // Lưu ý: 'admin' là tên middleware bạn đã đăng ký ở bước trước (trong bootstrap/app.php)
    Route::middleware(['auth', 'admin'])->group(function () {
        
        // Dashboard
        Route::get('/', [DashboardController::class, 'index']); // Vào /admin tự chuyển dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Các Resource (Quản lý dữ liệu)
        Route::resource('products', ProductController::class);
        Route::resource('brands', BrandController::class)->except(['show']);
        Route::resource('category', CategoryController::class)->except(['show']);
        Route::resource('user', UserController::class);
        
    });
});