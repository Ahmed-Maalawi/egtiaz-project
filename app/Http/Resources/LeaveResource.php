<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeaveResource extends JsonResource
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
            'employee'      => $this->whenLoaded('employee'),
            'start_date'    => Carbon::parse($this->start_date)->format("Y-m-d"),
            'end_date'      => Carbon::parse($this->end_date)->format("Y-m-d"),
            'days_taken'    => $this->days_taken,
            'reason'        => $this->reason,
            'notes'         => $this->notes,
            'status'        => $this->status,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
        ];
    }
}

