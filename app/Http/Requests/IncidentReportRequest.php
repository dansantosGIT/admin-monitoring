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
        return [
            'employee_id' => ['required', 'integer', 'exists:employees,id'],
            'department' => ['required', 'string', 'max:150'],
            'incident_type' => ['required', 'in:equipment_damage,equipment_loss,vehicle_incident,other'],
            'item_name' => ['required', 'string', 'max:255'],
            'property_serial_no' => ['nullable', 'string', 'max:255'],
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
}