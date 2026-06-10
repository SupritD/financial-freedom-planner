<?php

namespace Domain\Auth\Models;

use Domain\SharedKernel\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;

class TrustedDevice extends Model
{
    use HasUlid;

    protected $fillable = [
        'user_id',
        'device_fingerprint',
        'device_name',
        'first_seen',
        'last_seen',
        'trusted_at',
        'is_revoked'
    ];

    protected $casts = [
        'first_seen' => 'datetime',
        'last_seen' => 'datetime',
        'trusted_at' => 'datetime',
        'is_revoked' => 'boolean'
    ];
}
