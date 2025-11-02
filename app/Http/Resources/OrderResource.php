<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $result = [
            'id'                            => $this->id,
            'user'                          => new UserResource($this->whenLoaded('user')),
            'service_provider'              => new ServiceProviderResource($this->whenLoaded('serviceProvider')),
            'status'                        => $this->status,
            'sum'                           => $this->sum,
            'after_discount'                => $this->sum - $this->applicable_discount,
            'applicable_discount'           => $this->applicable_discount,
            'date'                          => $this->created_at->format('Y-m-d'),
            'time'                          => $this->created_at->format('H:i:s'),
            'status'                        => $this->status ,
            'reference_no'                  => $this->uuid,
            'service_provider_branch'       => new ServiceProviderBranchResource($this->whenLoaded('serviceProviderBranch')),
            'cashier'                       => $this->cashier_id ?  new UserResource($this->whenLoaded('cashier')) : null,
        ];

        return $result;
    }
}