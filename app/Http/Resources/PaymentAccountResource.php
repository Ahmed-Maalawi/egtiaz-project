<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

class PaymentAccountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $locale = App::getLocale();

        return [
            'id'            => $this->id,
            'name'          => $this->getTranslation('name', $locale),
            'description'   => $this->getTranslation('description', $locale),
            'users'         => $this->whenLoaded('users'),
            'transactions'  => $this->whenLoaded('transactions'),
            'balance'       => $this->balance,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
        ];
    }
}

