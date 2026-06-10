<?php

namespace Domain\Auth\Models;

use Domain\SharedKernel\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;

class LoginEvent extends Model
{
    use HasUlid;

    public const UPDATED_AT = null; // Append-only

    protected $fillable = [
        'user_id',
        'ip_address',
        'country_code',
        'city',
        'device_type',
        'browser',
        'success',
        'failure_reason',
        'created_at'
    ];

    protected $casts = [
        'success' => 'boolean',
        'created_at' => 'datetime'
    ];
}
