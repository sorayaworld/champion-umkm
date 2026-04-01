<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\LoanApplicationController;
use App\Http\Controllers\api\UmkmProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::prefix('profile')->middleware('role:borrower')->group(function () {
        Route::get('/', [UmkmProfileController::class, 'show']);
        Route::post('/', [UmkmProfileController::class, 'store']);
        Route::put('/', [UmkmProfileController::class, 'update']);
    });

    Route::prefix('applications')->middleware('role:borrower')->group(function () {
        Route::get('/', [LoanApplicationController::class, 'index']);
        Route::post('/', [LoanApplicationController::class, 'store']);
        Route::get('/{id}', [LoanApplicationController::class, 'show']);
    });
});