<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RiderPerformanceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'rider' => new RiderResource($this->whenLoaded('rider')),
            'period' => $this->period->format('Y-m'),
            'score' => $this->score,
            'deliveries_completed' => $this->deliveries_completed,
            'deliveries_failed' => $this->deliveries_failed,
            'attendance_rate' => $this->attendance_rate,
            'notes' => $this->notes,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
