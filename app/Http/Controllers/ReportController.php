<?php

namespace App\Http\Controllers;

use App\Http\Requests\IncidentReportRequest;
use App\Models\Employee;
use App\Models\Report;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /**
     * Display a listing of all incident reports.
     */
    public function index(Request $request)
    {
        $reports = Report::query()
            ->with(['employee', 'reportedBy'])
            ->search($request->input('search'))
            ->ofStatus($request->input('status'))
            ->ofSeverity($request->input('severity'))
            ->ofType($request->input('incident_type'))
            ->orderByDesc('date_of_incident')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('reports.index', [
            'reports' => $reports,
            'filters' => [
                'search' => $request->input('search'),
                'status' => $request->input('status'),
                'severity' => $request->input('severity'),
                'incident_type' => $request->input('incident_type'),
            ],
        ]);
    }

    /**
     * Show the form for creating a new incident report.
     */
    public function create()
    {
        return view('reports.create', $this->formOptions());
    }

    /**
     * Store a newly created incident report.
     */
    public function store(IncidentReportRequest $request)
    {
        $validated = $request->validated();

        $report = DB::transaction(function () use ($validated, $request) {
            $report = Report::create([
                'incident_code' => $this->generateIncidentCode(),
                'employee_id' => $validated['employee_id'],
                'department' => $validated['department'],
                'incident_type' => $validated['incident_type'],
                'item_name' => $validated['item_name'],
                'property_serial_no' => $validated['property_serial_no'] ?? null,
                'description' => $validated['description'],
                'location' => $validated['location'],
                'date_of_incident' => $validated['date_of_incident'],
                'severity' => $validated['severity'],
                'estimated_cost' => $validated['estimated_cost'] ?? null,
                'status' => $validated['status'],
                'action_taken' => $validated['action_taken'] ?? null,
                'reported_by' => auth()->id(),
                'remarks' => $validated['remarks'] ?? null,
            ]);

            $this->storeAttachments($report, $request->file('attachments', []));

            return $report;
        });

        return redirect()->route('reports.show', $report)->with('success', 'Incident report created successfully.');
    }

    /**
     * Display the specified incident report.
     */
    public function show(Report $report)
    {
        $report->load(['employee', 'reportedBy', 'attachments']);

        return view('reports.show', compact('report'));
    }

    /**
     * Show the form for editing the specified incident report.
     */
    public function edit(Report $report)
    {
        $report->load(['employee', 'reportedBy', 'attachments']);

        return view('reports.edit', array_merge(compact('report'), $this->formOptions()));
    }

    /**
     * Update the specified incident report.
     */
    public function update(IncidentReportRequest $request, Report $report)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $request, $report) {
            $report->update([
                'employee_id' => $validated['employee_id'],
                'department' => $validated['department'],
                'incident_type' => $validated['incident_type'],
                'item_name' => $validated['item_name'],
                'property_serial_no' => $validated['property_serial_no'] ?? null,
                'description' => $validated['description'],
                'location' => $validated['location'],
                'date_of_incident' => $validated['date_of_incident'],
                'severity' => $validated['severity'],
                'estimated_cost' => $validated['estimated_cost'] ?? null,
                'status' => $validated['status'],
                'action_taken' => $validated['action_taken'] ?? null,
                'remarks' => $validated['remarks'] ?? null,
            ]);

            $this->storeAttachments($report, $request->file('attachments', []));
        });

        return redirect()->route('reports.show', $report)->with('success', 'Incident report updated successfully.');
    }

    /**
     * Delete the specified incident report.
     */
    public function destroy(Report $report)
    {
        $report->delete();

        return redirect()->route('reports.index')->with('success', 'Incident report archived successfully.');
    }

    /**
     * Export report to Excel format.
     */
    public function exportExcel(Report $report)
    {
        $filename = ($report->incident_code ?: 'incident_report') . '_' . now()->format('Y-m-d') . '.xlsx';

        return Excel::download(
            new \App\Exports\ReportExport($report),
            $filename
        );
    }

    /**
     * Export report to PDF format.
     */
    public function exportPDF(Report $report)
    {
        $report->load(['employee', 'reportedBy', 'attachments']);
        $pdf = Pdf::loadView('reports.pdf', compact('report'));

        return $pdf->download(($report->incident_code ?: 'incident_report') . '.pdf');
    }

    /**
     * Get monitoring statistics dashboard.
     */
    public function dashboard()
    {
        $stats = [
            'total_employees' => User::count(),
            'active_employees' => 0,
            'inactive_employees' => 0,
            'pending_irs' => Report::where('status', 'pending')->count(),
            'job_order_count' => 0,
            'permanent_count' => 0,
            'present_today' => 0,
            'on_leave_today' => 0,
            'absent_today' => 0,
            'total_reports' => Report::count(),
            'published_reports' => Report::where('status', 'resolved')->count(),
            'draft_reports' => Report::where('status', 'pending')->count(),
            'reports_by_type' => Report::selectRaw('incident_type as type, COUNT(*) as count')
                ->groupBy('incident_type')
                ->get(),
            'recent_reports' => Report::with('employee')->latest('date_of_incident')->take(10)->get(),
        ];

        return view('dashboard', compact('stats'));
    }

    /**
     * Generate a system health check report.
     */
    public function systemHealth()
    {
        $health = [
            'database' => $this->checkDatabase(),
            'storage' => $this->checkStorage(),
            'cache' => $this->checkCache(),
            'filesystem' => $this->checkFilesystem(),
        ];

        return response()->json($health);
    }

    /**
     * Check database connectivity.
     */
    private function checkDatabase(): array
    {
        try {
            DB::connection()->getPdo();

            return ['status' => 'healthy', 'message' => 'Database connection successful'];
        } catch (\Exception $e) {
            return ['status' => 'unhealthy', 'message' => $e->getMessage()];
        }
    }

    /**
     * Check storage writability.
     */
    private function checkStorage(): array
    {
        try {
            $path = storage_path('logs/health-check.tmp');
            file_put_contents($path, 'test');
            unlink($path);

            return ['status' => 'healthy', 'message' => 'Storage is writable'];
        } catch (\Exception $e) {
            return ['status' => 'unhealthy', 'message' => 'Storage not writable'];
        }
    }

    /**
     * Check cache connectivity.
     */
    private function checkCache(): array
    {
        try {
            cache()->put('health_check', 'test', 1);
            cache()->get('health_check');

            return ['status' => 'healthy', 'message' => 'Cache is working'];
        } catch (\Exception $e) {
            return ['status' => 'unhealthy', 'message' => 'Cache not available'];
        }
    }

    /**
     * Check filesystem.
     */
    private function checkFilesystem(): array
    {
        try {
            \Storage::disk('local');

            return ['status' => 'healthy', 'message' => 'Filesystem accessible'];
        } catch (\Exception $e) {
            return ['status' => 'unhealthy', 'message' => 'Filesystem error'];
        }
    }

    private function formOptions(): array
    {
        return [
            'employees' => Employee::query()->orderBy('last_name')->orderBy('first_name')->get(),
            'departments' => Employee::query()
                ->whereNotNull('department')
                ->where('department', '!=', '')
                ->distinct()
                ->orderBy('department')
                ->pluck('department'),
            'incidentTypes' => [
                'equipment_damage' => 'Equipment Damage',
                'equipment_loss' => 'Equipment Loss',
                'vehicle_incident' => 'Vehicle Incident',
                'other' => 'Other',
            ],
            'severityLevels' => [
                'minor' => 'Minor',
                'major' => 'Major',
                'critical' => 'Critical',
            ],
            'statusOptions' => [
                'pending' => 'Pending',
                'under_investigation' => 'Under Investigation',
                'resolved' => 'Resolved',
                'closed' => 'Closed',
            ],
        ];
    }

    private function generateIncidentCode(): string
    {
        $year = now()->year;
        $count = Report::withTrashed()->where('incident_code', 'like', "MIR-{$year}-%")->count();

        return sprintf('MIR-%d-%04d', $year, $count + 1);
    }

    private function storeAttachments(Report $report, array $files): void
    {
        foreach ($files as $file) {
            if (! $file) {
                continue;
            }

            $path = $file->store('incident-reports/' . now()->format('Y/m'), 'public');

            $report->attachments()->create([
                'file_path' => $path,
                'original_filename' => $file->getClientOriginalName(),
                'uploaded_at' => now(),
            ]);
        }
    }
}
