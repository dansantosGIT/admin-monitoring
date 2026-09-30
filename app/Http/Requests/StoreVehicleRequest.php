<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->can('create', \App\Models\Vehicle::class) ?? false;
    }

    public function rules(): array
    {
        return $this->vehicleRules();
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'plate_number' => strtoupper(trim((string) $this->plate_number)),
            'team' => $this->team === '__new__' ? trim((string) $this->new_team) : $this->team,
        ]);
    }

    private function vehicleRules(): array
    {
        return [
            'call_sign' => ['required', 'string', 'max:100'],
            'vehicle_type' => ['required', Rule::in(['Ambulance', 'Rescue Truck', 'Fire Truck', 'Van', 'Motorcycle', 'Other'])],
            'vehicle_type_other' => ['required_if:vehicle_type,Other', 'nullable', 'string', 'max:100'],
            'vehicle_model' => ['nullable', 'string', 'max:100'],
            'brand' => ['nullable', 'string', 'max:100'],
            'plate_number' => ['required', 'string', 'max:30', 'unique:vehicles,plate_number'],
            'year' => ['nullable', 'integer', 'min:' . (now()->year - 30), 'max:' . now()->year],
            'team' => ['required', 'string', 'max:100'],
            'new_team' => ['nullable', 'string', 'max:100'],
            'driver_id' => ['nullable', 'integer', 'exists:employees,id'],
            'status' => ['required', Rule::in(['active', 'idle', 'offline'])],
            'drive_link' => ['nullable', 'url', 'max:500'],
            'remarks' => ['nullable', 'string', 'max:2000'],
            'next_due_date' => ['nullable', 'date'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
            'remove_photo' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return ['vehicle_type_other.required_if' => 'Please specify the vehicle type.'];
    }
}