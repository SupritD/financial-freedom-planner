<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Domain\Income\Models\IncomeEntry;
use Domain\Expense\Models\Expense;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $incomes = IncomeEntry::where('user_id', $user->id)
            ->with('sourceType')
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
            ->with('category')
            ->get()
            ->map(function ($exp) {
                return [
                    'date' => $exp->expense_date->format('Y-m-d'),
                    'type' => 'expense',
                    'title' => $exp->title . ' (' . $exp->category->name . ')',
                    'amount' => $exp->amount,
                ];
            });

        $transactions = $incomes->concat($expenses)
            ->sortByDesc('date')
            ->values();

        return view('transactions', compact('transactions'));
    }
}
