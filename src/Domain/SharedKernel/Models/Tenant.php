<?php

namespace Domain\SharedKernel\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'slug',
        'plan_id',
        'settings',
        'is_active',
    ];

    protected $casts = [
        'settings' => 'json',
        'is_active' => 'boolean',
    ];
}
