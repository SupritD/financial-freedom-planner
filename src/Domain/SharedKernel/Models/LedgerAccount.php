<?php

namespace Domain\SharedKernel\Models;

use Domain\SharedKernel\Traits\BelongsToTenant;
use Domain\SharedKernel\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;

class LedgerAccount extends Model
{
    use HasUlid, BelongsToTenant;

    protected $fillable = [
        'user_id',
        'tenant_id',
        'account_type',
        'name',
        'current_balance',
        'is_system',
    ];

    protected $casts = [
        'current_balance' => 'decimal:4',
        'is_system' => 'boolean',
    ];
}
