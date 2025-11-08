<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'id'                    => $this->id,
            'name'                  => $this->name,
            'email'                 => $this->email,
            'address'               => $this->address,
            'passport_number'       => $this->passport_number,
            'expired_date'          => Carbon::parse($this->expired_date)->format('Y-m-d'),
            'salary'                => $this->salary,
            'status'                => $this->status,
            'iqamaType'             => $this->whenLoaded('iqamaType'),
            'upcomingStage'         => $this->whenLoaded('upcomingStage'),
            'company'               => $this->whenLoaded('company', new CompanyResource($this->company)) ?? null,
            'employeeStages'        => $this->whenLoaded('employeeStages'),
            'salaries'              => $this->whenLoaded('salaries'),
            'leaves'                => $this->whenLoaded('leaves', new LeaveResource($this->leaves)) ?? null,
            'eos'                   => $this->whenLoaded('eos', new EndOfServiceResource($this->eos)),
            'phone'                 => $this->phone,
            'image_url'             => $this->image_url,
            'passport_image_url'    => $this->image_url,
            'created_at'            => $this->created_at,
            'updated_at'            => $this->updated_at,
        ];
    }
}

