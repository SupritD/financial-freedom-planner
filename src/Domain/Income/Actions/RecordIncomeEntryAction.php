<?php

namespace Domain\Income\Actions;

use Domain\Income\Models\IncomeEntry;
use Domain\SharedKernel\Services\LedgerService;
use Domain\SharedKernel\ValueObjects\Money;
use Domain\SharedKernel\ValueObjects\Currency;
use Illuminate\Support\Facades\DB;
use Domain\SharedKernel\Models\LedgerAccount;

class RecordIncomeEntryAction
{
    public function __construct(
        private LedgerService $ledgerService
    ) {}

    public function execute(array $data): IncomeEntry
    {
        return DB::transaction(function () use ($data) {
            
            $incomeEntry = IncomeEntry::create([
                'user_id' => $data['user_id'],
                'tenant_id' => $data['tenant_id'],
                'source_type_id' => $data['source_type_id'],
                'amount' => $data['amount'],
                'currency' => $data['currency'] ?? 'INR',
                'base_currency_amount' => $data['base_currency_amount'] ?? $data['amount'],
                'exchange_rate' => $data['exchange_rate'] ?? 1.0,
                'income_date' => $data['income_date'],
                'financial_year' => $this->calculateFinancialYear($data['income_date'])
            ]);

            // For double entry, we need an Income (Credit) account and an Asset (Debit) account
            // In a real system, these would be looked up or created dynamically
            $incomeAccount = LedgerAccount::firstOrCreate([
                'user_id' => $data['user_id'],
                'tenant_id' => $data['tenant_id'],
                'account_type' => 'income',
                'name' => 'Salary Pool'
            ]);

            $bankAccount = LedgerAccount::firstOrCreate([
                'user_id' => $data['user_id'],
                'tenant_id' => $data['tenant_id'],
                'account_type' => 'savings',
                'name' => 'Primary Bank Account'
            ]);

            $money = Money::of($incomeEntry->amount, new Currency($incomeEntry->currency));

            // Record the movement of money via Ledger (Debit Bank, Credit Income)
            $this->ledgerService->recordTransaction(
                $data['user_id'],
                $data['tenant_id'],
                $bankAccount->id,
                $incomeAccount->id,
                $money,
                'income_entry',
                $incomeEntry->id
            );

            return $incomeEntry;
        });
    }

    private function calculateFinancialYear(string $date): string
    {
        $time = strtotime($date);
        $month = (int)date('m', $time);
        $year = (int)date('Y', $time);

        if ($month < 4) {
            return ($year - 1) . '-' . substr($year, -2);
        }
        
        return $year . '-' . substr($year + 1, -2);
    }
}
