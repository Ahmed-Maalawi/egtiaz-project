<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class StageResource extends JsonResource
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
            'description'               => $this->getTranslation('description', $locale),
            'stage'                     => $this->whenLoaded('stage', new StageResource($this->stage)),
            'status'                    => $this->status,
            'price'                     => $this->price,
            'cost'                      => $this->cost,
            'estimated_time_in_days'    => $this->estimated_time_in_days,
            'options'                   => $this->options,
            'image_url'                 => $this->image_url,
            'file_url'                  => $this->file_url,
            'updated_at'                => $this->updated_at,
            'created_at'                => $this->created_at,
        ];
    }
}
