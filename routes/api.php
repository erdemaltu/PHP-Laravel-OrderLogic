<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DiscountController;

Route::apiResource('orders', OrderController::class);
Route::get('discounts/{orderId}', [DiscountController::class, 'calculate']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
