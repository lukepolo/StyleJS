<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Models\RepositoryPatch;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\Repository\RepositoryService;

class Merge implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5;
    public $timeout = 10;

    protected $pullRequest;
    protected $repositoryPatch;
    protected $repositoryService;

    /**
     * Create a new job instance.
     *
     * @param RepositoryPatch $repositoryPatch
     * @param $pullRequest
     */
    public function __construct(RepositoryPatch $repositoryPatch, $pullRequest)
    {
        $this->pullRequest = $pullRequest;
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
        $this->repositoryService->mergePullRequest($this->repositoryPatch, $this->pullRequest);

        $this->repositoryPatch->update([
            'sha' => null,
        ]);

        $this->repositoryService->updateCommitStatus($this->repositoryPatch, 'success');
    }
}
