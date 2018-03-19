<?php

namespace App\Jobs;

use App\Models\Repository;
use Illuminate\Bus\Queueable;
use App\Models\RepositoryPatch;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\Process\Process;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\Repository\RepositoryService;
use Symfony\Component\Process\Exception\ProcessFailedException;

class Prettify implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;
    public $timeout = 600;

    protected $branch;
    protected $options;
    protected $clonePath;
    protected $startTime;
    protected $repository;
    protected $output = [];
    protected $prettierOutput;
    protected $repositoryPatch;
    protected $repositoryService;

    /**
     * Create a new job instance.
     *
     * @param RepositoryService $repositoryService
     * @param Repository $repository
     * @param string $branch
     */
    public function __construct(RepositoryService $repositoryService, Repository $repository, $branch = null)
    {
        $this->branch = $branch;
        $this->repository = $repository;
        $this->repositoryService = $repositoryService;
        $this->clonePath = storage_path('patches/'.$this->repository->id);

        if (empty($branch)) {
            $this->branch = 'master';
        }

        $this->repositoryPatch = RepositoryPatch::create([
            'branch'        => $this->branch,
            'repository_id' => $this->repository->id,
        ]);

        $this->repositoryService->updateCommitStatus($this->repositoryPatch, 'pending');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->setupNodePath();
        $this->startTime = microtime(true);

        try {
            $this->removeCloneDirectory();
            $this->runCommand($this->getCloneCommand());
            $this->runCommand($this->getPrettierCommand());

            $patchBranch = $this->getPatchBranchName();

            $this->commitChanges($patchBranch);

            $this->repositoryPatch->update([
                'status'       => 'patched',
                'patch_branch' => $patchBranch,
                'runtime'      => $this->calculateRunningTime(),
                'log'          => implode("\n", $this->output),
            ]);

            if ($this->branch !== $patchBranch) {
                $this->repositoryService->updateCommitStatus($this->repositoryPatch, 'failure');
                $this->repositoryService->makePullRequest($this->repositoryPatch);
            } else {
                $this->repositoryService->updateCommitStatus($this->repositoryPatch, 'success');
            }

            $this->removeCloneDirectory();
        } catch (ProcessFailedException $e) {
            $this->captureError($e);
            $this->removeCloneDirectory();
        }
    }

    /**
     * @return string
     */
    protected function getCloneCommand()
    {
        $loadSshKeyCommand = '';

        if ($this->repository->userRepositoryProvider) {
            $repositoryProvider = $this->repository->userRepositoryProvider->repositoryProvider;
            $token = $this->repositoryService->getToken($this->repository->userRepositoryProvider);
            switch ($repositoryProvider->name) {
                case 'Bitbucket':
                    $url = "https://x-token-auth:{{$token}}@bitbucket.org/{$this->repository->repository}.git";
                    break;
                case 'GitHub':
                    $url = "https://{$token}@github.com/{$this->repository->repository}.git";
                    break;
                case 'GitLab':
                    $url = "https://oauth2:{$token}@gitlab.com/{$this->repository->repository}.git";
                    break;
            }
        } else {
            $url = $this->repository;
            // TODO - we can alllow for just ssh keys later
        }

        return "$loadSshKeyCommand git clone $url $this->clonePath --depth=1 --branch=$this->branch";
    }

    /**
     * @return string
     */
    protected function getPrettierCommand()
    {
        $directories = $this->getEligibleDirectories();
        $ignoreDirectories = $this->getIgnoredDirectories();
        $paths = "find . {$this->getEligibleFileTypes()}".($directories ? '| '.$directories : null).($ignoreDirectories ? ' | '.$ignoreDirectories : null);
        $this->prettierOutput = ". ~/.nvm/nvm.sh && cd $this->clonePath && ($paths | xargs prettier --config-precedence prefer-file {$this->getCommandLineOptions()} --write)";
    }


    /**
     * @return string
     */
    protected function getPatchBranchName()
    {
        $patchBranch = $this->branch;

        if ($this->repository->analysis_setting !== Repository::DIRECT) {
            $patchBranch = RepositoryService::STYLE_BRANCH.$this->repositoryPatch->id;
            $this->runCommand("cd $this->clonePath && git checkout -b $patchBranch");
        }

        return $patchBranch;
    }

    /**
     * @param $patchBranch
     */
    protected function commitChanges($patchBranch)
    {
        $this->runCommand("cd $this->clonePath && git config user.name \"".RepositoryService::STYLE_BOT."\" && git config user.email \"bot@stylejs.io\" && git add -A && git commit -m \"StyleJS fixes ".($this->repository->no_ci ? ' [skip ci]' : null).'"');
        $this->runCommand("cd $this->clonePath && git push origin $patchBranch");
    }

    protected function removeCloneDirectory()
    {
        $this->runCommand("rm -rf $this->clonePath");
    }

    /**
     * @return mixed
     */
    protected function getIgnoredDirectories()
    {
        $tempIgnoreDirectories = $this->repository->ignore_directories;

        return collect($tempIgnoreDirectories ? explode("\n", $tempIgnoreDirectories) : ['**'])->map(function ($value) {
            return preg_replace('/\r/', '', $value);
        })->filter()->sortBy(function ($value) {
            return strlen($value);
        })->map(function ($value) {
            return 'grep -v '.$value;
        })->implode(' | ');
    }

    /**
     * @return mixed
     */
    protected function getEligibleDirectories()
    {
        $includedDirectories = $this->repository->include_directories;

        return collect(! empty($includedDirectories) ? explode("\n", $includedDirectories) : ['**'])->map(function ($value) {
            return preg_replace('/\r/', '', $value);
        })->filter(function ($value) {
            return $value !== '*';
        })->sortBy(function ($value) {
            return strlen($value);
        })->map(function ($value) {
            return 'grep '.$value;
        })->implode(' | ');
    }

    /**
     * @return string
     */
    protected function getEligibleFileTypes()
    {
        $fileTypes = collect($this->repository->file_types);

        if ($fileTypes->isEmpty()) {
            $fileTypes = '*';
        } else {
            $fileTypes = $fileTypes->map(function ($value, $index) {
                if ($index === 0) {
                    return "-name \"*.$value\"";
                } else {
                    return "-o -name \"*.$value\"";
                }
            })->implode(' ');
        }

        return $fileTypes;
    }

    /**
     * @return string
     */
    protected function getCommandLineOptions()
    {
        return collect($this->repository->cli_options)->map(function ($value, $option) {
            if ($value === true) {
                $value = null;
            }
            return trim("--$option $value");
        })->implode(' ');
    }

    protected function setupNodePath()
    {
        putenv('PATH='.config('node.path'));
    }

    /**
     * @param $command
     * @param bool $removeFirstOutput
     * @return null|string|string[]
     */
    protected function runCommand($command, $removeFirstOutput = false)
    {
        $process = new Process($command);

        $process->setTimeout(600);
        $process->run();

        if (! $process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $output = trim($process->getOutput());

        if ($removeFirstOutput) {
            $output = preg_replace('/^.+\n/', '', $output);
        }

        $output = $this->filterLog($output);

        if (! empty($output)) {
            $this->output[] = $output;
        }

        return $output;
    }

    /**
     * @param $log
     * @return null|string|string[]
     */
    protected function filterLog($log)
    {
        return preg_replace('/.*(All identities removed|Identity added).*\n/', '', $log);
    }

    /**
     * @param ProcessFailedException $e
     */
    protected function captureError(ProcessFailedException $e)
    {
        $status = 'error';
        $message = $e->getMessage();

        if (str_contains($message, 'nothing to commit')) {
            $status = 'passed';
        } elseif (str_contains($message, ['Output'])) {
            $this->prettierOutput = substr($message, strpos($message, 'Output'));
        } else {
            app('sentry')->captureException($e);
        }

        $this->repositoryPatch->update([
            'status'       => $status,
            'branch'       => $this->branch,
            'runtime'      => $this->calculateRunningTime(),
            'patch_branch' => isset($patchBranch) ? $patchBranch : null,
            'log'          => ! empty($this->prettierOutput) ? $this->filterLog($this->prettierOutput) : 'We had an error, please contact support if this continues.',
        ]);

        $this->repositoryService->updateCommitStatus($this->repositoryPatch, $status === 'passed' ? 'success' : 'error');

        if (config('app.env') === 'local') {
            dd($e->getMessage());
        }
    }

    /**
     * @return mixed
     */
    protected function calculateRunningTime()
    {
        return microtime(true) - $this->startTime;
    }
}
