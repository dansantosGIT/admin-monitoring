<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && in_array(auth()->user()->role ?? '', ['admin', 'super-admin'], true);
    }

    public function rules(): array
    {
        $vehicleId = $this->route('vehicle')?->id;

        return [
            'call_sign' => ['required', 'string', 'max:100'],
            'vehicle_model' => ['required', 'string', 'max:100'],
            'brand' => ['required', 'string', 'max:100'],
            'plate_number' => ['required', 'string', 'max:30', Rule::unique('vehicles', 'plate_number')->ignore($vehicleId)],
            'year' => ['nullable', 'integer', 'min:1900', 'max:' . (now()->year + 1)],
            'team' => ['nullable', 'string', 'max:100'],
            'drive_link' => ['nullable', 'url', 'max:500'],
            'driver_id' => ['nullable', 'exists:employees,id'],
            'status' => ['nullable', Rule::in(['active', 'idle', 'offline'])],
            'last_known_location' => ['nullable', 'string', 'max:255'],
        ];
    }
}