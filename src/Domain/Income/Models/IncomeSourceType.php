<?php

namespace Domain\Income\Models;

use Domain\SharedKernel\Traits\HasUlid;
use Domain\SharedKernel\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class IncomeSourceType extends Model
{
    use HasUlid, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'name',
        'slug',
        'icon',
        'is_system',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_system' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];
}
