<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;

Route::prefix('v1')->group(function () {
    
    // Public Auth Routes
    Route::post('/auth/register', [RegisterController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);

    // Protected Auth Routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
        
        // Future endpoints: Income, Expense, Goals etc.
    });
    
});
