<?php

use Illuminate\Support\Facades\Route;
Route::get('/', function () {
    return view('welcome');
});
Route::get('/admin', fn() => view('admin.dashboard.index'));
Route::get('/admin/products', fn() => view('admin.products.index'));
Route::get('/admin/products/create', fn() => view('admin.products.create'));
Route::get('/admin/products/{id}/edit', fn() => view('admin.products.edit'));

Route::get('/admin/orders/{id}', fn() => view('admin.orders.show'));
