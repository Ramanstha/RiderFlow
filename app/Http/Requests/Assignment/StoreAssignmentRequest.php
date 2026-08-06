<?php

namespace App\Http\Requests\Assignment;

use App\Enums\AssignmentType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'rider_id' => ['required', 'integer', 'exists:riders,id'],
            'company_id' => ['required', 'integer', 'exists:companies,id'],
            'type' => ['required', Rule::enum(AssignmentType::class)],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ];
    }
}
