<?php

namespace Domain\Expense\Models;

use Domain\SharedKernel\Traits\HasUlid;
use Domain\SharedKernel\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class ExpenseBudget extends Model
{
    use HasUlid, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'category_id',
        'amount',
        'month',
        'year',
        'alert_threshold'
    ];

    protected $casts = [
        'amount' => 'decimal:4',
        'month' => 'integer',
        'year' => 'integer',
        'alert_threshold' => 'decimal:2'
    ];

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'category_id');
    }
}
