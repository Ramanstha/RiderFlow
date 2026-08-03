<?php

namespace App\Http\Requests\Performance;

use Illuminate\Foundation\Http\FormRequest;

class StorePerformanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'rider_id' => ['required', 'integer', 'exists:riders,id'],
            'period' => ['required', 'date'],
            'score' => ['required', 'integer', 'min:0', 'max:100'],
            'deliveries_completed' => ['nullable', 'integer', 'min:0'],
            'deliveries_failed' => ['nullable', 'integer', 'min:0'],
            'attendance_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
