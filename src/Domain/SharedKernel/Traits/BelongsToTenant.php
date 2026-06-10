<?php

namespace Domain\SharedKernel\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait BelongsToTenant
{
    protected static function bootBelongsToTenant(): void
    {
        static::addGlobalScope('tenant', function (Builder $builder) {
            if (app()->has('tenant_id')) {
                $builder->where('tenant_id', app('tenant_id'));
            }
        });

        static::creating(function (Model $model) {
            if (empty($model->tenant_id) && app()->has('tenant_id')) {
                $model->tenant_id = app('tenant_id');
            }
        });
    }
}
