<?php

namespace Domain\SharedKernel\Models;

use Domain\SharedKernel\Traits\BelongsToTenant;
use Domain\SharedKernel\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasUlid, BelongsToTenant;

    public const UPDATED_AT = null; // Append-only

    protected $fillable = [
        'user_id',
        'tenant_id',
        'action',
        'auditable_type',
        'auditable_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'row_hash',
        'previous_hash',
        'chain_verified_at',
    ];

    protected $casts = [
        'old_values' => 'json',
        'new_values' => 'json',
        'chain_verified_at' => 'datetime',
    ];
}
