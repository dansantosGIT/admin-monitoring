<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\AttendanceSchedule;
use App\Models\DtrEntry;
use App\Models\DtrPeriod;
use App\Models\Employee;
use App\Models\LeaveCredit;
use App\Models\LeaveRecord;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));

        try {
            $periodStart = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        } catch (\Throwable) {
            $periodStart = now()->startOfMonth();
            $month = $periodStart->format('Y-m');
        }

        $periodEnd = $periodStart->copy()->endOfMonth();
        $tab = $request->input('tab', 'dtr');
        $tab = in_array($tab, ['dtr', 'schedules', 'leaves', 'summary'], true) ? $tab : 'dtr';
        $search = trim((string) $request->input('search', ''));
        $department = trim((string) $request->input('department', ''));
        $isNewMonth = $periodStart->greaterThanOrEqualTo(now()->startOfMonth());

        $employeeQuery = ($isNewMonth ? Employee::query() : Employee::withTrashed())
            ->orderBy('last_name')
            ->orderBy('first_name');
        if ($isNewMonth) {
            $employeeQuery->where('status', 'Active');
        }
        if ($search !== '') {
            $employeeQuery->where(function ($query) use ($search) {
                $query->where('employee_number', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('middle_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            });
        }
        if ($department !== '') {
            $employeeQuery->where('department', $department);
        }

        $employees = $employeeQuery->get();
        $employeeIds = $employees->modelKeys();

        $departmentQuery = $isNewMonth ? Employee::query() : Employee::withTrashed();
        if ($isNewMonth) {
            $departmentQuery->where('status', 'Active');
        }
        $departments = $departmentQuery
            ->whereNotNull('department')
            ->where('department', '<>', '')
            ->select('department')
            ->distinct()
            ->orderBy('department')
            ->pluck('department');

        $schedules = AttendanceSchedule::whereIn('employee_id', $employeeIds)
            ->where('effective_from', '<=', $periodEnd->toDateString())
            ->where(function ($query) use ($periodStart) {
                $query->whereNull('effective_to')->orWhere('effective_to', '>=', $periodStart->toDateString());
            })
            ->orderByDesc('effective_from')
            ->get()
            ->groupBy('employee_id')
            ->map(fn ($items) => $items->first());

        $schedules = $employees->mapWithKeys(function (Employee $employee) use ($schedules) {
            $schedule = $schedules->get($employee->id);

            if ($schedule) {
                return [$employee->id => $schedule];
            }

            if (! $employee->shift_start || ! $employee->shift_end) {
                return [];
            }

            return [$employee->id => (object) [
                'shift_type' => $employee->shift_type,
                'shift_start' => $employee->shift_start,
                'shift_end' => $employee->shift_end,
                'working_days' => ['mon', 'tue', 'wed', 'thu', 'fri'],
                'status' => 'assigned',
            ]];
        });

        $entries = DtrEntry::whereIn('employee_id', $employeeIds)
            ->whereBetween('work_date', [$periodStart->toDateString(), $periodEnd->toDateString()])
            ->get()
            ->groupBy('employee_id');

        $leaveRecords = LeaveRecord::with('employee')
            ->whereIn('employee_id', $employeeIds)
            ->where('start_date', '<=', $periodEnd->toDateString())
            ->where('end_date', '>=', $periodStart->toDateString())
            ->latest('start_date')
            ->get();

        $credits = LeaveCredit::whereIn('employee_id', $employeeIds)
            ->where('year', $periodStart->year)
            ->get()
            ->keyBy('employee_id');

        $summary = $employees->map(function (Employee $employee) use ($entries, $schedules, $leaveRecords) {
            $employeeEntries = $entries->get($employee->id, collect());
            return [
                'employee' => $employee,
                'schedule' => $schedules->get($employee->id),
                'present' => $employeeEntries->where('status', 'present')->count(),
                'absent' => $employeeEntries->where('status', 'absent')->count(),
                'leave' => $employeeEntries->where('status', 'leave')->count(),
                'late' => $employeeEntries->sum('late_minutes'),
                'leave_days' => $leaveRecords->where('employee_id', $employee->id)->where('status', 'approved')->sum('days'),
            ];
        });

        $calendarData = $employees->mapWithKeys(function (Employee $employee) use ($periodStart, $entries, $leaveRecords, $schedules) {
            $employeeEntries = $entries->get($employee->id, collect())->keyBy(function (DtrEntry $entry) {
                return Carbon::parse((string) $entry->work_date)->format('Y-m-d');
            });
            $employeeLeaves = $leaveRecords->where('employee_id', $employee->id);
            $schedule = $schedules->get($employee->id);
            $workingDays = $schedule?->working_days ?: ['mon', 'tue', 'wed', 'thu', 'fri'];
            $days = collect(range(1, $periodStart->daysInMonth))->map(function (int $day) use ($periodStart, $employeeEntries, $employeeLeaves, $workingDays) {
                $date = $periodStart->copy()->day($day);
                $entry = $employeeEntries->get($date->format('Y-m-d'));
                $leave = $employeeLeaves->first(fn ($record) => $date->between($record->start_date, $record->end_date));
                $weekday = strtolower($date->format('D'));
                $status = $entry?->status ?: ($leave ? 'leave' : (in_array($weekday, $workingDays, true) ? 'not_recorded' : 'rest_day'));

                return [
                    'date' => $date->format('Y-m-d'),
                    'day' => $day,
                    'status' => $status,
                    'time_in' => $entry?->time_in ? substr($entry->time_in, 0, 5) : null,
                    'time_out' => $entry?->time_out ? substr($entry->time_out, 0, 5) : null,
                    'late_minutes' => $entry?->late_minutes ?? 0,
                    'overtime_minutes' => $entry?->overtime_minutes ?? 0,
                    'undertime_minutes' => $entry?->undertime_minutes ?? 0,
                    'remarks' => $entry?->remarks,
                    'leave_type' => $leave ? trim(($leave->leave_category ? $leave->leave_category . ' / ' : '') . $leave->leave_type) : null,
                ];
            });

            return [$employee->id => [
                'id' => $employee->id,
                'name' => $employee->last_name . ', ' . $employee->first_name . ($employee->middle_name ? ' ' . substr($employee->middle_name, 0, 1) . '.' : ''),
                'employee_number' => $employee->employee_number ?: 'No ID number',
                'department' => $employee->department ?: '-',
                'section' => $employee->section ?: 'Section not assigned',
                'position' => $employee->position ?: 'Position not assigned',
                'days' => $days->values()->all(),
                'counts' => $days->countBy('status')->all(),
            ]];
        });

        return view('attendance.index', compact(
            'employees', 'schedules', 'entries', 'leaveRecords', 'credits', 'summary',
            'calendarData', 'departments', 'month', 'periodStart', 'periodEnd', 'tab', 'search', 'department', 'isNewMonth'
        ))->with('canManage', $this->canManage());
    }

    public function storeEntry(Request $request)
    {
        $this->ensureCanManage();
        $data = $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'work_date' => ['required', 'date'],
            'time_in' => ['nullable', 'date_format:H:i'],
            'time_out' => ['nullable', 'date_format:H:i'],
            'status' => ['required', 'in:present,absent,leave,rest_day'],
            'late_minutes' => ['nullable', 'integer', 'min:0', 'max:1440'],
            'undertime_minutes' => ['nullable', 'integer', 'min:0', 'max:1440'],
            'overtime_minutes' => ['nullable', 'integer', 'min:0', 'max:1440'],
            'remarks' => ['nullable', 'string', 'max:1000'],
        ]);

        $workDate = Carbon::parse($data['work_date']);
        $employee = Employee::findOrFail($data['employee_id']);
        $schedule = $this->scheduleFor($employee, $workDate);
        $metrics = $this->calculateAttendanceMetrics($data, $workDate, $schedule);
        $period = DtrPeriod::firstOrCreate(
            ['employee_id' => $data['employee_id'], 'period_start' => $workDate->copy()->startOfMonth()->toDateString()],
            ['period_end' => $workDate->copy()->endOfMonth()->toDateString()]
        );

        DtrEntry::updateOrCreate(
            ['employee_id' => $data['employee_id'], 'work_date' => $workDate->toDateString()],
            array_merge($data, $metrics, ['dtr_period_id' => $period->id])
        );

        return back()->with('success', 'DTR entry saved.');
    }

    public function storeSchedule(Request $request)
    {
        $this->ensureCanManage();
        $data = $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'shift_type' => ['nullable', 'in:morning,mid,night'],
            'shift_start' => ['nullable', 'date_format:H:i'],
            'shift_end' => ['nullable', 'date_format:H:i'],
            'working_days' => ['nullable', 'array'],
            'working_days.*' => ['in:mon,tue,wed,thu,fri,sat,sun'],
            'effective_from' => ['required', 'date'],
        ]);

        $data['status'] = $data['shift_start'] && $data['shift_end'] ? 'assigned' : 'needs_schedule';
        $data['working_days'] = array_values(array_unique($data['working_days'] ?? ['mon', 'tue', 'wed', 'thu', 'fri']));
        AttendanceSchedule::updateOrCreate(
            ['employee_id' => $data['employee_id'], 'effective_from' => $data['effective_from']],
            $data
        );

        return back()->with('success', 'Schedule updated.');
    }

    public function storeLeave(Request $request)
    {
        $this->ensureCanManage();
        $data = $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'leave_category' => ['required', 'in:Regular,Special,Other'],
            'leave_type' => ['required', 'in:Vacation Leave,Sick Leave,CTO,Wellness Leave,Other Leave'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'days' => ['required', 'numeric', 'min:0.5', 'max:366'],
            'status' => ['required', 'in:pending,approved,rejected'],
            'reason' => ['nullable', 'string', 'max:1000'],
        ]);

        LeaveRecord::create($data);

        return back()->with('success', 'Leave record added.');
    }

    private function canManage(): bool
    {
        return auth()->check() && in_array(auth()->user()->role ?? '', ['admin', 'super-admin'], true);
    }

    private function ensureCanManage(): void
    {
        abort_unless($this->canManage(), 403);
    }

    private function scheduleFor(Employee $employee, Carbon $workDate): ?object
    {
        $schedule = AttendanceSchedule::where('employee_id', $employee->id)
            ->where('effective_from', '<=', $workDate->toDateString())
            ->where(function ($query) use ($workDate) {
                $query->whereNull('effective_to')->orWhere('effective_to', '>=', $workDate->toDateString());
            })
            ->orderByDesc('effective_from')
            ->first();

        if ($schedule) {
            return $schedule;
        }

        if (! $employee->shift_start || ! $employee->shift_end) {
            return null;
        }

        return (object) [
            'shift_start' => $employee->shift_start,
            'shift_end' => $employee->shift_end,
        ];
    }

    private function calculateAttendanceMetrics(array $data, Carbon $workDate, ?object $schedule): array
    {
        $manual = [
            'late_minutes' => (int) ($data['late_minutes'] ?? 0),
            'undertime_minutes' => (int) ($data['undertime_minutes'] ?? 0),
            'overtime_minutes' => (int) ($data['overtime_minutes'] ?? 0),
        ];

        if ($data['status'] !== 'present' || ! $schedule?->shift_start || ! $schedule?->shift_end) {
            return $data['status'] === 'present' ? $manual : [
                'late_minutes' => 0,
                'undertime_minutes' => 0,
                'overtime_minutes' => 0,
            ];
        }

        $scheduledStart = Carbon::parse($workDate->toDateString() . ' ' . $schedule->shift_start);
        $scheduledEnd = Carbon::parse($workDate->toDateString() . ' ' . $schedule->shift_end);
        $overnight = $scheduledEnd->lessThanOrEqualTo($scheduledStart);

        if ($overnight) {
            $scheduledEnd->addDay();
        }

        $metrics = [
            'late_minutes' => 0,
            'undertime_minutes' => 0,
            'overtime_minutes' => 0,
        ];

        if (! empty($data['time_in'])) {
            $actualIn = Carbon::parse($workDate->toDateString() . ' ' . $data['time_in']);
            if ($overnight && substr($data['time_in'], 0, 5) <= substr((string) $schedule->shift_end, 0, 5)) {
                $actualIn->addDay();
            }
            if ($actualIn->greaterThan($scheduledStart)) {
                $metrics['late_minutes'] = $scheduledStart->diffInMinutes($actualIn);
            }
        }

        if (! empty($data['time_out'])) {
            $actualOut = Carbon::parse($workDate->toDateString() . ' ' . $data['time_out']);
            if ($overnight && substr($data['time_out'], 0, 5) <= substr((string) $schedule->shift_start, 0, 5)) {
                $actualOut->addDay();
            }

            if ($actualOut->lessThan($scheduledEnd)) {
                $metrics['undertime_minutes'] = $actualOut->diffInMinutes($scheduledEnd);
            } elseif ($actualOut->greaterThan($scheduledEnd)) {
                $metrics['overtime_minutes'] = $scheduledEnd->diffInMinutes($actualOut);
            }
        }

        return $metrics;
    }
}
