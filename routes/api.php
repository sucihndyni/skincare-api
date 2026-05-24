<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\OrderDetailController;
use App\Http\Controllers\Api\StatisticController;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/

Route::post('/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| SEMUA ROUTE WAJIB TOKEN
|--------------------------------------------------------------------------
*/

Route::middleware('auth.token')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | USERS
    |--------------------------------------------------------------------------
    */

    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    /*
    |--------------------------------------------------------------------------
    | PRODUCTS
    |--------------------------------------------------------------------------
    */

    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);

    /*
    |--------------------------------------------------------------------------
    | CATEGORIES
    |--------------------------------------------------------------------------
    */

    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{id}', [CategoryController::class, 'show']);
    Route::put('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

    /*
    |--------------------------------------------------------------------------
    | ORDERS / TRANSAKSI
    |--------------------------------------------------------------------------
    */

    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::delete('/orders/{id}', [OrderController::class, 'destroy']);

    /*
    |--------------------------------------------------------------------------
    | ORDER DETAILS
    |--------------------------------------------------------------------------
    */

    Route::post('/orders/{id}/details', [OrderDetailController::class, 'store']);

    Route::delete(
        '/orders/{orderId}/details/{detailId}',
        [OrderDetailController::class, 'destroy']
    );

    /*
    |--------------------------------------------------------------------------
    | STATISTICS
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/statistics/year/{tahun}',
        [StatisticController::class, 'yearlyStatistics']
    );

    /*
    | Statistik Bulanan
    | contoh:
    | GET /api/statistics/month/2026/5
    */

    Route::get(
        '/statistics/month/{tahun}/{bulan}',
        [StatisticController::class, 'monthlyStatistics']
    );

    /*
    | Statistik Harian
    | contoh:
    | GET /api/statistics/day/2026-05-12
    */

    Route::get(
        '/statistics/day/{tanggal}',
        [StatisticController::class, 'dailyStatistics']
    );

    /*
    | Top Products
    | contoh:
    | GET /api/statistics/top-products
    */

    Route::get(
        '/statistics/top-products',
        [StatisticController::class, 'topProducts']
    );

});

/*
|--------------------------------------------------------------------------
| ERROR ENDPOINT
|--------------------------------------------------------------------------
*/

Route::any('{any?}', function () {
    return response()->json([
        'response' => false,
        'message' => 'Mau cari endpoint apa ya? 😠'
    ], 404);
})->where('any', '.*');