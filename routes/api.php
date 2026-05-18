<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\OrderDetailController;
use App\Http\Controllers\Api\StatisticController;

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

Route::get('/order-details', [OrderDetailController::class, 'index']);
Route::get('/order-details/{id}', [OrderDetailController::class, 'show']);
Route::post('/order-details', [OrderDetailController::class, 'store']);
Route::delete('/order-details', [OrderDetailController::class, 'destroy']);

Route::get(
    '/statistics/monthly-transactions',
    [StatisticController::class, 'monthlyTransactions']
);
Route::get(
    '/statistics/yearly-transactions',
    [StatisticController::class, 'yearlyTransactions']
);
Route::get(
    '/statistics/total-income',
    [StatisticController::class, 'totalIncome']
);
Route::get(
    '/statistics/top-products',
    [StatisticController::class, 'topProducts']
);

Route::any('{any?}', function () {
    return response()->json([
        'response' => false,
        'message' => 'Mau cari endpoint apa ya?😠'
    ], 404);
})->where('any', '.*');