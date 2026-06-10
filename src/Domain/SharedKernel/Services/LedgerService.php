<?php

namespace Domain\SharedKernel\Services;

use Domain\SharedKernel\Contracts\LedgerRepositoryInterface;
use Domain\SharedKernel\ValueObjects\Money;
use Illuminate\Support\Facades\DB;
use Domain\SharedKernel\Exceptions\DomainException;

class LedgerService
{
    public function __construct(
        private LedgerRepositoryInterface $ledgerRepository,
        private MoneyCalculatorService $calculator
    ) {}

    public function recordTransaction(
        string $userId,
        string $tenantId,
        string $debitAccountId,
        string $creditAccountId,
        Money $amount,
        string $transactionRefType,
        string $transactionRefId
    ): void {
        if ($amount->isZero() || $amount->isNegative()) {
            throw new DomainException("Transaction amount must be positive.");
        }

        DB::transaction(function () use ($userId, $tenantId, $debitAccountId, $creditAccountId, $amount, $transactionRefType, $transactionRefId) {
            // Fetch accounts to get their real types
            $debitAccount = \Domain\SharedKernel\Models\LedgerAccount::find($debitAccountId);
            $creditAccount = \Domain\SharedKernel\Models\LedgerAccount::find($creditAccountId);

            // Record Debit
            $currentDebitBalanceStr = $this->ledgerRepository->getBalance($debitAccountId);
            $currentDebitBalance = Money::of($currentDebitBalanceStr, $amount->getCurrency());
            $newDebitBalance = $this->calculator->subtract($currentDebitBalance, $amount);

            $this->ledgerRepository->recordEntry([
                'user_id' => $userId,
                'tenant_id' => $tenantId,
                'account_id' => $debitAccountId,
                'transaction_ref_type' => $transactionRefType,
                'transaction_ref_id' => $transactionRefId,
                'account_type' => $debitAccount->account_type,
                'entry_type' => 'debit',
                'amount' => $amount->getAmount(),
                'currency' => $amount->getCurrency()->getCode(),
                'balance_after' => $newDebitBalance->getAmount(),
                'posted_at' => now(),
            ]);

            // Record Credit
            $currentCreditBalanceStr = $this->ledgerRepository->getBalance($creditAccountId);
            $currentCreditBalance = Money::of($currentCreditBalanceStr, $amount->getCurrency());
            $newCreditBalance = $this->calculator->add($currentCreditBalance, $amount);

            $this->ledgerRepository->recordEntry([
                'user_id' => $userId,
                'tenant_id' => $tenantId,
                'account_id' => $creditAccountId,
                'transaction_ref_type' => $transactionRefType,
                'transaction_ref_id' => $transactionRefId,
                'account_type' => $creditAccount->account_type,
                'entry_type' => 'credit',
                'amount' => $amount->getAmount(),
                'currency' => $amount->getCurrency()->getCode(),
                'balance_after' => $newCreditBalance->getAmount(),
                'posted_at' => now(),
            ]);
            
        });
    }
}
