<?php

namespace App\Http\Controllers\Repository;

use App\Models\RepositoryPatch;
use App\Http\Controllers\Controller;
use App\Http\Resources\RepositoryPatchResource;

class RepositoryPatchesController extends Controller
{
    /**
     * @param $repositoryId
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index($repositoryId)
    {
        return RepositoryPatchResource::collection(
            RepositoryPatch::where('repository_id', $repositoryId)->latest()->paginate(10)
        );
    }

    /**
     * @param $repositoryId
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($repositoryId, $id)
    {
        return new RepositoryPatchResource(
            RepositoryPatch::with('repository')->where('repository_id', $repositoryId)->findOrFail($id)
        );
    }
}
