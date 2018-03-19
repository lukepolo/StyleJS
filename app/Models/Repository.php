<?php

namespace App\Models;

use App\Traits\HasUser;
use App\Models\User\User;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Database\Eloquent\Model;
use App\Models\User\UserRepositoryProvider;

/**
 * Class RepositoryProvider.
 */
class Repository extends Model
{
    use HasUser;

    protected $guarded = ['id'];

    protected $attributes = [
        'branches' => '{}',
        'file_types' => '{}',
        'cli_options' => '{}',
    ];

    protected $casts = [
        'branches' => 'array',
        'file_types' => 'array',
        'cli_options' => 'json',
    ];

    protected $appends = [
        'hash'
    ];

    /*
    |--------------------------------------------------------------------------
    | Constants
    |--------------------------------------------------------------------------
    */
    const TRAILING_COMMAS = [
        'none',
        'es5',
        'all',
    ];

    const ARROW_PARENTS = [
        'avoid',
        'always',
    ];

    const PROSE_WRAP = [
        'preserve',
        'always',
        'never',
    ];

    const FIlE_TYPES = [
        'js',
        'css',
        'less',
        'scss',
        'ts',
        'tsx',
        'vue',
        'html',
        'json',
        'md',
    ];

    const PR_ONLY = 'pr_only';
    const MERGE = 'auto_merge';
    const SQAUSH = 'auto_squash';
    const DIRECT = 'direct_commit';

    const ANALYSIS_SETTINGS = [
        self::PR_ONLY => 'Create Pull Request Only',
        self::MERGE   => 'Auto Merge Pull Request',
        self::SQAUSH  => 'Auto Squash & Merge Pull Request',
        self::DIRECT  => 'Directly Commit to Same Branch',
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

    public function userRepositoryProvider()
    {
        return $this->belongsTo(UserRepositoryProvider::class);
    }

    public function patches()
    {
        return $this->hasMany(RepositoryPatch::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */
    public function getHashAttribute()
    {
        return $this->hashId();
    }

    public function hashId()
    {
        return Hashids::encode($this->id);
    }
}
