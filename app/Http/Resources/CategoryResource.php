<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'id'                        =>$this->id,
            'name'                      =>$this->getTranslation('name',$locale),
            'description'               =>$this->getTranslation('description',$locale),
            'image'                     =>$this->image ? asset('storage/'.$this->image) : null,
            'providers'                 =>ServiceProviderResource::collection($this->whenLoaded('serviceProviders')),
            'main-category'             =>new MainCategoryResource($this->whenLoaded('mainCategory')),
        ];
    }
}
