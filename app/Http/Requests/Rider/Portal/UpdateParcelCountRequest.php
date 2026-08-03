<?php

namespace App\Http\Requests\Rider\Portal;

use App\Models\DailyParcelCount;
use Illuminate\Foundation\Http\FormRequest;

class UpdateParcelCountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // A client may assign a load for an upcoming day, so a client-assigned
            // row is not held to the "not in the future" rule the rider's own
            // submissions are.
            'date' => $this->isClientAssigned()
                ? ['required', 'date']
                : ['required', 'date', 'before_or_equal:today'],
            'assigned' => ['required', 'integer', 'min:0'],
            'delivered' => ['required', 'integer', 'min:0', 'lte:assigned'],
            'failed' => ['required', 'integer', 'min:0', 'lte:assigned'],
            'returned' => ['required', 'integer', 'min:0', 'lte:assigned'],
        ];
    }

    /**
     * The assigned figure and date belong to the client that set them, so
     * whatever the rider posts for those is replaced with the stored values.
     */
    protected function prepareForValidation(): void
    {
        if ($this->isClientAssigned()) {
            $assignment = $this->parcelCount();

            $this->merge([
                'assigned' => $assignment->assigned,
                'date' => $assignment->date->toDateString(),
            ]);
        }
    }

    protected function isClientAssigned(): bool
    {
        return (bool) $this->parcelCount()?->isClientAssigned();
    }

    protected function parcelCount(): ?DailyParcelCount
    {
        $parcel = $this->route('parcel');

        return $parcel instanceof DailyParcelCount ? $parcel : null;
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
