<?php

namespace App\Services\Repository;

use App\Jobs\Merge;
use App\Models\User\User;
use App\Jobs\DeleteBranch;
use App\Models\Repository;
use App\Models\RepositoryPatch;
use App\Models\User\UserRepositoryProvider;

class RepositoryService
{
    const STYLE_BOT = 'StyleJS-Bot';
    const STYLE_BRANCH = 'stylejs-';

    /**
     * @param UserRepositoryProvider $userRepositoryProvider
     *
     * @return mixed
     */
    public function getRepositories(UserRepositoryProvider $userRepositoryProvider)
    {
        return $this->getProvider($userRepositoryProvider)->getRepositories();
    }

    /**
     * @param User $user
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAllRepositories(User $user)
    {
        $userRepositories = collect();

        foreach ($user->repositoryProviders as $provider) {
            $this->getRepositories($provider)->each(function ($repository) use ($userRepositories, $provider) {
                $userRepositories->push([
                    'id' => $repository['id'],
                    'user_provider_id' => $provider->id,
                    'full_name' => $repository['full_name'],
                ]);
            });
        }

        return $userRepositories;
    }

    /**
     * @param UserRepositoryProvider $userRepositoryProvider
     * @param $repositoryId
     *
     * @return mixed
     */
    public function getRepositoryInfo(UserRepositoryProvider $userRepositoryProvider, $repositoryId)
    {
        return $this->getProvider($userRepositoryProvider)->getRepositoryInfo($repositoryId);
    }

    /**
     * @param Repository $repository
     *
     * @return mixed
     */
    public function getRepositoryBranches(Repository $repository)
    {
        return $this->getProvider($repository->userRepositoryProvider)->getRepositoryBranches($repository);
    }

    /**
     * @param RepositoryPatch $repositoryPatch
     * @param $status
     *
     * @return mixed
     */
    public function updateCommitStatus(RepositoryPatch $repositoryPatch, $status)
    {
        return $this->getProvider($repositoryPatch->repository->userRepositoryProvider)->updateCommitStatus($repositoryPatch, $status);
    }

    /**
     * @param RepositoryPatch $repositoryPatch
     *
     * @return mixed
     */
    public function makePullRequest(RepositoryPatch $repositoryPatch)
    {
        $repository = $repositoryPatch->repository;
        $provider = $this->getProvider($repository->userRepositoryProvider);

        $pullRequest = $provider->makePullRequest($repositoryPatch);

        if (
            $repository->analysis_setting === Repository::MERGE ||
            $repository->analysis_setting === Repository::SQAUSH
        ) {
            Merge::withChain([
                    new DeleteBranch($repositoryPatch)
                ])
                ->dispatch($repositoryPatch, $pullRequest)
                ->delay(now()->addSeconds(5));
        }

        return $pullRequest;
    }

    /**
     * @param RepositoryPatch $repositoryPatch
     * @param $pullRequest
     *
     * @return mixed
     */
    public function mergePullRequest(RepositoryPatch $repositoryPatch, $pullRequest)
    {
        $repository = $repositoryPatch->repository;

        $mergedRequest = $this->getProvider($repository->userRepositoryProvider)->mergePullRequest($repository, $pullRequest);

        $repositoryPatch->update([
            'status' => 'merged',
        ]);

        return $mergedRequest;
    }

    /**
     * @param RepositoryPatch $repositoryPatch
     *
     * @return mixed
     */
    public function deleteBranch(RepositoryPatch $repositoryPatch)
    {
        return $this->getProvider($repositoryPatch->repository->userRepositoryProvider)->deleteBranch($repositoryPatch);
    }

    /**
     * @param Repository $repository
     */
    public function createDeployHook(Repository $repository)
    {
        $this->getProvider($repository->userRepositoryProvider)->createDeployHook($repository);
    }

    /**
     * @param Repository $repository
     */
    public function deleteDeployHook(Repository $repository)
    {
        $this->getProvider($repository->userRepositoryProvider)->deleteDeployHook($repository);
    }

    /**
     * @param UserRepositoryProvider $userRepositoryProvider
     *
     * @return mixed
     */
    private function getProvider(UserRepositoryProvider $userRepositoryProvider)
    {
        return new $userRepositoryProvider->repositoryProvider->repository_class($userRepositoryProvider->token);
    }

    /**
     * @param UserRepositoryProvider $userRepositoryProvider
     * @return mixed
     */
    public function getToken(UserRepositoryProvider $userRepositoryProvider)
    {
        return $this->getProvider($userRepositoryProvider)->getToken($userRepositoryProvider);
    }
}
