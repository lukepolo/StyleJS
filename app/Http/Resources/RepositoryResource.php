<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RepositoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' =>$this->id,
            'hash' => $this->hash,
            'no_ci' => $this->no_ci,
            'branches' => $this->branches,
            'on_demand' => $this->on_demand,
            'last_patch' => $this->last_patch,
            'file_types' => $this->file_types,
            'repository' => $this->repository,
            'cli_options' => $this->cli_options,
            'default_branch' => $this->default_branch,
            'analysis_setting' => $this->analysis_setting,
            'user_provider_id' => $this->user_provider_id,
            'ignore_directories' => $this->ignore_directories,
            'include_directories' => $this->include_directories,
        ];
    }
}
