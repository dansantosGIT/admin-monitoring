<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'first_name' => $this->normalizeName($this->input('first_name')),
            'last_name' => $this->normalizeName($this->input('last_name')),
            'middle_name' => $this->normalizeName($this->input('middle_name')),
            'suffix' => $this->normalizeName($this->input('suffix')),
            'maiden_name' => $this->normalizeName($this->input('maiden_name')),
            'birthdate' => $this->input('birthdate') ?: null,
            'mobile' => $this->normalizeDigits($this->input('mobile')),
            'email' => $this->normalizeText($this->input('email')),
            'present_zip' => $this->normalizeDigits($this->input('present_zip')),
            'sss' => $this->normalizeSss($this->input('sss')),
            'tin' => $this->normalizeTin($this->input('tin')),
            'philhealth' => $this->normalizePhilhealth($this->input('philhealth')),
            'pagibig' => $this->normalizePagibig($this->input('pagibig')),
            'gsis' => $this->normalizeGsis($this->input('gsis')),
        ]);
    }

    public function rules(): array
    {
        $minBirthdate = now()->subYears(18)->toDateString();

        return [
            'first_name' => ['required', 'string', 'max:150', 'regex:/^[\pL\s\'-]+$/u'],
            'last_name' => ['required', 'string', 'max:150', 'regex:/^[\pL\s\'-]+$/u'],
            'middle_name' => ['nullable', 'string', 'max:50', 'regex:/^[\pL\s\'-]+$/u'],
            'suffix' => ['nullable', 'string', 'max:20', 'regex:/^[\pL\s\'-]+$/u'],
            'maiden_name' => ['nullable', 'string', 'max:150', 'regex:/^[\pL\s\'-]+$/u'],
            'sex' => ['nullable', 'string', 'max:10'],
            'civil_status' => ['nullable', 'string', 'max:20'],
            'birthdate' => ['nullable', 'date', 'before_or_equal:' . $minBirthdate],
            'place_of_birth' => ['nullable', 'string', 'max:200'],
            'mobile' => ['nullable', 'regex:/^09\d{9}$/'],
            'email' => ['nullable', 'email', 'max:200'],
            'position' => ['required', 'string', 'max:200'],
            'department' => ['nullable', 'string', 'max:200'],
            'section' => ['nullable', 'string', 'max:200'],
            'employment_type' => ['required', 'in:JO,Permanent'],
            'date_hired' => ['required', 'date'],
            'monthly_salary' => ['nullable', 'numeric'],
            'sss' => ['nullable', 'regex:/^\d{2}-\d{7}-\d$/'],
            'gsis' => ['nullable', 'regex:/^(?=.*\d)[\d-]+$/'],
            'philhealth' => ['nullable', 'regex:/^\d{2}-\d{9}-\d$/'],
            'pagibig' => ['nullable', 'regex:/^\d{4}-\d{4}-\d{4}$/'],
            'tin' => ['nullable', 'regex:/^\d{3}-\d{3}-\d{3}$/'],
            'present_zip' => ['nullable', 'regex:/^\d{4}$/'],
            'remarks' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:4096'],
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'First name is required.',
            'first_name.regex' => 'First name may contain letters, spaces, hyphens, and apostrophes only.',
            'last_name.required' => 'Last name is required.',
            'last_name.regex' => 'Last name may contain letters, spaces, hyphens, and apostrophes only.',
            'middle_name.regex' => 'Middle name may contain letters, spaces, hyphens, and apostrophes only.',
            'suffix.regex' => 'Suffix may contain letters, spaces, hyphens, and apostrophes only.',
            'maiden_name.regex' => 'Maiden name may contain letters, spaces, hyphens, and apostrophes only.',
            'birthdate.date' => 'Birthdate must be a valid date.',
            'birthdate.before_or_equal' => 'Birthdate must be at least 18 years ago and cannot be in the future.',
            'mobile.regex' => 'Mobile number must be 11 digits starting with 09.',
            'email.email' => 'Please enter a valid email address.',
            'position.required' => 'Position / Title is required.',
            'employment_type.required' => 'Employment type is required.',
            'date_hired.required' => 'Date hired is required.',
            'sss.regex' => 'SSS number must follow the format XX-XXXXXXX-X.',
            'gsis.regex' => 'GSIS number may contain digits and hyphens only.',
            'philhealth.regex' => 'PhilHealth number must follow the format XX-XXXXXXXXX-X.',
            'pagibig.regex' => 'Pag-IBIG number must follow the format XXXX-XXXX-XXXX.',
            'tin.regex' => 'TIN must follow the format XXX-XXX-XXX.',
            'present_zip.regex' => 'ZIP code must be 4 digits.',
        ];
    }

    private function normalizeText(mixed $value): ?string
    {
        $text = trim((string) $value);

        return $text === '' ? null : $text;
    }

    private function normalizeName(mixed $value): ?string
    {
        $text = trim((string) $value);

        if ($text === '') {
            return null;
        }

        $text = preg_replace("/[^\pL\s\'-]+/u", '', $text) ?? '';
        $text = preg_replace('/\s+/u', ' ', $text) ?? '';

        return $text === '' ? null : $text;
    }

    private function normalizeDigits(mixed $value): ?string
    {
        $digits = preg_replace('/\D+/', '', (string) $value) ?? '';

        return $digits === '' ? null : $digits;
    }

    private function normalizeSss(mixed $value): ?string
    {
        $digits = preg_replace('/\D+/', '', (string) $value) ?? '';

        if ($digits === '') {
            return null;
        }

        return strlen($digits) === 10
            ? substr($digits, 0, 2) . '-' . substr($digits, 2, 7) . '-' . substr($digits, 9, 1)
            : $digits;
    }

    private function normalizeTin(mixed $value): ?string
    {
        $digits = preg_replace('/\D+/', '', (string) $value) ?? '';

        if ($digits === '') {
            return null;
        }

        return strlen($digits) === 9
            ? substr($digits, 0, 3) . '-' . substr($digits, 3, 3) . '-' . substr($digits, 6, 3)
            : $digits;
    }

    private function normalizePhilhealth(mixed $value): ?string
    {
        $digits = preg_replace('/\D+/', '', (string) $value) ?? '';

        if ($digits === '') {
            return null;
        }

        return strlen($digits) === 12
            ? substr($digits, 0, 2) . '-' . substr($digits, 2, 9) . '-' . substr($digits, 11, 1)
            : $digits;
    }

    private function normalizePagibig(mixed $value): ?string
    {
        $digits = preg_replace('/\D+/', '', (string) $value) ?? '';

        if ($digits === '') {
            return null;
        }

        return strlen($digits) === 12
            ? substr($digits, 0, 4) . '-' . substr($digits, 4, 4) . '-' . substr($digits, 8, 4)
            : $digits;
    }

    private function normalizeGsis(mixed $value): ?string
    {
        $text = trim((string) $value);

        if ($text === '') {
            return null;
        }

        $text = preg_replace('/[^\d-]+/', '', $text) ?? '';
        $text = preg_replace('/-+/', '-', $text) ?? '';

        return $text === '' ? null : $text;
    }
}