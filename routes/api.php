<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PaymentController;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:api', 'throttle:60,1']], function () {
    Route::resource('/transaction', PaymentController::class)->only(['store', 'update', 'index']);
    Route::get('/transaction/summary', [PaymentController::class, 'transactionSummary']);
});
