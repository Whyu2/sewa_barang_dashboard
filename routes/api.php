<?php
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\RegionController;
use Illuminate\Support\Facades\Route;

Route::get('/regions', [RegionController::class, 'regions']);
Route::get('/region-paginated', [RegionController::class, 'regionPaginated']);
Route::post('/region', [RegionController::class, 'regionStore']);
Route::put('/region/{id}', [RegionController::class, 'regionUpdate']);
Route::delete('/region/{id}', [RegionController::class, 'regionDestroy']);

Route::get('/products', [ProductController::class, 'products']);
Route::get('/product-paginated', [ProductController::class, 'productPaginated']);
Route::post('/product', [ProductController::class, 'productStore']);
Route::put('/product/{id}', [ProductController::class, 'productUpdate']);
Route::delete('/product/{id}', [ProductController::class, 'productDestroy']);

Route::get('/category', [CategoryController::class, 'categories']);
Route::get('/category-paginated', [CategoryController::class, 'categoryPaginated']);
Route::post('/category', [CategoryController::class, 'categoryStore']);
Route::put('/category/{id}', [CategoryController::class, 'categoryUpdate']);
Route::delete('/category/{id}', [CategoryController::class, 'categoryDestroy']);
