<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasRepository
{
    /**
     * Boot the global scope.
     */
    protected static function bootHasUser()
    {
        static::addGlobalScope('repository', function (Builder $builder) {
            if (auth()->check()) {
                $builder->whereHas('repository', function ($query) {
                    $query->where('user_id', auth()->user()->id);
                });
            }
        });
    }
}
