<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CardResource extends JsonResource
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
            'name'                  =>$this->getTranslation('name',$locale),
            'payment_limits'        =>$this->payment_limit_from .' - '.$this->payment_limit_to,
            'orders_limit'          =>$this->orders_count_from .' - '. $this->orders_count_to,
        ];
    }
}
