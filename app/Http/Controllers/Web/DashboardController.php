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
        $user = Auth::user();
        
        if (!$user->is_onboarded) {
            return redirect()->route('onboarding');
        }
        
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

        // Chart Data (Last 6 Months Income vs Expense)
        $chartLabels = [];
        $chartIncome = [];
        $chartExpense = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $chartLabels[] = $month->format('M Y');
            
            $inc = IncomeEntry::where('user_id', $user->id)
                ->whereMonth('income_date', $month->month)
                ->whereYear('income_date', $month->year)
                ->sum('amount');
                
            $exp = Expense::where('user_id', $user->id)
                ->whereMonth('expense_date', $month->month)
                ->whereYear('expense_date', $month->year)
                ->sum('amount');
                
            $chartIncome[] = $inc;
            $chartExpense[] = $exp;
        }

        // Goals
        $goals = \Domain\Goal\Models\FinancialGoal::where('user_id', $user->id)
            ->where('is_completed', false)
            ->take(3)
            ->get();

        // Emergency Fund
        $emergencyFund = \App\Models\EmergencyFund::where('user_id', $user->id)->first();
        $emergencyFundGap = 0;
        $emergencyFundTarget = 0;
        $emergencyFundCurrent = 0;
        if ($emergencyFund) {
            $emergencyFundTarget = $emergencyFund->monthly_expenses * $emergencyFund->recommended_months;
            $emergencyFundCurrent = $emergencyFund->current_amount;
            $emergencyFundGap = max(0, $emergencyFundTarget - $emergencyFundCurrent);
        }

        return view('dashboard', compact(
            'netWorth',
            'monthlyIncome',
            'monthlyExpense',
            'recentTransactions',
            'chartLabels',
            'chartIncome',
            'chartExpense',
            'goals',
            'emergencyFund',
            'emergencyFundTarget',
            'emergencyFundCurrent',
            'emergencyFundGap'
        ));
    }
}
