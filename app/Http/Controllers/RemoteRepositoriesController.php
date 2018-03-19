<?php

namespace App\Http\Controllers;

use App\Models\Repository;
use Illuminate\Http\Request;
use App\Http\Resources\RepositoryResource;
use App\Exceptions\RemoteRepositoryNotFound;
use App\Http\Requests\WatchRepositoryRequest;
use App\Services\Repository\RepositoryService;

class RemoteRepositoriesController extends Controller
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
     * Gets the users repositories.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return response()->json($this->repositoryService->getAllRepositories($request->user())->values());
    }

    /**
     * @param WatchRepositoryRequest $request
     *
     * @return RepositoryResource|\Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function store(WatchRepositoryRequest $request)
    {
        try {
            $repositoryInfo = $this->repositoryService->getRepositoryInfo(
                $request
                    ->user()
                    ->repositoryProviders
                    ->keyBy('id')
                    ->get($request->get('user_provider_id')),
                $request->get('repository_id')
            );
        } catch (RemoteRepositoryNotFound $e) {
            return response()->json('We could not find this repository.', 500);
        } catch (\Exception $e) {
            return response()->json('Something went wrong.', 500);
        }

        $repository = Repository::create([
            'user_id'                     => $request->user()->id,
            'repository_id'               => $repositoryInfo['id'],
            'repository'                  => $repositoryInfo['full_name'],
            'default_branch'              => $repositoryInfo['default_branch'],
            'user_repository_provider_id' => $request->user()->repositoryProviders->first()->id,
            'ignore_directories'  => "public",
            'analysis_setting'    => Repository::PR_ONLY,
            'file_types' => [
                'js'
            ],
        ]);

        try {
            $this->repositoryService->createDeployHook($repository);
        } catch (\Exception $e) {
            $repository->delete();
            return response()->json('Unable to Create Deploy Hook, you may have to check if you have proper permissions', 509);
        }

        return new RepositoryResource($repository);
    }
}
