<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {
    // Accounts
    Route::apiResource('accounts', AccountController::class);
    Route::get('accounts/total-balance', [AccountController::class, 'getTotalBalance']);

    // Categories
    Route::apiResource('categories', CategoryController::class);
    Route::get('categories/type', [CategoryController::class, 'getByType']);

    // Transactions
    Route::apiResource('transactions', TransactionController::class);
    Route::get('transactions/revenue/total', [TransactionController::class, 'getTotalRevenue']);
    Route::get('transactions/expense/total', [TransactionController::class, 'getTotalExpense']);
    Route::get('transactions/profit', [TransactionController::class, 'getProfit']);

    // Reports
    Route::prefix('reports')->group(function () {
        Route::get('daily', [ReportController::class, 'daily']);
        Route::get('monthly', [ReportController::class, 'monthly']);
        Route::get('yearly', [ReportController::class, 'yearly']);
        Route::get('date-range', [ReportController::class, 'dateRange']);
    });
});
