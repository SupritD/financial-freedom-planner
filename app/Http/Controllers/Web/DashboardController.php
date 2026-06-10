<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Domain\Income\Models\IncomeEntry;
use Domain\Expense\Models\Expense;
use Domain\SharedKernel\Models\LedgerAccount;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // For prototype purposes, auto-login Demo User if not logged in
        if (!Auth::check()) {
            Auth::loginUsingId(1);
        }

        $user = Auth::user();
        
        // Compute Net Worth (Assets - Liabilities)
        // Savings = asset, Debt = liability
        $savingsAccounts = LedgerAccount::where('user_id', $user->id)
            ->where('account_type', 'savings')
            ->sum('current_balance');
            
        $debtAccounts = LedgerAccount::where('user_id', $user->id)
            ->where('account_type', 'debt')
            ->sum('current_balance');
            
        $netWorth = $savingsAccounts - $debtAccounts;

        // Current Month Data
        $startOfMonth = Carbon::now()->startOfMonth();
        
        $monthlyIncome = IncomeEntry::where('user_id', $user->id)
            ->where('income_date', '>=', $startOfMonth)
            ->sum('amount');
            
        $monthlyExpense = Expense::where('user_id', $user->id)
            ->where('expense_date', '>=', $startOfMonth)
            ->sum('amount');

        // Recent Transactions (Combine Income and Expense, then sort)
        $incomes = IncomeEntry::where('user_id', $user->id)
            ->with('sourceType')
            ->orderBy('income_date', 'desc')
            ->take(5)
            ->get()
            ->map(function ($inc) {
                return [
                    'date' => $inc->income_date->format('Y-m-d'),
                    'type' => 'income',
                    'title' => $inc->sourceType->name,
                    'amount' => $inc->amount,
                ];
            });

        $expenses = Expense::where('user_id', $user->id)
            ->orderBy('expense_date', 'desc')
            ->take(5)
            ->get()
            ->map(function ($exp) {
                return [
                    'date' => $exp->expense_date->format('Y-m-d'),
                    'type' => 'expense',
                    'title' => $exp->title,
                    'amount' => $exp->amount,
                ];
            });

        $recentTransactions = $incomes->concat($expenses)
            ->sortByDesc('date')
            ->take(8)
            ->values();

        return view('dashboard', compact(
            'netWorth',
            'monthlyIncome',
            'monthlyExpense',
            'recentTransactions'
        ));
    }
}
