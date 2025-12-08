<?php
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use Illuminate\Support\Facades\Route;

Route::get('/products', [ProductController::class, 'products']);
Route::get('/product-paginated', [ProductController::class, 'productPaginated']);
Route::post('/products', [ProductController::class, 'productStore']);


Route::get('/category', [CategoryController::class, 'categories']);
Route::get('/category-paginated', [CategoryController::class, 'categoryPaginated']);
Route::post('/category', [CategoryController::class, 'categoryStore']);
Route::delete('/category/{id}', [CategoryController::class, 'categoryDestroy']);
