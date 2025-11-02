<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceProviderBranchResource extends JsonResource
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
            'id'                            => $this->id,       
            'address'                       => $this->getTranslation('address', $locale),
            'city'                          => new CityResource($this->whenLoaded('city')),
            'phone'                         => $this->phone,
            'phone_alt'                     => $this->phone_alt,
            'location'                      => "https://maps.google.com/maps?q={$this->latitude},{$this->longitude}",
            'latitude'                      => $this->latitude,
            'longitude'                     => $this->longitude,
            'distance'                      => !is_null($this->distance) ? round($this->distance, 2) . " km" : null,
            'service_provider'              => new ServiceProviderResource($this->whenLoaded('serviceProvider')),
        ];
    }
}
