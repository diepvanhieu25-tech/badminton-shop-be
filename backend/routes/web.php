<?php

use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('products', ProductController::class);
});

Route::prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('brands', BrandController::class)->except(['show']);
    });

Route::prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('category', CategoryController::class)->except(['show']);
    });

Route::prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('user', UserController::class);
    });

Route::prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
         //Route::resource('dashboard', DashboardController::class);
    });