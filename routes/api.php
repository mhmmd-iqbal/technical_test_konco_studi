<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:api', 'throttle:60,1']], function () {
    // Route::post('/transactions', [AuthController::class, 'logout']);
});
