<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Domain\Expense\Models\ExpenseBudget;
use Domain\Expense\Models\ExpenseCategory;
use Domain\Expense\Models\Expense;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BudgetController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;
        
        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));

        $categories = ExpenseCategory::where('tenant_id', $tenantId)->get();
        
        $budgets = ExpenseBudget::where('tenant_id', $tenantId)
            ->where('month', $month)
            ->where('year', $year)
            ->get()
            ->keyBy('category_id');

        $expenses = Expense::where('user_id', $user->id)
            ->where('month', $month)
            ->where('year', $year)
            ->selectRaw('category_id, sum(amount) as total')
            ->groupBy('category_id')
            ->pluck('total', 'category_id');

        $budgetData = $categories->map(function ($cat) use ($budgets, $expenses) {
            $budget = $budgets->get($cat->id);
            $spent = $expenses->get($cat->id) ?? 0;
            $limit = $budget ? $budget->amount : 0;
            $progress = $limit > 0 ? min(100, ($spent / $limit) * 100) : ($spent > 0 ? 100 : 0);
            
            return [
                'category' => $cat,
                'budget_id' => $budget ? $budget->id : null,
                'limit' => $limit,
                'spent' => $spent,
                'remaining' => max(0, $limit - $spent),
                'progress' => $progress,
                'is_exceeded' => $spent > $limit && $limit > 0
            ];
        });

        return view('budget', compact('budgetData', 'categories', 'month', 'year'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:expense_categories,id',
            'amount' => 'required|numeric|min:1',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2000',
        ]);

        $data['tenant_id'] = Auth::user()->tenant_id;
        $data['alert_threshold'] = 80; // Default alert at 80%

        ExpenseBudget::updateOrCreate(
            [
                'tenant_id' => $data['tenant_id'],
                'category_id' => $data['category_id'],
                'month' => $data['month'],
                'year' => $data['year'],
            ],
            ['amount' => $data['amount'], 'alert_threshold' => $data['alert_threshold']]
        );

        return back()->with('success', 'Budget limit updated successfully!');
    }
}
