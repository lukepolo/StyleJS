<?php

namespace App\Http\Controllers\Repository;

use App\Models\Repository;
use App\Http\Controllers\Controller;
use App\Services\Repository\RepositoryService;

class RepositoryBranchesController extends Controller
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
     * @param $repositoryId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function index($repositoryId)
    {
        $repository = Repository::with('userRepositoryProvider.repositoryProvider')->findOrFail($repositoryId);

        try {
            $repositoryBranches = $this->repositoryService->getRepositoryBranches($repository);
        } catch (\Exception $e) {
            if (! $e->getMessage() === 'Not Found') {
                throw $e;
            }

            $repositoryBranches = [];
        }

        return response()->json($repositoryBranches);
    }
}
