<?php

namespace App\Observers;

use App\Jobs\DeleteDeployHook;
use App\Models\Repository;
use App\Services\Repository\RepositoryService;

class RepositoryObserver
{
    protected $repositoryService;

    /**
     * RepositoryObserver constructor.
     */
    public function __construct()
    {
        $this->repositoryService = new RepositoryService();
    }

    /**
     * @param Repository $repository
     */
    public function deleting(Repository $repository)
    {
        $repository->patches()->delete();
        dispatch(new DeleteDeployHook($repository));
    }
}
