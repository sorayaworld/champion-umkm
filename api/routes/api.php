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

    Route::prefix('business')->middleware('role:borrower')->group(function () {
        Route::get('/', [UmkmProfileController::class, 'show']);
        Route::post('/', [UmkmProfileController::class, 'store']);
        Route::put('/', [UmkmProfileController::class, 'update']);
    });

    Route::prefix('applications')->group(function () {
        Route::get('/', [LoanApplicationController::class, 'index'])->middleware('role:borrower,officer,manager,admin');
        Route::post('/', [LoanApplicationController::class, 'store'])->middleware('role:borrower');
        Route::get('/{id}', [LoanApplicationController::class, 'show'])->middleware('role:borrower,officer,manager,admin');
        Route::put('/{id}/review', [LoanApplicationController::class, 'review'])->middleware('role:officer');
        Route::put('/{id}/approve', [LoanApplicationController::class, 'approve'])->middleware('role:manager');
        Route::put('/{id}/reject', [LoanApplicationController::class, 'reject'])->middleware('role:manager');
    });
});