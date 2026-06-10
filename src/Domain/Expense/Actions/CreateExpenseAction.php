<?php

namespace Domain\Expense\Actions;

use Domain\Expense\Models\Expense;
use Domain\SharedKernel\Services\LedgerService;
use Domain\SharedKernel\ValueObjects\Money;
use Domain\SharedKernel\ValueObjects\Currency;
use Illuminate\Support\Facades\DB;
use Domain\SharedKernel\Models\LedgerAccount;

class CreateExpenseAction
{
    public function __construct(
        private LedgerService $ledgerService,
        private \Domain\Expense\Services\BudgetCheckService $budgetCheckService
    ) {}

    public function execute(array $data): Expense
    {
        return DB::transaction(function () use ($data) {
            
            $expenseDate = strtotime($data['expense_date']);
            $month = (int)date('m', $expenseDate);
            $year = (int)date('Y', $expenseDate);

            // Check Budget Limits
            $budgetCheck = $this->budgetCheckService->verifyLimit(
                $data['user_id'], 
                $data['tenant_id'], 
                $data['category_id'], 
                $data['amount'], 
                $month, 
                $year
            );

            if ($budgetCheck['status'] === 'exceeded') {
                throw new \Exception($budgetCheck['message']);
            }

            $expense = Expense::create([
                'user_id' => $data['user_id'],
                'tenant_id' => $data['tenant_id'],
                'category_id' => $data['category_id'],
                'title' => $data['title'],
                'amount' => $data['amount'],
                'currency' => $data['currency'] ?? 'INR',
                'base_currency_amount' => $data['base_currency_amount'] ?? $data['amount'],
                'exchange_rate' => $data['exchange_rate'] ?? 1.0,
                'expense_date' => $data['expense_date'],
                'month' => $month,
                'year' => $year
            ]);

            // For double entry: Credit the Asset (Bank), Debit the Expense account
            $expenseAccount = LedgerAccount::firstOrCreate([
                'user_id' => $data['user_id'],
                'tenant_id' => $data['tenant_id'],
                'account_type' => 'expense',
                'name' => 'General Expenses'
            ]);

            $bankAccount = LedgerAccount::firstOrCreate([
                'user_id' => $data['user_id'],
                'tenant_id' => $data['tenant_id'],
                'account_type' => 'savings',
                'name' => 'Primary Bank Account'
            ]);

            // The raw amount before encryption is needed for the Ledger
            $money = Money::of($data['amount'], new Currency($expense->currency));

            $this->ledgerService->recordTransaction(
                $data['user_id'],
                $data['tenant_id'],
                $expenseAccount->id, // Debit the expense account
                $bankAccount->id,    // Credit the bank account
                $money,
                'expense',
                $expense->id
            );

            return $expense;
        });
    }
}
