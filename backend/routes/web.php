<?php

use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/admin', fn() => view('admin.dashboard.index'));

Route::prefix('admin/products')
    ->name('admin.products.')
    ->group(function () {

        Route::get('/', [ProductController::class, 'index'])->name('index');

        Route::get('create', [ProductController::class, 'create'])->name('create');
        Route::post('/', [ProductController::class, 'store'])->name('store');

        Route::get('{product}', [ProductController::class, 'detail'])->name('detail');

        Route::get('{product}/edit', [ProductController::class, 'edit'])->name('edit');
        Route::put('{product}', [ProductController::class, 'update'])->name('update');

        Route::delete('{product}', [ProductController::class, 'destroy'])->name('destroy');
    });

Route::prefix('admin/orders')->name('admin.orders.')->group(function () {
    Route::get('/', [OrderController::class, 'index'])->name('index');
    Route::get('{order}', [OrderController::class, 'show'])->name('show');  
});

Route::prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('brands', BrandController::class)->except(['show']);
    });
