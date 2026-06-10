<?php

namespace Domain\Debt\Models;

use Domain\SharedKernel\Traits\HasUlid;
use Domain\SharedKernel\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class DebtAccount extends Model
{
    use HasUlid, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'name',
        'type',
        'principal_amount',
        'current_balance',
        'currency',
        'interest_rate',
        'minimum_payment',
        'due_date_day',
        'is_paid_off'
    ];

    protected $casts = [
        'principal_amount' => 'decimal:4',
        'current_balance' => 'decimal:4',
        'interest_rate' => 'decimal:2',
        'minimum_payment' => 'decimal:4',
        'due_date_day' => 'integer',
        'is_paid_off' => 'boolean'
    ];
}
