<?php

namespace Domain\Goal\Actions;

use Domain\Goal\Models\FinancialGoal;

class CreateGoalAction
{
    public function execute(array $data): FinancialGoal
    {
        // Simple creation of a financial goal.
        // Complex logic like tracking contributions over time would be handled
        // by a different action that interacts with LedgerService.
        
        return FinancialGoal::create([
            'user_id' => $data['user_id'],
            'tenant_id' => $data['tenant_id'],
            'name' => $data['name'],
            'target_amount' => $data['target_amount'],
            'current_amount' => 0.00,
            'currency' => $data['currency'] ?? 'INR',
            'deadline' => $data['deadline'] ?? null,
            'is_completed' => false
        ]);
    }
}
