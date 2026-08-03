<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateClientProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $companyId = Auth::guard('client')->id();

        return [
            // Clients may correct their own contact details; the company name
            // and status stay under admin control.
            'email' => ['required', 'email', 'max:255', Rule::unique('companies', 'email')->ignore($companyId)],
            'phone' => ['required', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:255'],
            'contact_person' => ['required', 'string', 'max:255'],
        ];
    }
}
