<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class EmployeeFileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $locale = app()->getLocale();

        return [
            'id'                        => $this->id,
            'employee_id'               => $this->employee_id,
            'file_url'                  => $this->FileUrl,
            'updated_at'                => $this->updated_at,
            'created_at'                => $this->created_at
        ];
    }
}
