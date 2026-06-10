<?php

namespace Domain\Expense\Models;

use Domain\SharedKernel\Traits\HasUlid;
use Domain\SharedKernel\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{
    use HasUlid, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'name',
        'slug',
        'icon',
        'is_system',
        'is_active'
    ];

    protected $casts = [
        'is_system' => 'boolean',
        'is_active' => 'boolean'
    ];
}
