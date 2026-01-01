<?php

use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
//Route::get('/admin', fn() => view('admin.dashboard.index'));
Route::get('/admin/products', fn() => view('admin.products.index'));
Route::get('/admin/products/create', fn() => view('admin.products.create'));
Route::get('/admin/products/{id}/edit', fn() => view('admin.products.edit'));

Route::get('/admin/orders', fn() => view('admin.orders.index'));
Route::get('/admin/orders/{id}', fn() => view('admin.orders.show'));



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