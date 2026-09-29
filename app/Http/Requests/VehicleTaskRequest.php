<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VehicleTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && in_array(auth()->user()->role ?? '', ['admin', 'super-admin'], true);
    }

    public function rules(): array
    {
        return [
            'task_option_id' => ['nullable', 'exists:task_options,id'],
            'task_name' => ['required', 'string', 'max:150'],
            'frequency' => ['nullable', 'string', 'max:100'],
            'responsible_person_id' => ['nullable', 'exists:employees,id'],
            'responsible_name' => ['nullable', 'string', 'max:150'],
            'status' => ['required', Rule::in(['pending', 'scheduled', 'ongoing', 'completed', 'cancelled'])],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}