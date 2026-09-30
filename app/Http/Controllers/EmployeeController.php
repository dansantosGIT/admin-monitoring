<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeRequest;
use App\Models\DtrEntry;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::orderBy('last_name')->paginate(15);
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(EmployeeRequest $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:150',
            'last_name' => 'required|string|max:150',
            'middle_name' => 'nullable|string|max:50',
            'suffix' => 'nullable|string|max:20',
            'maiden_name' => 'nullable|string|max:150',
            'sex' => 'nullable|string|max:10',
            'civil_status' => 'nullable|string|max:20',
            'birthdate' => 'nullable|date',
            'place_of_birth' => 'nullable|string|max:200',
            'mobile' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:200',
            'employee_number' => 'nullable|string|max:50',
            'position' => 'nullable|string|max:200',
            'department' => 'nullable|string|max:200',
            'section' => 'nullable|string|max:200',
            'employment_type' => 'nullable|in:JO,Permanent',
            'shift_type' => 'nullable|in:morning,mid,night',
            'shift_start' => 'nullable|date_format:H:i',
            'shift_end' => 'nullable|date_format:H:i',
            'date_hired' => 'nullable|date',
            'monthly_salary' => 'nullable|numeric',
            'sss' => 'nullable|string|max:50',
            'gsis' => 'nullable|string|max:50',
            'philhealth' => 'nullable|string|max:50',
            'pagibig' => 'nullable|string|max:50',
            'tin' => 'nullable|string|max:50',
            'remarks' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
        ]);

        // Structured groups: addresses and simple spouse/parents arrays
        $present = [
            'address' => $request->input('present_address'),
            'barangay' => $request->input('present_barangay'),
            'city' => $request->input('present_city'),
            'province' => $request->input('present_province'),
            'zip' => $request->input('present_zip'),
        ];
        $permanent = [
            'address' => $request->input('permanent_address'),
            'barangay' => $request->input('permanent_barangay'),
            'city' => $request->input('permanent_city'),
            'province' => $request->input('permanent_province'),
            'zip' => $request->input('permanent_zip'),
        ];

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('employees', 'public');
            $data['photo_path'] = $path;
        }

        $employee = Employee::create(array_merge($data, [
            'present_address' => $present,
            'permanent_address' => $permanent,
            'spouse' => [
                'name' => $request->input('spouse_name'),
                'occupation' => $request->input('spouse_occupation'),
                'employer' => $request->input('spouse_employer'),
                'contact' => $request->input('spouse_contact'),
            ],
            'parents' => [
                'father' => $request->input('father_name'),
                'mother' => $request->input('mother_name'),
            ],
            'education' => [
                'elementary' => $request->input('elem_school'),
                'secondary' => $request->input('hs_school'),
                'college' => $request->input('college_school'),
                'graduate' => $request->input('grad_school'),
            ],
        ]));

        return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
    }

    public function show(Request $request, Employee $employee)
    {
        $month = $request->input('month', now()->format('Y-m'));

        try {
            $calendarMonth = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        } catch (\Throwable) {
            $calendarMonth = now()->startOfMonth();
            $month = $calendarMonth->format('Y-m');
        }

        $calendarEnd = $calendarMonth->copy()->endOfMonth();
        $schedule = $employee->attendanceSchedules()
            ->where('effective_from', '<=', $calendarEnd->toDateString())
            ->where(function ($query) use ($calendarMonth) {
                $query->whereNull('effective_to')->orWhere('effective_to', '>=', $calendarMonth->toDateString());
            })
            ->orderByDesc('effective_from')
            ->first();
        $workingDays = $schedule?->working_days ?: ['mon', 'tue', 'wed', 'thu', 'fri'];

        $entries = DtrEntry::where('employee_id', $employee->id)
            ->whereBetween('work_date', [$calendarMonth->toDateString(), $calendarEnd->toDateString()])
            ->get()
            ->keyBy(fn (DtrEntry $entry) => Carbon::parse((string) $entry->work_date)->format('Y-m-d'));

        $leaveRecords = $employee->leaveRecords()
            ->where('status', 'approved')
            ->where('start_date', '<=', $calendarEnd->toDateString())
            ->where('end_date', '>=', $calendarMonth->toDateString())
            ->get();

        $calendarDays = collect(range(1, $calendarMonth->daysInMonth))->map(function (int $day) use ($calendarMonth, $entries, $leaveRecords, $workingDays) {
            $date = $calendarMonth->copy()->day($day);
            $dateKey = $date->format('Y-m-d');
            $entry = $entries->get($dateKey);
            $leave = $leaveRecords->first(fn ($record) => $date->between($record->start_date, $record->end_date));
            $weekday = strtolower($date->format('D'));

            $status = $entry?->status;
            if (! $status && $leave) {
                $status = 'leave';
            } elseif (! $status && ! in_array($weekday, $workingDays, true)) {
                $status = 'rest_day';
            } elseif (! $status) {
                $status = 'not_recorded';
            }

            return (object) [
                'date' => $date,
                'entry' => $entry,
                'leave' => $leave,
                'status' => $status,
            ];
        });

        $calendarCounts = $calendarDays->countBy('status');

        return view('employees.show', compact(
            'employee', 'month', 'calendarMonth', 'calendarDays', 'calendarCounts'
        ))->with('canManage', $this->canManageAttendance());
    }

    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    public function update(EmployeeRequest $request, Employee $employee)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:150',
            'last_name' => 'required|string|max:150',
            'middle_name' => 'nullable|string|max:50',
            'suffix' => 'nullable|string|max:20',
            'maiden_name' => 'nullable|string|max:150',
            'sex' => 'nullable|string|max:10',
            'civil_status' => 'nullable|string|max:20',
            'birthdate' => 'nullable|date',
            'place_of_birth' => 'nullable|string|max:200',
            'mobile' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:200',
            'employee_number' => 'nullable|string|max:50',
            'position' => 'nullable|string|max:200',
            'department' => 'nullable|string|max:200',
            'section' => 'nullable|string|max:200',
            'employment_type' => 'nullable|in:JO,Permanent',
            'shift_type' => 'nullable|in:morning,mid,night',
            'shift_start' => 'nullable|date_format:H:i',
            'shift_end' => 'nullable|date_format:H:i',
            'date_hired' => 'nullable|date',
            'monthly_salary' => 'nullable|numeric',
            'sss' => 'nullable|string|max:50',
            'gsis' => 'nullable|string|max:50',
            'philhealth' => 'nullable|string|max:50',
            'pagibig' => 'nullable|string|max:50',
            'tin' => 'nullable|string|max:50',
            'remarks' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
        ]);

        $present = [
            'address' => $request->input('present_address'),
            'barangay' => $request->input('present_barangay'),
            'city' => $request->input('present_city'),
            'province' => $request->input('present_province'),
            'zip' => $request->input('present_zip'),
        ];
        $permanent = [
            'address' => $request->input('permanent_address'),
            'barangay' => $request->input('permanent_barangay'),
            'city' => $request->input('permanent_city'),
            'province' => $request->input('permanent_province'),
            'zip' => $request->input('permanent_zip'),
        ];

        if ($request->hasFile('photo')) {
            // delete old
            if ($employee->photo_path) {
                Storage::disk('public')->delete($employee->photo_path);
            }
            $path = $request->file('photo')->store('employees', 'public');
            $data['photo_path'] = $path;
        }

        $employee->update(array_merge($data, [
            'present_address' => $present,
            'permanent_address' => $permanent,
            'spouse' => [
                'name' => $request->input('spouse_name'),
                'occupation' => $request->input('spouse_occupation'),
                'employer' => $request->input('spouse_employer'),
                'contact' => $request->input('spouse_contact'),
            ],
            'parents' => [
                'father' => $request->input('father_name'),
                'mother' => $request->input('mother_name'),
            ],
            'education' => [
                'elementary' => $request->input('elem_school'),
                'secondary' => $request->input('hs_school'),
                'college' => $request->input('college_school'),
                'graduate' => $request->input('grad_school'),
            ],
        ]));

        return redirect()->route('employees.show', $employee)->with('success', 'Employee updated.');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee deleted.');
    }

    private function canManageAttendance(): bool
    {
        return auth()->check() && in_array(auth()->user()->role ?? '', ['admin', 'super-admin'], true);
    }
}

