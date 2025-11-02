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
            'image'                     => $this->image ? asset('storage/' . $this->image) : null,
            'fcm_token'                 => $this->fcm_token,
            'player_id'                 => $this->player_id,
            'reviews'                   => ReviewResource::collection($this->whenLoaded('reviews')),
            'service_providers'         => ServiceProviderResource::collection($this->whenLoaded('serviceProviders')),
            'referral_code'             => $this->referral_code,
            'refers_count'              => $this->whenLoaded('refers') ? $this->refers->count() : 0,
            'qr_code'                   => $this->qr_code,
            'card_code'                 => $this->card_code , 
            'city'                      => new CityResource($this->whenLoaded('city')),
            'is_cashier'                => $this->provider_cashier_id ? true : false,
            'is_phone_verified'         => $this->phone_verified_at ? true : false ,
        ];
    }
}
