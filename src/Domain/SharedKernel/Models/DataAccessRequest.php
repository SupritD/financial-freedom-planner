<?php

namespace Domain\SharedKernel\Models;

use Domain\SharedKernel\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;

class DataAccessRequest extends Model
{
    use HasUlid;

    protected $fillable = [
        'user_id',
        'request_type',
        'status',
        'requested_at',
        'fulfilled_at',
        'fulfillment_data'
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'fulfilled_at' => 'datetime',
        'fulfillment_data' => 'json'
    ];
}
