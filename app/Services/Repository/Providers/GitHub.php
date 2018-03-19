<?php

namespace App\Services\Repository\Providers;

use Github\Client;
use Github\ResultPager;
use App\Models\Repository;
use App\Models\RepositoryPatch;
use App\Contracts\RepositoryContract;
use Github\Exception\RuntimeException;
use App\Models\User\UserRepositoryProvider;
use App\Exceptions\RemoteRepositoryNotFound;

class GitHub implements RepositoryContract
{
    protected $client;
    protected $paginator;

    /**
     * GitHub constructor.
     *
     * @param $token
     */
    public function __construct($token)
    {
        /* @var Client $client */
        $this->client = new Client();
        $this->client->authenticate($token, 'http_token');
        $this->paginator = new ResultPager($this->client);
    }

    /**
     * @return array|mixed
     */
    public function getRepositories()
    {
        $myRepos = collect($this->paginator->fetchAll($this->client->currentUser(), 'repositories', [
            'affiliation' => 'owner',
        ]));

        $orgRepos = collect($this->paginator->fetchAll($this->client->currentUser(), 'repositories', [
            'affiliation' => 'organization_member',
        ]))->filter(function ($repository) {
            return $repository['permissions']['admin'] === true;
        });

        $orgs = $this->client->currentUser()->memberships()->all();

        foreach ($orgs as $org) {
            if ($org['role'] === 'admin') {
                $myRepos = $myRepos->merge($this->client->repos()->org($org['organization']['login']));
            }
        }

        return $myRepos
            ->merge($orgRepos)
            ->sortBy(function ($repository) {
                return $repository['full_name'];
            });
    }

    /**
     * @param $repositoryId
     *
     * @return mixed
     */
    public function getRepositoryInfo($repositoryId)
    {
        return $this->client->api('repo')->showById((int) $repositoryId);
    }

    /**
     * @param RepositoryPatch $repositoryPatch
     * @param $status
     * @throws RemoteRepositoryNotFound
     */
    public function updateCommitStatus(RepositoryPatch $repositoryPatch, $status)
    {
        $repository = $repositoryPatch->repository;

        try {
            if (empty($repositoryPatch->sha)) {
                $repositoryPatch->update([
                    'sha' => $this->getLatestCommitFromBranch($repository, $repositoryPatch->branch)['sha'],
                ]);
            }

            $this->client->api('repo')->statuses()->create(
                $this->getRepositoryOwner($repository),
                $this->getRepositoryName($repository),
                $repositoryPatch->sha, [
                    'state'       => $status,
                    'target_url'  => action('Repository\RepositoryPatchesController@show', [$repository->id, $repositoryPatch->id]),
                    'description' => 'StyleJS CI',
                    'context'     => 'continuous-integration/stylejs',
                ]
            );
        } catch (RuntimeException $e) {
            if ($e->getMessage() === 'Not Found') {
                throw new RemoteRepositoryNotFound();
            }
            throw $e;
        }
    }

    /**
     * @param Repository $repository
     * @param $branch
     *
     * @return mixed
     */
    public function getLatestCommitFromBranch(Repository $repository, $branch)
    {
        return collect($this->client->api('repo')->commits()->all(
            $this->getRepositoryOwner($repository),
            $this->getRepositoryName($repository), [
                'sha' => $branch,
            ]
        ))->first();
    }

    /**
     * @param RepositoryPatch $repositoryPatch
     *
     * @return array
     * @throws \Github\Exception\MissingArgumentException
     */
    public function makePullRequest(RepositoryPatch $repositoryPatch)
    {
        $repository = $repositoryPatch->repository;

        return $this->client->pullRequest()
            ->create(
                $this->getRepositoryOwner($repository),
                $this->getRepositoryName($repository), [
                'base'  => $repositoryPatch->branch,
                'head'  => $repositoryPatch->patch_branch,
                'title' => 'Apply fixes from StyleJS',
                'body'  => 'This pull request applies code style fixes from an analysis carried out by [StyleJS](https://stylejs.io).

---

For more information, click [here](https://stylejs.io/analyses/'.$repositoryPatch->id.').',
            ]);
    }

    /**
     * @param Repository $repository
     * @param $pullRequest
     *
     * @return mixed
     */
    public function mergePullRequest(Repository $repository, $pullRequest)
    {
        return $this->client->pullRequest()
            ->merge(
                $this->getRepositoryOwner($repository),
                $this->getRepositoryName($repository),
                $pullRequest['number'],
                'StyleJS Auto Squash and Merge PR-'.$pullRequest['number'],
                $pullRequest['head']['sha'],
                $repository->analysis_setting === Repository::MERGE ? 'merge' : 'squash'
            );
    }

    /**
     * @param Repository $repository
     *
     * @return Repository $repository
     */
    public function createDeployHook(Repository $repository)
    {
        $webhook = $this->client->api('repo')
            ->hooks()
            ->create(
                $this->getRepositoryOwner($repository),
                $this->getRepositoryName($repository), [
                'name'   => 'web',
                'active' => true,
                'events' => [
                    'push',
                ],
                'config' => [
                    'url'          => action('Repository\RepositoryAnalyzeController@analyze', $repository->hashId()),
                    'content_type' => 'json',
                ],
            ]);

        $repository->update([
            'automatic_deployment_id' => $webhook['id'],
        ]);

        return $repository;
    }

    /**
     * @param Repository $repository
     *
     * @return Repository $repository
     */
    public function deleteDeployHook(Repository $repository)
    {
        $automaticDeploymentId = $repository->automatic_deployment_id;
        $repository->update([
            'automatic_deployment_id' => null,
        ]);

        $this->client->api('repo')
            ->hooks()
            ->remove(
                $this->getRepositoryOwner($repository),
                $this->getRepositoryName($repository),
                $automaticDeploymentId
            );

        return $repository;
    }

    /**
     * @param Repository $repository
     *
     * @return mixed
     */
    public function getRepositoryBranches(Repository $repository)
    {
        return collect(
                $this->client->api('repo')->branches(
                $this->getRepositoryOwner($repository),
                $this->getRepositoryName($repository)
            ))->map(function ($branch) {
                return $branch['name'];
            });
    }

    /**
     * @param RepositoryPatch $repositoryPatch
     *
     * @internal param $branch
     */
    public function deleteBranch(RepositoryPatch $repositoryPatch)
    {
        $repository = $repositoryPatch->repository;

        $this->client->api('git')->references()->remove(
            $this->getRepositoryOwner($repository),
            $this->getRepositoryName($repository),
            'heads/'.$repositoryPatch->patch_branch
        );
    }

    /**
     * @param UserRepositoryProvider $userRepositoryProvider
     * @return mixed|string
     */
    public function getToken(UserRepositoryProvider $userRepositoryProvider)
    {
        return $userRepositoryProvider->token;
    }

    /**
     * @param Repository $repository
     *
     * @return mixed
     */
    protected function getRepositoryOwner(Repository $repository)
    {
        $repositoryDetails = explode('/', $repository->repository);

        return $repositoryDetails[0];
    }

    /**
     * @param Repository $repository
     *
     * @return mixed
     */
    protected function getRepositoryName(Repository $repository)
    {
        $repositoryDetails = explode('/', $repository->repository);

        return $repositoryDetails[1];
    }
}
