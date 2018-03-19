<?php

namespace App\Contracts;

use App\Models\Repository;
use App\Models\RepositoryPatch;
use App\Models\User\UserRepositoryProvider;

interface RepositoryContract
{
    /**
     * @return array|mixed
     */
    public function getRepositories();

    /**
     * @param $repositoryId
     *
     * @return mixed
     */
    public function getRepositoryInfo($repositoryId);

    /**
     * @param RepositoryPatch $repositoryPatch
     * @param $status
     */
    public function updateCommitStatus(RepositoryPatch $repositoryPatch, $status);

    /**
     * @param Repository $repository
     * @param $branch
     *
     * @return mixed
     */
    public function getLatestCommitFromBranch(Repository $repository, $branch);
    /**
     * @param RepositoryPatch $repositoryPatch
     *
     * @return array
     * @throws \Github\Exception\MissingArgumentException
     */
    public function makePullRequest(RepositoryPatch $repositoryPatch);

    /**
     * @param Repository $repository
     * @param $pullRequest
     *
     * @return mixed
     */
    public function mergePullRequest(Repository $repository, $pullRequest);

    /**
     * @param Repository $repository
     *
     * @return Repository $repository
     */
    public function createDeployHook(Repository $repository);

    /**
     * @param Repository $repository
     *
     * @return Repository $repository
     */
    public function deleteDeployHook(Repository $repository);

    /**
     * @param Repository $repository
     *
     * @return mixed
     */
    public function getRepositoryBranches(Repository $repository);

    /**
     * @param RepositoryPatch $repositoryPatch
     *
     * @internal param $branch
     */
    public function deleteBranch(RepositoryPatch $repositoryPatch);

    /**
     * @param UserRepositoryProvider $userRepositoryProvider
     * @return mixed
     */
    public function getToken(UserRepositoryProvider $userRepositoryProvider);
}
