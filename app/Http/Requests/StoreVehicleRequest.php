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

    private function vehicleRules(): array
    {
        return [
            'call_sign' => ['required', 'string', 'max:100'],
            'vehicle_type' => ['required', Rule::in(['Ambulance', 'Rescue Truck', 'Fire Truck', 'Van', 'Motorcycle', 'Other'])],
            'vehicle_model' => ['nullable', 'string', 'max:100'],
            'brand' => ['nullable', 'string', 'max:100'],
            'plate_number' => ['required', 'string', 'max:30', 'unique:vehicles,plate_number'],
            'year' => ['nullable', 'integer', 'min:1900', 'max:' . (now()->year + 1)],
            'team' => ['nullable', 'string', 'max:100'],
            'status' => ['required', Rule::in(['active', 'idle', 'offline'])],
            'drive_link' => ['nullable', 'url', 'max:500'],
            'remarks' => ['nullable', 'string', 'max:2000'],
        ];
    }
}