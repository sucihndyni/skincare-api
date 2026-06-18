<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\OrderDetailController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\StatisticController;
use App\Http\Controllers\Api\UserController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/refresh', [AuthController::class, 'refresh']);

Route::middleware('auth.token')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);

    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{id}', [CategoryController::class, 'show']);
    Route::put('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::delete('/orders/{id}', [OrderController::class, 'destroy']);

    Route::post('/orders/{id}/details', [OrderDetailController::class, 'store']);
    Route::delete('/orders/{orderId}/details/{detailId}', [OrderDetailController::class, 'destroy']);

    Route::get('/statistics', [StatisticController::class, 'index']);
    Route::get('/statistics/year/{tahun}', [StatisticController::class, 'yearlyStatistics']);
    Route::get('/statistics/month/{tahun}/{bulan}', [StatisticController::class, 'monthlyStatistics']);
    Route::get('/statistics/day/{tanggal}', [StatisticController::class, 'dailyStatistics']);
    Route::get('/statistics/top-products', [StatisticController::class, 'topProducts']);
});

Route::any('{any}', function () {
    return response()->json([
        'response' => false,
        'message' => 'Endpoint tidak ditemukan'
    ], 404);
})->where('any', '.*');
