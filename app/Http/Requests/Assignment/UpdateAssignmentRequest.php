<?php

namespace App\Http\Requests\Assignment;

use App\Enums\AssignmentShift;
use App\Enums\AssignmentStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAssignmentRequest extends FormRequest
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
            'zone' => ['nullable', 'string', 'max:255'],
            'route' => ['nullable', 'string', 'max:255'],
            'shift' => ['nullable', Rule::enum(AssignmentShift::class)],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'status' => ['required', Rule::enum(AssignmentStatus::class)],
        ];
    }
}
