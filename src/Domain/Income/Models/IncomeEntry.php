<?php

namespace Domain\Income\Models;

use Domain\SharedKernel\Traits\HasUlid;
use Domain\SharedKernel\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IncomeEntry extends Model
{
    use HasUlid, BelongsToTenant, SoftDeletes;

    protected $fillable = [
        'user_id',
        'tenant_id',
        'source_type_id',
        'amount',
        'currency',
        'base_currency_amount',
        'exchange_rate',
        'income_date',
        'financial_year'
    ];

    protected $casts = [
        'amount' => 'decimal:4',
        'base_currency_amount' => 'decimal:4',
        'exchange_rate' => 'decimal:6',
        'income_date' => 'date'
    ];

    public function sourceType()
    {
        return $this->belongsTo(IncomeSourceType::class, 'source_type_id');
    }
}
