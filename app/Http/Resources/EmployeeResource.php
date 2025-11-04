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
        $locale = app()->getLocale();

        return [
            'id'                    => $this->id,
            'name'                  => $this->name,
            'email'                 => $this->email,
            'address'               => $this->address,
            'passport_number'       => $this->passport_number,
            'expired_date'          => Carbon::parse($this->expired_date)->format('Y-m-d'),
            'salary'                => $this->salary,
            'status'                => $this->status,
            'phone'                 => $this->phone,
            'image_url'             => $this->image_url,
            'passport_image_url'    => $this->image_url,
            'created_at'            => $this->created_at,
            'updated_at'            => $this->updated_at,
        ];
    }
}

