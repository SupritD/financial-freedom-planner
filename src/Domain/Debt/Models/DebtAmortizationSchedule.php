<?php

namespace Domain\Debt\Models;

use Domain\SharedKernel\Traits\HasUlid;
use Domain\SharedKernel\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class DebtAmortizationSchedule extends Model
{
    use HasUlid, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'debt_account_id',
        'payment_number',
        'payment_date',
        'principal_payment',
        'interest_payment',
        'remaining_balance'
    ];

    protected $casts = [
        'payment_number' => 'integer',
        'payment_date' => 'date',
        'principal_payment' => 'decimal:4',
        'interest_payment' => 'decimal:4',
        'remaining_balance' => 'decimal:4'
    ];

    public function debtAccount()
    {
        return $this->belongsTo(DebtAccount::class, 'debt_account_id');
    }
}
