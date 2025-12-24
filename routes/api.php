<?php
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\RegionController;
use App\Http\Controllers\Api\RentTransactionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;



Route::middleware('auth:sanctum')->group(function () {
    Route::get('/regions', [RegionController::class, 'regions']);
    Route::get('/category', [CategoryController::class, 'categories']);
    Route::get('/category-paginated', [CategoryController::class, 'categoryPaginated']);
    Route::post('/category', [CategoryController::class, 'categoryStore']);
    Route::put('/category/{id}', [CategoryController::class, 'categoryUpdate']);
    Route::delete('/category/{id}', [CategoryController::class, 'categoryDestroy']);

    Route::get('/rent-transactions', [RentTransactionController::class, 'rentTransactions']);
    Route::get('/rent-transactions-paginated', [RentTransactionController::class, 'rentTransactionPaginated']);
    Route::post('/rent-transaction', [RentTransactionController::class, 'rentTransactionStore']);
    Route::put('/rent-transaction/{id}', [RentTransactionController::class, 'rentTransactionUpdate']);
    Route::delete('/rent-transaction/{id}', [RentTransactionController::class, 'rentTransactionDestroy']);


    Route::get('/region-paginated', [RegionController::class, 'regionPaginated']);
    Route::post('/region', [RegionController::class, 'regionStore']);
    Route::put('/region/{id}', [RegionController::class, 'regionUpdate']);
    Route::delete('/region/{id}', [RegionController::class, 'regionDestroy']);

    Route::get('/products', [ProductController::class, 'products']);
    Route::get('/product-paginated', [ProductController::class, 'productPaginated']);
    Route::post('/product', [ProductController::class, 'productStore']);
    Route::put('/product/{id}', [ProductController::class, 'productUpdate']);
    Route::delete('/product/{id}', [ProductController::class, 'productDestroy']);
    Route::get('/product-by-qr', [ProductController::class, 'productFindByQrCode']);
});



Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    // auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
});
