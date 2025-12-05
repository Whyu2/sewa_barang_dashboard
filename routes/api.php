<?php
use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/products', [ProductController::class, 'products']);
Route::get('/product-paginated', [ProductController::class, 'productPaginated']);
Route::post('/products', [ProductController::class, 'productStore']);
