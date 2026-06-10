<?php

namespace Domain\Goal\Models;

use Domain\SharedKernel\Traits\HasUlid;
use Domain\SharedKernel\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class FinancialGoal extends Model
{
    use HasUlid, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'name',
        'target_amount',
        'current_amount',
        'currency',
        'deadline',
        'is_completed'
    ];

    protected $casts = [
        'target_amount' => 'decimal:4',
        'current_amount' => 'decimal:4',
        'deadline' => 'date',
        'is_completed' => 'boolean'
    ];
}
