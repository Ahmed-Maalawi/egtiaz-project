<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
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
            'id'            => $this->id,
            'name'          => $this->getTranslation('name', $locale),
            'description'   => $this->getTranslation('description', $locale),
            'status'        => $this->status,
            'banner_image'  => $this->banner_image_url,
            'image_url'     => $this->image_url,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
        ];
    }
}
