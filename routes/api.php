<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ShippingController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// // Route untuk fitur pengiriman (pencarian tujuan tidak perlu login)
// Route::get('/shipping/destinations', [ShippingController::class, 'searchDestination'])->name('api.shipping.destinations');

// // Route untuk kalkulasi ongkir (membutuhkan user untuk login)
// Route::post('/shipping/calculate', [ShippingController::class, 'calculate'])->name('api.shipping.calculate')->middleware('auth');