<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\DashboardController;

use App\Http\Controllers\Web\WebAuthController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

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

    Route::get('/emergency-fund', [\App\Http\Controllers\Web\EmergencyFundController::class, 'index'])->name('emergency');
    Route::post('/emergency-fund', [\App\Http\Controllers\Web\EmergencyFundController::class, 'store'])->name('emergency.store');
    Route::post('/emergency-fund/deposit', [\App\Http\Controllers\Web\EmergencyFundController::class, 'deposit'])->name('emergency.deposit');

    // Settings & Profile
    Route::get('/settings', [\App\Http\Controllers\Web\SettingsController::class, 'index'])->name('settings');
    Route::put('/settings/profile', [\App\Http\Controllers\Web\SettingsController::class, 'updateProfile'])->name('settings.profile.update');
    Route::put('/settings/password', [\App\Http\Controllers\Web\SettingsController::class, 'updatePassword'])->name('settings.password.update');
    Route::post('/settings/2fa', [\App\Http\Controllers\Web\SettingsController::class, 'toggleTwoFactor'])->name('settings.2fa.toggle');
    Route::get('/settings/export', [\App\Http\Controllers\Web\SettingsController::class, 'downloadData'])->name('settings.export');
    Route::delete('/settings/account', [\App\Http\Controllers\Web\SettingsController::class, 'deleteAccount'])->name('settings.account.delete');
});
