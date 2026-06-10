<?php

namespace Domain\Expense\Models;

use Domain\SharedKernel\Traits\HasUlid;
use Domain\SharedKernel\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use HasUlid, BelongsToTenant, SoftDeletes;

    protected $fillable = [
        'user_id',
        'tenant_id',
        'category_id',
        'title',
        'amount',
        'currency',
        'base_currency_amount',
        'exchange_rate',
        'expense_date',
        'month',
        'year'
    ];

    protected $casts = [
        'title' => 'encrypted',
        'amount' => 'encrypted',
        'base_currency_amount' => 'decimal:4',
        'exchange_rate' => 'decimal:6',
        'expense_date' => 'date',
        'month' => 'integer',
        'year' => 'integer'
    ];

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'category_id');
    }
}
