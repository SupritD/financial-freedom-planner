<?php

namespace Domain\Expense\Services;

use Domain\Expense\Models\Expense;
use Domain\Expense\Models\ExpenseBudget;

class BudgetCheckService
{
    public function verifyLimit(int $userId, string $tenantId, int $categoryId, float $amount, int $month, int $year): array
    {
        $budget = ExpenseBudget::where('tenant_id', $tenantId)
            ->where('category_id', $categoryId)
            ->where('month', $month)
            ->where('year', $year)
            ->first();

        if (!$budget) {
            return ['status' => 'ok', 'message' => 'No budget set for this category.'];
        }

        $currentExpenses = Expense::where('user_id', $userId)
            ->where('category_id', $categoryId)
            ->where('month', $month)
            ->where('year', $year)
            ->sum('amount');

        $projectedTotal = $currentExpenses + $amount;

        if ($projectedTotal > $budget->amount) {
            return [
                'status' => 'exceeded', 
                'message' => 'This expense exceeds your budget limit of ₹' . number_format($budget->amount, 2) . ' for this category.'
            ];
        }

        if ($budget->alert_threshold > 0 && $projectedTotal >= ($budget->amount * ($budget->alert_threshold / 100))) {
            return [
                'status' => 'warning', 
                'message' => 'Warning: This expense puts you over the ' . $budget->alert_threshold . '% threshold of your budget.'
            ];
        }

        return ['status' => 'ok', 'message' => 'Within budget limits.'];
    }
}
