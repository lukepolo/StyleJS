<?php

namespace App\Models\User;

use App\Traits\HasUser;
use App\Traits\Encryptable;
use App\Models\RepositoryProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserRepositoryProvider extends Model
{
    use SoftDeletes, Encryptable, HasUser;

    protected $guarded = ['id'];

    protected $encryptable = [
        'token',
        'refresh_token',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function repositoryProvider()
    {
        return $this->belongsTo(RepositoryProvider::class);
    }
}
