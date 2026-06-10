<?php

namespace Domain\SharedKernel\Models;

use Domain\SharedKernel\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;

class PrivacyPolicyVersion extends Model
{
    use HasUlid;

    protected $fillable = [
        'version',
        'effective_date',
        'summary_of_changes',
        'full_text_url'
    ];

    protected $casts = [
        'effective_date' => 'date',
    ];
}
