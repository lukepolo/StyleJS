<?php

namespace App\Http\Controllers\Repository;

use App\Models\Repository;
use App\Http\Controllers\Controller;
use App\Services\Repository\RepositoryService;

class RepositoryWebHooksController extends Controller
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
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store($repositoryId)
    {
        try {
            $this->repositoryService->createDeployHook(Repository::findOrFail($repositoryId));
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }

        return response()->json('OK');
    }
}
