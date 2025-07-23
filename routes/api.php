<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ShippingController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API Routes for Shipping
Route::get('/shipping/destinations', [ShippingController::class, 'searchDestination'])->name('api.shipping.destinations');
Route::post('/shipping/calculate', [ShippingController::class, 'calculateCost'])->name('api.shipping.calculate');
