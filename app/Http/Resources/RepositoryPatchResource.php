<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RepositoryPatchResource extends JsonResource
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
            'id' => $this->id,
            'sha' => $this->sha,
            'log' => $this->log,
            'branch' => $this->branch,
            'status' => $this->status,
            'run_date' => $this->created_at,
            'repository' => $this->repository_id,
            'patch_branch' => $this->patch_branch,
            'runtime' => round($this->runtime, 2),
        ];
    }
}
