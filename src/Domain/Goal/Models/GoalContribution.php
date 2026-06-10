<?php

namespace Domain\Goal\Models;

use Domain\SharedKernel\Traits\HasUlid;
use Domain\SharedKernel\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class GoalContribution extends Model
{
    use HasUlid, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'goal_id',
        'amount',
        'currency',
        'contribution_date'
    ];

    protected $casts = [
        'amount' => 'decimal:4',
        'contribution_date' => 'date'
    ];

    public function goal()
    {
        return $this->belongsTo(FinancialGoal::class, 'goal_id');
    }
}
