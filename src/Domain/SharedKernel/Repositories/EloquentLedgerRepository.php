<?php

namespace Domain\SharedKernel\Repositories;

use Domain\SharedKernel\Contracts\LedgerRepositoryInterface;
use Domain\SharedKernel\Models\LedgerEntry;

class EloquentLedgerRepository implements LedgerRepositoryInterface
{
    public function recordEntry(array $data): LedgerEntry
    {
        return LedgerEntry::create($data);
    }

    public function getBalance(string $accountId): string
    {
        $latest = LedgerEntry::where('account_id', $accountId)
            ->orderBy('posted_at', 'desc')
            ->orderBy('id', 'desc')
            ->first();
            
        return $latest ? (string) $latest->balance_after : '0.0000';
    }

    public function getHistory(string $accountId, int $perPage = 15)
    {
        return LedgerEntry::where('account_id', $accountId)
            ->orderBy('posted_at', 'desc')
            ->paginate($perPage);
    }
}
