<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['throttle:payment-gateway'])
    ->post('/webhooks/curlec', App\Http\Controllers\Webhooks\ProcessCurlecPaymentController::class);
