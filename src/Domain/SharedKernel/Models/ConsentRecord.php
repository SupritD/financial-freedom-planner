<?php

namespace Domain\SharedKernel\Models;

use Domain\SharedKernel\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;

class ConsentRecord extends Model
{
    use HasUlid;

    protected $fillable = [
        'user_id',
        'consent_type',
        'version',
        'consented_at',
        'ip_address',
        'withdrawn_at'
    ];

    protected $casts = [
        'consented_at' => 'datetime',
        'withdrawn_at' => 'datetime',
    ];
}
