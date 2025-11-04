<?php

namespace App\Http\Resources;

use App\Http\Controllers\Dashboard\UserController;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                        => $this->id,
            'name'                      => $this->name,
            'email'                     => $this->email,
            'phone'                     => $this->phone,
            'type'                      => $this->type,
            'salary'                    => $this->salary,
            'image'                     => $this->image ? asset('storage/' . $this->image) : null,
            'fcm_token'                 => $this->fcm_token,
            'moderator_company_id'      => $this->moderator_company_id,
            'email_verified_at'         => $this->email_verified_at,
            'status'                    => $this->status,
            'is_phone_verified'         => $this->phone_verified_at ? true : false,
        ];
    }
}
