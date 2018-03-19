<?php

namespace App\Models\User;

use App\Traits\HasUser;
use App\Traits\Encryptable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserLoginProvider extends Model
{
    use SoftDeletes, Encryptable, HasUser;

    protected $encryptable = [
        'token',
        'refresh_token',
    ];

    protected $guarded = ['id'];

    protected $dates = [
        'created_at',
        'updated_at',
        'expires_in',
        'deleted_at',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
