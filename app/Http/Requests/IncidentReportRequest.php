<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IncidentReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        if ($this->isMethod('post')) {
            return $this->storeRules();
        }

        return $this->updateRules();
    }

    public function messages(): array
    {
        return [
            'required' => 'This field is required.',
            'reported_by_name.min' => 'Staff Name must be at least 2 characters.',
            'reported_by_name.max' => 'Staff Name must not exceed 100 characters.',
            'reported_by_name.regex' => 'Staff Name must contain letters and may include spaces, periods, apostrophes, or hyphens.',
            'employee_id.exists' => 'Selected employee is invalid.',
            'department.in' => 'Please select a valid department.',
            'team.required_if' => 'This field is required.',
            'department_other.required_if' => 'This field is required.',
            'estimated_cost.numeric' => 'Estimated cost must contain numbers only.',
            'estimated_cost.min' => 'Estimated cost must be at least 0.',
            'attachments.array' => 'Attachments must be uploaded as a file list.',
            'attachments.*.file' => 'Each attachment must be a file.',
            'attachments.*.mimes' => 'Attachments must be JPG, PNG, or PDF files.',
            'attachments.*.max' => 'Each attachment must not exceed 5 MB.',
        ];
    }

    private function storeRules(): array
    {
        return [
            'reported_by_name' => ['required', 'string', 'min:2', 'max:100', "regex:/^(?=.*\\pL)[\\pL\\s.'-]+$/u"],
            'employee_id' => ['required', 'integer', 'exists:employees,id'],
            'department' => ['required', 'in:CEDOC,LOGISTICS,PLANNING,ADMIN & TRAINING,OPERATIONS,VOLUNTEER,OTHERS'],
            'team' => ['nullable', 'required_if:department,OPERATIONS', 'in:Team Alpha,Team Bravo,Team Charlie,Team Delta'],
            'department_other' => ['nullable', 'required_if:department,OTHERS', 'string', 'max:255'],
            'incident_type' => ['required', 'in:equipment_damage,equipment_loss,vehicle_incident,other'],
            'item_name' => ['required', 'string', 'max:255'],
            'property_number' => ['nullable', 'string', 'max:255'],
            'serial_number' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'location' => ['required', 'string', 'max:255'],
            'date_of_incident' => ['required', 'date'],
            'severity' => ['required', 'in:minor,major,critical'],
            'estimated_cost' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:pending,under_investigation,resolved,closed'],
            'action_taken' => ['nullable', 'string'],
            'remarks' => ['nullable', 'string'],
            'attachments' => ['nullable', 'array'],
            'attachments.*' => ['file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->isMethod('post')) {
            $this->merge(['reported_by_name' => trim((string) $this->input('reported_by_name'))]);
        }
    }

    private function updateRules(): array
    {
        return [
            'estimated_cost' => ['nullable', 'numeric', 'min:0'],
            'severity' => ['required', 'in:minor,major,critical'],
            'status' => ['required', 'in:pending,under_investigation,resolved,closed'],
            'action_taken' => ['nullable', 'string'],
            'remarks' => ['nullable', 'string'],
            'attachments' => ['nullable', 'array'],
            'attachments.*' => ['file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ];
    }
}