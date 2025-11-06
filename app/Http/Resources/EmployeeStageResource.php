<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class EmployeeStageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'id'            => $this->id,
            'employee'      => $this->whenLoaded('employee', new EmployeeResource($this->employee)),
            'stage'         => $this->whenLoaded('stage', new StageResource($this->stage)),
            'status'        => $this->status,
            'done_by'       => $this->whenLoaded('doneBy') ?? null,
            'completed_at'  => Carbon::parse($this->completed_at)->format('Y-m-d H:i'),
            'expired_at'    => Carbon::parse($this->expired_at)->format('Y-m-d H:i'),
            'options'       => $this->options ?? null,
            'files'         => $this->whenLoaded('files'),
            'cost'          => $this->cost ?? null,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
        ];
    }
}
