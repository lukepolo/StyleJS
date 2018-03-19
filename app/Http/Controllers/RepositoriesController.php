<?php

namespace App\Http\Controllers;

use App\Jobs\DeleteDeployHook;
use App\Models\Repository;
use App\Http\Resources\RepositoryResource;
use App\Http\Requests\UpdateRepositoryRequest;

class RepositoriesController extends Controller
{
    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $repositories = Repository::get()->map(function ($repository) {
            $repository->load(['patches' => function ($query) {
                $query->latest()->take(1);
            }]);
            $repository->last_patch = $repository->patches->first();
            return $repository;
        });

        return RepositoryResource::collection($repositories);
    }

    /**
     * @param $id
     * @return RepositoryResource
     */
    public function show($id)
    {
        return new RepositoryResource(Repository::findOrFail($id));
    }

    /**
     * @param UpdateRepositoryRequest $request
     * @param $id
     *
     * @return RepositoryResource
     */
    public function update(UpdateRepositoryRequest $request, $id)
    {
        $repository = Repository::findOrFail($id);

        $repository->update($request->validated());

        return new RepositoryResource($repository);
    }

    /**
     * @param $id
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $repository = Repository::with('userRepositoryProvider.repositoryProvider')->findOrFail($id);

        dispatch(new DeleteDeployHook($repository));

        $repository->delete();

        return response()->json('OK');
    }
}
