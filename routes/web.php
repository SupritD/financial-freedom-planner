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
    Route::get('/register', [WebAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [WebAuthController::class, 'register']);
    
    Route::get('/forgot-password', [WebAuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [WebAuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [WebAuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [WebAuthController::class, 'resetPassword'])->name('password.store');
});

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', [WebAuthController::class, 'showVerifyEmail'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [WebAuthController::class, 'verifyEmail'])->middleware(['signed'])->name('verification.verify');
    Route::post('/email/verification-notification', [WebAuthController::class, 'sendVerificationEmail'])->middleware(['throttle:6,1'])->name('verification.send');
    Route::post('/logout', [WebAuthController::class, 'logout'])->name('logout');
    
    Route::get('/onboarding', [\App\Http\Controllers\Web\OnboardingController::class, 'show'])->name('onboarding');
    Route::post('/onboarding', [\App\Http\Controllers\Web\OnboardingController::class, 'store'])->name('onboarding.store');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/transactions', [\App\Http\Controllers\Web\TransactionController::class, 'index'])->name('transactions');
    Route::post('/transactions/income', [\App\Http\Controllers\Web\TransactionController::class, 'storeIncome'])->name('transactions.income.store');
    Route::post('/transactions/expense', [\App\Http\Controllers\Web\TransactionController::class, 'storeExpense'])->name('transactions.expense.store');
    Route::get('/goals', [\App\Http\Controllers\Web\GoalController::class, 'index'])->name('goals');
    Route::post('/goals', [\App\Http\Controllers\Web\GoalController::class, 'store'])->name('goals.store');
    Route::post('/goals/contribute', [\App\Http\Controllers\Web\GoalController::class, 'contribute'])->name('goals.contribute');
    Route::get('/budget', [\App\Http\Controllers\Web\BudgetController::class, 'index'])->name('budget');
    Route::post('/budget', [\App\Http\Controllers\Web\BudgetController::class, 'store'])->name('budget.store');
    Route::get('/savings', [\App\Http\Controllers\Web\SavingsController::class, 'index'])->name('savings');
    Route::post('/savings', [\App\Http\Controllers\Web\SavingsController::class, 'store'])->name('savings.store');
    Route::post('/savings/deposit', [\App\Http\Controllers\Web\SavingsController::class, 'deposit'])->name('savings.deposit');
    Route::post('/savings/withdraw', [\App\Http\Controllers\Web\SavingsController::class, 'withdraw'])->name('savings.withdraw');
    Route::get('/investments', [\App\Http\Controllers\Web\InvestmentController::class, 'index'])->name('investments');
    Route::post('/investments', [\App\Http\Controllers\Web\InvestmentController::class, 'store'])->name('investments.store');
    Route::post('/investments/update', [\App\Http\Controllers\Web\InvestmentController::class, 'updateValue'])->name('investments.update');
    
    Route::get('/debt', [\App\Http\Controllers\Web\DebtController::class, 'index'])->name('debt');
    Route::post('/debt', [\App\Http\Controllers\Web\DebtController::class, 'store'])->name('debt.store');
    Route::post('/debt/payment', [\App\Http\Controllers\Web\DebtController::class, 'payment'])->name('debt.payment');

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
