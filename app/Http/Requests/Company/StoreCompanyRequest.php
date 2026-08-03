<?php

namespace App\Http\Requests\Company;

use App\Enums\CompanyStatus;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StoreCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:companies,name'],
            'email' => ['nullable', 'email', 'max:255', 'unique:companies,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:255'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            // Optional client portal credentials. The email doubles as the
            // sign-in name, so a password without one would be unusable.
            'password' => ['nullable', 'string', 'confirmed', 'required_with:password_confirmation', Password::defaults()],
            'status' => ['required', Rule::enum(CompanyStatus::class)],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if (filled($this->input('password')) && blank($this->input('email'))) {
                $validator->errors()->add('email', 'An email address is required to give this company portal access.');
            }
        });
    }
}
