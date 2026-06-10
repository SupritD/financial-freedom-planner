<?php

namespace Domain\Debt\Actions;

use Domain\Debt\Models\DebtAccount;
use Domain\SharedKernel\Services\LedgerService;
use Domain\SharedKernel\ValueObjects\Money;
use Domain\SharedKernel\ValueObjects\Currency;
use Illuminate\Support\Facades\DB;
use Domain\SharedKernel\Models\LedgerAccount;

class RecordDebtPaymentAction
{
    public function __construct(
        private LedgerService $ledgerService
    ) {}

    public function execute(array $data): DebtAccount
    {
        return DB::transaction(function () use ($data) {
            
            $debt = DebtAccount::findOrFail($data['debt_account_id']);

            // Decrease debt current balance
            $debt->current_balance -= $data['amount'];
            
            if ($debt->current_balance <= 0) {
                $debt->current_balance = 0;
                $debt->is_paid_off = true;
            }
            
            $debt->save();

            // Record Ledger Entry
            $bankAccount = LedgerAccount::firstOrCreate([
                'user_id' => $debt->user_id,
                'tenant_id' => $debt->tenant_id,
                'account_type' => 'asset',
                'name' => 'Primary Bank Account'
            ]);

            $liabilityAccount = LedgerAccount::firstOrCreate([
                'user_id' => $debt->user_id,
                'tenant_id' => $debt->tenant_id,
                'account_type' => 'debt', // Or liability
                'name' => $debt->name . ' Liability'
            ]);

            $money = Money::of($data['amount'], new Currency($debt->currency));

            // Debt payment: Credit Bank (Asset), Debit Liability Account
            $this->ledgerService->recordTransaction(
                $debt->user_id,
                $debt->tenant_id,
                $liabilityAccount->id, // Debit liability reduces it
                $bankAccount->id,      // Credit asset reduces it
                $money,
                'debt_payment',
                $debt->id
            );

            return $debt;
        });
    }
}
