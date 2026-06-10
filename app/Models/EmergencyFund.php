<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmergencyFund extends Model
{
    protected $fillable = [
        'user_id',
        'tenant_id',
        'monthly_expenses',
        'recommended_months',
        'current_amount',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
