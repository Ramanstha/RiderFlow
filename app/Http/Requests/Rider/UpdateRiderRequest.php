<?php

namespace App\Http\Requests\Rider;

use App\Enums\RiderStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateRiderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $riderId = $this->route('rider')?->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('riders', 'email')->ignore($riderId)],
            'phone' => ['required', 'string', 'max:30'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'pay' => ['nullable', 'numeric', 'min:0', 'max:99999999.99'],
            'license_number' => ['required', 'string', 'max:255', Rule::unique('riders', 'license_number')->ignore($riderId)],
            'status' => ['required', Rule::enum(RiderStatus::class)],
            // Leave blank to keep the rider's existing portal password.
            'password' => ['nullable', 'string', 'confirmed', Password::defaults()],
        ];
    }
}
