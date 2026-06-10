<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\DashboardController;

use App\Http\Controllers\Web\WebAuthController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [WebAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [WebAuthController::class, 'login']);
});

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [WebAuthController::class, 'logout'])->name('logout');
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/transactions', [\App\Http\Controllers\Web\TransactionController::class, 'index'])->name('transactions');
    Route::post('/transactions/income', [\App\Http\Controllers\Web\TransactionController::class, 'storeIncome'])->name('transactions.income.store');
    Route::post('/transactions/expense', [\App\Http\Controllers\Web\TransactionController::class, 'storeExpense'])->name('transactions.expense.store');
    Route::get('/goals', [\App\Http\Controllers\Web\GoalController::class, 'index'])->name('goals');
    Route::post('/goals', [\App\Http\Controllers\Web\GoalController::class, 'store'])->name('goals.store');
    
    Route::get('/debt', [\App\Http\Controllers\Web\DebtController::class, 'index'])->name('debt');
    Route::post('/debt', [\App\Http\Controllers\Web\DebtController::class, 'store'])->name('debt.store');
});
