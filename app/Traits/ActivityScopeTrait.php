<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

Trait ActivityScopeTrait
{

    public function scopeActive(Builder $builder)
    {
        return $builder->where('status','active');
    }

    public function scopeInactive(Builder $builder)
    {
        return $builder->where('status','inactive');
    }
}