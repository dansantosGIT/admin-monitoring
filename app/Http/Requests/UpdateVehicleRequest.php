<?php

namespace App\Http\Requests;

use App\Models\Vehicle;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('vehicle')) ?? false;
    }

    public function rules(): array
    {
        /** @var Vehicle $vehicle */
        $vehicle = $this->route('vehicle');

        return [
            'call_sign' => ['required', 'string', 'max:100'],
            'vehicle_type' => ['required', Rule::in(['Ambulance', 'Rescue Truck', 'Fire Truck', 'Van', 'Motorcycle', 'Other'])],
            'vehicle_model' => ['nullable', 'string', 'max:100'],
            'brand' => ['nullable', 'string', 'max:100'],
            'plate_number' => ['required', 'string', 'max:30', Rule::unique('vehicles', 'plate_number')->ignore($vehicle)],
            'year' => ['nullable', 'integer', 'min:1900', 'max:' . (now()->year + 1)],
            'team' => ['nullable', 'string', 'max:100'],
            'status' => ['required', Rule::in(['active', 'idle', 'offline'])],
            'drive_link' => ['nullable', 'url', 'max:500'],
            'remarks' => ['nullable', 'string', 'max:2000'],
        ];
    }
}