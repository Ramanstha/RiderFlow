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
            'delivered' => ['required', 'integer', 'min:0', 'lte:assigned'],
            'failed' => ['required', 'integer', 'min:0', 'lte:assigned'],
            'returned' => ['required', 'integer', 'min:0', 'lte:assigned'],
        ];
    }

    public function messages(): array
    {
        return [
            'delivered.lte' => 'Delivered cannot be more than the number assigned.',
            'failed.lte' => 'Failed cannot be more than the number assigned.',
            'returned.lte' => 'Returned cannot be more than the number assigned.',
            'date.before_or_equal' => 'You cannot log parcel counts for a future date.',
        ];
    }
}
