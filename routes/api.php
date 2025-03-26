<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingsController;
use App\Http\Controllers\Api\CustomersController;
use App\Http\Controllers\Api\ExpensesController;
use App\Http\Controllers\Api\PlansController;
use App\Http\Controllers\Api\WorkspacesController;
use App\Http\Controllers\Api\UsersController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});
Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('bookings')->group(function () {
        Route::get('/', [BookingsController::class, 'index']);
        Route::post('/', [BookingsController::class, 'store']);
        Route::get('/{booking}', [BookingsController::class, 'show']);
        Route::put('/{booking}', [BookingsController::class, 'update']);
        Route::post('/{booking}/confirm', [BookingsController::class, 'confirm']);
        Route::post('/{booking}/cancel', [BookingsController::class, 'cancel']);
        Route::post('/{booking}/payment', [BookingsController::class, 'addPayment']);
        Route::post('/{booking}/renew', [BookingsController::class, 'renew']);
    });
    Route::apiResource('customers', CustomersController::class);
    Route::apiResource('workspaces', WorkspacesController::class);
    Route::get('workspaces/status/available', [WorkspacesController::class, 'available']);
    Route::apiResource('plans', PlansController::class);
    Route::apiResource('expenses', ExpensesController::class);
    Route::apiResource('users', UsersController::class);
});
