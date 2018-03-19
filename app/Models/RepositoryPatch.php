<?php

namespace App\Models;

use App\Traits\HasRepository;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RepositoryPatch.
 */
class RepositoryPatch extends Model
{
    use HasRepository;

    protected $guarded = ['id'];

    const ERROR = 'error';
    const FAILED = 'failure';
    const SUCCESS = 'success';
    const PATCHED = 'patched';
    const PASSED = 'passed';
    const PENDING = 'pending';
    const QUEUED = 'queued';
    const MERGED = 'merged';

    const STATUSES = [
        self::ERROR   => 's-failed go-red',
        self::FAILED  => 's-check go-green',
        self::MERGED  => 's-check go-green',
        self::PASSED  => 's-check go-green',
        self::PATCHED => 's-check go-green',
        self::PENDING => 's-pending go-gray',
        self::QUEUED  => 's-pending go-gray',
        self::SUCCESS => 's-check go-green',
    ];

    protected $appends = [
        'linkToPatch'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function repository()
    {
        return $this->belongsTo(Repository::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function getLinkToPatchAttribute()
    {
        $repository = $this->repository;

        return  'https://'.$repository->userRepositoryProvider->repositoryProvider->url.'/'.$repository->repository.'/commit/'.$this->sha;
    }
}
