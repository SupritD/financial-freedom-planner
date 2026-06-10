<?php

namespace Domain\SharedKernel\Models;

use Domain\SharedKernel\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;

class IdempotencyKey extends Model
{
    use HasUlid;

    public const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'key',
        'endpoint',
        'response_status',
        'response_body',
        'created_at',
        'expires_at'
    ];

    protected $casts = [
        'response_body' => 'json',
        'created_at' => 'datetime',
        'expires_at' => 'datetime'
    ];
}
