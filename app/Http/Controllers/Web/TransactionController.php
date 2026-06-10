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

        $incomeSources = \Domain\Income\Models\IncomeSourceType::where('tenant_id', $user->tenant_id)->get();
        $expenseCategories = \Domain\Expense\Models\ExpenseCategory::where('tenant_id', $user->tenant_id)->get();

        return view('transactions', compact('transactions', 'incomeSources', 'expenseCategories'));
    }

    public function storeIncome(\Illuminate\Http\Request $request, \Domain\Income\Actions\RecordIncomeEntryAction $action)
    {
        $data = $request->validate([
            'source_type_id' => 'required|exists:income_source_types,id',
            'amount' => 'required|numeric|min:0.01',
            'income_date' => 'required|date',
        ]);

        $data['user_id'] = Auth::id();
        $data['tenant_id'] = Auth::user()->tenant_id;
        $data['currency'] = 'INR';

        $action->execute($data);

        return back()->with('success', 'Income added successfully!');
    }

    public function storeExpense(\Illuminate\Http\Request $request, \Domain\Expense\Actions\CreateExpenseAction $action)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:expense_categories,id',
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'expense_date' => 'required|date',
        ]);

        $data['user_id'] = Auth::id();
        $data['tenant_id'] = Auth::user()->tenant_id;
        $data['currency'] = 'INR';

        $action->execute($data);

        return back()->with('success', 'Expense added successfully!');
    }
}
