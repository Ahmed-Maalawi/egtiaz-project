<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceProviderResource extends JsonResource
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
            'name'                      => $this->getTranslation('name', $locale),
            'description'               => strip_tags($this->getTranslation('description', $locale)),
            'category'                  => new CategoryResource($this->whenLoaded('category')),
            'image'                     => $this->image ? asset('storage/' . $this->image) : null,
            'normal_discount'           => $this->normal_percent,
            // 'silver_discount'           => $this->silver_percent,
            // 'golden_discount'           => $this->golden_percent,
            'open_at'                   => isset($this->options['open_at'])
                ? Carbon::createFromFormat('H:i', $this->options['open_at'])->format('g:i A')
                : null,

            'close_at'                  => isset($this->options['close_at'])
                ? Carbon::createFromFormat('H:i', $this->options['close_at'])->format('g:i A')
                : null,

            'services'                  => ServiceResource::collection($this->whenLoaded('services')),
            'users'                     => UserResource::collection($this->whenLoaded('users')),
            'reviews'                   => ReviewResource::collection($this->whenLoaded('reviews')),
            'reviews_count'             => $this->whenCounted('reviews', $this->reviews_count),
            'is_liked'                  => $this->is_liked,
            'branches'                  => ServiceProviderBranchResource::collection($this->whenLoaded('serviceProviderBranches')),
            'nearest_branch'           => new ServiceProviderBranchResource($this->whenLoaded('nearestServiceProviderBranch')),
        ];
    }
}
