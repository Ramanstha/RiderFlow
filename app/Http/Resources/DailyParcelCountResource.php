<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DailyParcelCountResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'rider' => new RiderResource($this->whenLoaded('rider')),
            'date' => $this->date->toDateString(),
            'assigned' => $this->assigned,
            'delivered' => $this->delivered,
            'success_rate' => $this->success_rate,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
