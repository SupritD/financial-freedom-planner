<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Domain\Income\Models\IncomeEntry;
use Domain\Expense\Models\Expense;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $user = Auth::user();

        $type = $request->query('type', 'all'); // 'all', 'income', 'expense'
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $categoryId = $request->query('category_id');
        $perPage = (int) $request->query('per_page', 25);

        $incomes = collect();
        if ($type === 'all' || $type === 'income') {
            $query = IncomeEntry::where('user_id', $user->id)->with('sourceType');
            
            if ($startDate) {
                $query->whereDate('income_date', '>=', $startDate);
            }
            if ($endDate) {
                $query->whereDate('income_date', '<=', $endDate);
            }
            if ($categoryId) {
                // For incomes, category maps to source_type_id
                $query->where('source_type_id', $categoryId);
            }

            $incomes = $query->get()->map(function ($inc) {
                return [
                    'date' => $inc->income_date->format('Y-m-d'),
                    'type' => 'income',
                    'title' => $inc->sourceType->name,
                    'amount' => $inc->amount,
                ];
            });
        }

        $expenses = collect();
        if ($type === 'all' || $type === 'expense') {
            $query = Expense::where('user_id', $user->id)->with('category');
            
            if ($startDate) {
                $query->whereDate('expense_date', '>=', $startDate);
            }
            if ($endDate) {
                $query->whereDate('expense_date', '<=', $endDate);
            }
            if ($categoryId) {
                $query->where('category_id', $categoryId);
            }

            $expenses = $query->get()->map(function ($exp) {
                return [
                    'date' => $exp->expense_date->format('Y-m-d'),
                    'type' => 'expense',
                    'title' => $exp->title . ' (' . $exp->category->name . ')',
                    'amount' => $exp->amount,
                ];
            });
        }

        $allTransactions = $incomes->concat($expenses)
            ->sortByDesc('date')
            ->values();

        $page = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
        
        $transactions = new \Illuminate\Pagination\LengthAwarePaginator(
            $allTransactions->forPage($page, $perPage),
            $allTransactions->count(),
            $perPage,
            $page,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(), 'query' => $request->query()]
        );
        
        $transactions->onEachSide(1);

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

        try {
            $action->execute($data);
        } catch (\Exception $e) {
            return back()->withErrors(['amount' => $e->getMessage()]);
        }

        return back()->with('success', 'Expense added successfully!');
    }
}
