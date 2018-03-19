<?php

namespace App\Jobs;

use App\Models\RepositoryPatch;
use App\Services\Repository\RepositoryService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeleteBranch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 10;

    protected $repositoryPatch;
    protected $repositoryService;

    /**
     * Create a new job instance.
     *
     * @param RepositoryPatch $repositoryPatch
     */
    public function __construct(RepositoryPatch $repositoryPatch)
    {
        $this->repositoryPatch = $repositoryPatch;
        $this->repositoryService = new RepositoryService();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->repositoryService->deleteBranch($this->repositoryPatch);
    }
}
