<?php

namespace App\Http\Requests\Rider\Portal;

use Illuminate\Foundation\Http\FormRequest;

class StoreParcelCountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Riders log their own numbers, so rider_id is never accepted from input.
            'date' => ['required', 'date', 'before_or_equal:today'],
            'assigned' => ['required', 'integer', 'min:0'],
            // The day can be opened with just the start reading; delivered and
            // the end reading follow once the round is done.
            'delivered' => ['nullable', 'integer', 'min:0', 'lte:assigned'],
            'start_km' => ['sometimes', 'integer', 'min:0'],
            'end_km' => ['nullable', 'integer', 'min:0', 'gte:start_km'],
        ];
    }

    public function messages(): array
    {
        return [
            'delivered.lte' => 'Delivered cannot be more than the number assigned.',
            'end_km.gte' => 'End KM cannot be less than Start KM.',
            'date.before_or_equal' => 'You cannot log parcel counts for a future date.',
        ];
    }
}
