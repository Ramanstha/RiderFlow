<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RiderAttendanceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'rider' => new RiderResource($this->whenLoaded('rider')),
            'date' => $this->date->toDateString(),
            'status' => $this->status->value,
            'check_in' => $this->check_in,
            'check_out' => $this->check_out,
            'remarks' => $this->remarks,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
