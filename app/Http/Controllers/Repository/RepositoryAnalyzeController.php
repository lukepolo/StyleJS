<?php

namespace App\Http\Controllers\Repository;

use App\Jobs\Prettify;
use App\Models\Repository;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Vinkla\Hashids\Facades\Hashids;
use App\Http\Controllers\Controller;
use App\Services\Repository\RepositoryService;
use App\Http\Controllers\Auth\OauthController;
use App\Http\Requests\AnalyzeRepositoryRequest;

class RepositoryAnalyzeController extends Controller
{
    protected $repositoryService;

    /**
     * RepositoryController constructor.
     * @param RepositoryService $repositoryService
     */
    public function __construct(RepositoryService $repositoryService)
    {
        $this->repositoryService = $repositoryService;
    }

    /**
     * @param AnalyzeRepositoryRequest $request
     * @param $repositoryHashId
     * @return \Illuminate\Http\JsonResponse
     */
    public function analyze(AnalyzeRepositoryRequest $request, $repositoryHashId)
    {
        $error = null;

        $repository = $this->getRepository($repositoryHashId);

        if (empty($repository)) {
            return $this->showError('Unable to find repository');
        }

        if ($this->isAlreadyRunning($repository)) {
            return $this->showError('There is a patch already running.');
        }

        $branch = $request->get('branch', $repository->default_branch);

        if (empty($branch) && $this->isOnDemandOnly($repository)) {
            return $this->showError('This repository requires that a branch must be set in the query parameters for on demand requests');
        }

        if ($this->isBotCommit($repository, $request, $branch)) {
            return $this->showSuccess();
        }

        $error = $this->canRunAnalysisOnBranch(collect($repository->branches), $branch);

        if ($error === true) {
            $this->dispatch(new Prettify($this->repositoryService, $repository, $branch));
            return $this->showSuccess();
        }

        return $this->showError($error);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    protected function showSuccess()
    {
        return response()->json('OK');
    }

    /**
     * @param $error
     * @param int $status
     * @return \Illuminate\Http\JsonResponse
     */
    protected function showError($error, $status = 409)
    {
        return response()->json($error, $status);
    }

    /**
     * @param $repositoryHashId
     * @return Repository
     */
    protected function getRepository($repositoryHashId)
    {
        $repositoryId = Hashids::decode($repositoryHashId);

        if (! empty($repositoryId)) {
            return Repository::with('userRepositoryProvider.repositoryProvider')->findOrFail($repositoryId[0]);
        }
    }

    /**
     * @param Repository $repository
     * @return bool
     */
    protected function isAlreadyRunning(Repository $repository)
    {
        $currentPatch = $repository->patches()
            ->where('status', 'queued')
            ->Orwhere('status', 'pending')
            ->first();

        if (! empty($currentPatch)) {
            return true;
        }
        return false;
    }

    /**
     * @param Repository $repository
     * @return bool
     */
    protected function isOnDemandOnly(Repository $repository)
    {
        if ($repository->on_demand === true) {
            return true;
        }
        return false;
    }

    /**
     * @param Repository $repository
     * @param Request $request
     * @param $branch
     * @return bool|\Illuminate\Http\JsonResponse
     */
    protected function isBotCommit(Repository $repository, Request $request, $branch)
    {
        if (str_contains($branch, RepositoryService::STYLE_BRANCH)) {
            return response()->json('OK');
        }

        switch ($repository->userRepositoryProvider->repositoryProvider->provider_name) {
            case OauthController::GITHUB:

                $headCommit = $request->get('head_commit');

                if (! empty($headCommit)) {
                    if ($headCommit['committer']['name'] === RepositoryService::STYLE_BOT) {
                        return response()->json('OK');
                    }
                }

                break;
        }
        return false;
    }

    /**
     * @param Collection $branches
     * @param $branch
     * @return bool|string
     */
    protected function canRunAnalysisOnBranch(Collection $branches, $branch)
    {
        if (! $branches->isEmpty() && ! $branches->contains($branch)) {
            if (empty($branch)) {
                return 'You must supply a branch, please see the FAQ if your having trouble';
            } else {
                return 'This branch ('.$branch.') is not available for StyleJS, '.'please see the FAQ if your having trouble';
            }
        }
        return true;
    }
}
