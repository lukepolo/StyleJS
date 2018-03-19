<?php

namespace App\Http\Controllers\Repository;

use App\Models\Repository;
use App\Models\RepositoryPatch;
use App\Http\Controllers\Controller;
use App\Http\Requests\RepositoryBadgeRequest;

class RepositoryBadgeController extends Controller
{
    /**
     * @param RepositoryBadgeRequest $request
     * @param $repositoryId
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(RepositoryBadgeRequest $request, $repositoryId)
    {
        $repository = Repository::findOrFail($repositoryId);

        $branch = $request->get('branch');

        if (empty($branch)) {
            $branch = $repository->default_branch ?: 'master';
        }

        $repository->load(['patches' => function ($query) use ($branch) {
            $query->where('branch', $branch)->latest()->take(1);
        }]);

        $patch = $repository->patches->first();

        $color = '#9f9f9f';

        if (empty($patch)) {
            $status = '-';
        } else {
            $status = $patch->status;

            switch ($patch->status) {
                case RepositoryPatch::ERROR:
                case RepositoryPatch::FAILED:
                    $color = '#e05d44';
                    break;
                case RepositoryPatch::MERGED:
                case RepositoryPatch::PASSED:
                case RepositoryPatch::PATCHED:
                case RepositoryPatch::SUCCESS:
                    $color = '#44CC11';
                    break;
            }
        }

        return response(
            view('repositories.badge', [
                'color'  => $color,
                'status' => $status,
            ])
        )->withHeaders([
            'Content-Type' => 'image/svg+xml',
        ]);
    }
}
