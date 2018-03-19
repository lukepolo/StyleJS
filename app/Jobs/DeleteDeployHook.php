<?php

namespace App\Jobs;

use App\Models\Repository;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\Repository\RepositoryService;
use App\Notifications\DeployHookFailedToDelete;

class DeleteDeployHook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    private $user;
    private $repository;

    /**
     * Create a new job instance.
     *
     * @param Repository $repository
     */
    public function __construct(Repository $repository)
    {
        $this->user = $repository->user;
        $this->repository = $repository;
    }

    /**
     * Execute the job.
     *
     * @param RepositoryService $repositoryService
     * @return void
     */
    public function handle(RepositoryService $repositoryService)
    {
        try {
            $repositoryService->deleteDeployHook($this->repository);
        } catch (\Exception $e) {
            $this->user->notify(new DeployHookFailedToDelete($this->repository));
        }
    }
}
