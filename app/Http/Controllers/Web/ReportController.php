<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Domain\Income\Models\IncomeEntry;
use Domain\Expense\Models\Expense;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $period = $request->query('period', '6_months');
        $monthsToFetch = match ($period) {
            '3_months' => 3,
            '6_months' => 6,
            '12_months' => 12,
            default => 6,
        };

        // Chart Data (Income vs Expense over time)
        $chartLabels = [];
        $chartIncome = [];
        $chartExpense = [];
        
        $totalIncome = 0;
        $totalExpense = 0;

        for ($i = $monthsToFetch - 1; $i >= 0; $i--) {
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
            
            $totalIncome += $inc;
            $totalExpense += $exp;
        }

        // Expense by Category (for Pie Chart)
        $startDate = Carbon::now()->subMonths($monthsToFetch - 1)->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        $expensesByCategory = Expense::where('user_id', $user->id)
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->with('category')
            ->get()
            ->groupBy('category_id');

        $pieLabels = [];
        $pieData = [];
        
        foreach ($expensesByCategory as $catId => $expenses) {
            $catName = $expenses->first()->category->name ?? 'Uncategorized';
            $pieLabels[] = $catName;
            $pieData[] = $expenses->sum('amount');
        }

        $savingsRate = $totalIncome > 0 ? round((($totalIncome - $totalExpense) / $totalIncome) * 100, 1) : 0;

        return view('reports', compact(
            'period',
            'chartLabels',
            'chartIncome',
            'chartExpense',
            'totalIncome',
            'totalExpense',
            'savingsRate',
            'pieLabels',
            'pieData'
        ));
    }

    public function exportCsv(Request $request)
    {
        $user = Auth::user();
        
        $incomes = IncomeEntry::where('user_id', $user->id)
            ->with('sourceType')
            ->get()
            ->map(function ($inc) {
                return [
                    'Date' => $inc->income_date->format('Y-m-d'),
                    'Type' => 'Income',
                    'Category/Source' => $inc->sourceType->name,
                    'Amount' => $inc->amount,
                ];
            });

        $expenses = Expense::where('user_id', $user->id)
            ->with('category')
            ->get()
            ->map(function ($exp) {
                return [
                    'Date' => $exp->expense_date->format('Y-m-d'),
                    'Type' => 'Expense',
                    'Category/Source' => $exp->category->name ?? 'Uncategorized',
                    'Amount' => $exp->amount,
                ];
            });

        $transactions = $incomes->concat($expenses)->sortByDesc('Date');

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=financial_report.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('Date', 'Type', 'Category/Source', 'Amount');

        $callback = function() use($transactions, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($transactions as $transaction) {
                fputcsv($file, array($transaction['Date'], $transaction['Type'], $transaction['Category/Source'], $transaction['Amount']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
