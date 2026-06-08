<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Display a listing of all reports.
     */
    public function index(Request $request)
    {
        $reports = Report::query()
            ->when($request->status, function ($query) {
                return $query->where('status', $request->status);
            })
            ->when($request->search, function ($query) {
                return $query->where('title', 'like', "%{$request->search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('reports.index', compact('reports'));
    }

    /**
     * Show the form for creating a new report.
     */
    public function create()
    {
        return view('reports.create');
    }

    /**
     * Store a newly created report.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:system,performance,user,custom',
            'status' => 'required|in:draft,published,archived',
        ]);

        Report::create($validated);

        return redirect()->route('reports.index')->with('success', 'Report created successfully.');
    }

    /**
     * Display the specified report.
     */
    public function show(Report $report)
    {
        return view('reports.show', compact('report'));
    }

    /**
     * Show the form for editing the specified report.
     */
    public function edit(Report $report)
    {
        return view('reports.edit', compact('report'));
    }

    /**
     * Update the specified report.
     */
    public function update(Request $request, Report $report)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:system,performance,user,custom',
            'status' => 'required|in:draft,published,archived',
        ]);

        $report->update($validated);

        return redirect()->route('reports.show', $report)->with('success', 'Report updated successfully.');
    }

    /**
     * Delete the specified report.
     */
    public function destroy(Report $report)
    {
        $report->delete();

        return redirect()->route('reports.index')->with('success', 'Report deleted successfully.');
    }

    /**
     * Export report to Excel format.
     */
    public function exportExcel(Report $report)
    {
        $filename = 'report_' . $report->id . '_' . now()->format('Y-m-d') . '.xlsx';
        
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
        $pdf = Pdf::loadView('reports.pdf', compact('report'));
        
        return $pdf->download('report_' . $report->id . '.pdf');
    }

    /**
     * Get monitoring statistics dashboard.
     */
    public function dashboard()
    {
        $stats = [
            'total_reports' => Report::count(),
            'published_reports' => Report::where('status', 'published')->count(),
            'draft_reports' => Report::where('status', 'draft')->count(),
            'reports_by_type' => Report::selectRaw('type, COUNT(*) as count')
                ->groupBy('type')
                ->get(),
            'recent_reports' => Report::latest()->take(10)->get(),
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
            \DB::connection()->getPdo();
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
            \Cache::put('health_check', 'test', 1);
            $value = \Cache::get('health_check');
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
            $disk = \Storage::disk('local');
            return ['status' => 'healthy', 'message' => 'Filesystem accessible'];
        } catch (\Exception $e) {
            return ['status' => 'unhealthy', 'message' => 'Filesystem error'];
        }
    }
}
