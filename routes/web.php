<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index']);
Route::get('/login', [DashboardController::class, 'login']);
Route::get('/master-product', [DashboardController::class, 'masterProduct']);
Route::get('/master-category', [DashboardController::class, 'masterCategory']);
Route::get('/master-region', [DashboardController::class, 'masterRegion']);
