<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\BudgetController;
use App\Http\Controllers\Api\TransactionController;
use Illuminate\Support\Facades\Route;

// Grouper les routes API authentifiées
Route::middleware(['auth:sanctum'])->group(function () {
    // User endpoints
    Route::get('/user/profile', [UserController::class, 'profile']);
    Route::get('/user/settings', [UserController::class, 'settings']);
    Route::get('/user/freelance-split', [UserController::class, 'freelanceSplit']);

    // Budget endpoints
    Route::get('/user/budget/{year}/{month}', [BudgetController::class, 'show']);
    Route::get('/user/budget/{year}/{month}/summary', [BudgetController::class, 'summary']);
    Route::get('/user/budget-categories', [BudgetController::class, 'categories']);

    // Transaction endpoints
    Route::get('/user/transactions', [TransactionController::class, 'index']);
    Route::get('/user/transactions/summary', [TransactionController::class, 'summary']);
});
