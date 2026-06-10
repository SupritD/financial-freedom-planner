<?php

namespace Domain\SharedKernel\Models;

use Domain\SharedKernel\Traits\BelongsToTenant;
use Domain\SharedKernel\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;

class LedgerEntry extends Model
{
    use HasUlid, BelongsToTenant;

    public const UPDATED_AT = null; // Append-only

    protected $fillable = [
        'user_id',
        'tenant_id',
        'transaction_ref_type',
        'transaction_ref_id',
        'account_type',
        'entry_type',
        'amount',
        'currency',
        'balance_after',
        'posted_at',
    ];

    protected $casts = [
        'amount' => 'decimal:4',
        'balance_after' => 'decimal:4',
        'posted_at' => 'datetime',
    ];
}
