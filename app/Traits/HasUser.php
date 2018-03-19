<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasUser
{
    /**
     * Boot the global scope.
     */
    protected static function bootHasUser()
    {
        static::addGlobalScope('user', function (Builder $builder) {
            if (auth()->check()) {
                $builder->where('user_id', \Auth::user()->id);
            }
        });
    }
}
