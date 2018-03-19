<?php

namespace App\Models\User;

use App\Models\Repository;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $subscription;

    protected $guarded = [
        'id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $dates = [
        'updated_at',
        'created_at',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function repositories()
    {
        return $this->hasMany(Repository::class);
    }

    public function loginProviders()
    {
        return $this->hasMany(UserLoginProvider::class);
    }

    public function repositoryProviders()
    {
        return $this->hasMany(UserRepositoryProvider::class);
    }
}
