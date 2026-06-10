<?php

namespace Domain\SharedKernel\Contracts;

use Domain\SharedKernel\Models\LedgerEntry;

interface LedgerRepositoryInterface
{
    public function recordEntry(array $data): LedgerEntry;
    
    public function getBalance(string $accountId): string;
    
    public function getHistory(string $accountId, int $perPage = 15);
}
