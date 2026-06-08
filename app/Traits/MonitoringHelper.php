<?php

namespace App\Traits;

use App\Models\Report;

trait MonitoringHelper
{
    /**
     * Create a new monitoring report.
     */
    public function createMonitoringReport(string $title, string $type, array $data = []): Report
    {
        return Report::create([
            'title' => $title,
            'type' => $type,
            'status' => 'draft',
            'data' => $data,
            'generated_by' => auth()->id(),
        ]);
    }

    /**
     * Get recent system metrics.
     */
    public function getSystemMetrics(): array
    {
        return [
            'memory_usage' => $this->getMemoryUsage(),
            'cpu_load' => $this->getCPULoad(),
            'disk_space' => $this->getDiskSpace(),
            'database_size' => $this->getDatabaseSize(),
            'timestamp' => now(),
        ];
    }

    /**
     * Get memory usage.
     */
    private function getMemoryUsage(): array
    {
        $used = round(memory_get_usage() / 1024 / 1024, 2);
        $peak = round(memory_get_peak_usage() / 1024 / 1024, 2);
        $limit = ini_get('memory_limit');

        return [
            'used_mb' => $used,
            'peak_mb' => $peak,
            'limit' => $limit,
        ];
    }

    /**
     * Get CPU load average.
     */
    private function getCPULoad(): array
    {
        if (function_exists('sys_getloadavg')) {
            $load = sys_getloadavg();
            return [
                'one_minute' => $load[0],
                'five_minutes' => $load[1],
                'fifteen_minutes' => $load[2],
            ];
        }

        return ['status' => 'unavailable'];
    }

    /**
     * Get disk space information.
     */
    private function getDiskSpace(): array
    {
        $disk = disk_total_space(base_path());
        $free = disk_free_space(base_path());

        return [
            'total_gb' => round($disk / 1024 / 1024 / 1024, 2),
            'free_gb' => round($free / 1024 / 1024 / 1024, 2),
            'used_gb' => round(($disk - $free) / 1024 / 1024 / 1024, 2),
            'usage_percent' => round((($disk - $free) / $disk) * 100, 2),
        ];
    }

    /**
     * Get database size.
     */
    private function getDatabaseSize(): string|null
    {
        try {
            $driver = config('database.default');

            if ($driver === 'sqlite') {
                $path = config("database.connections.sqlite.database");
                if (file_exists($path)) {
                    return round(filesize($path) / 1024 / 1024, 2) . ' MB';
                }
            }

            // For MySQL/PostgreSQL, you'd need to execute specific SQL commands
            return 'Unable to determine';
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Log monitoring data.
     */
    public function logMonitoring(string $type, array $metrics): void
    {
        Report::create([
            'title' => "{$type} Monitoring Report - " . now()->format('Y-m-d H:i:s'),
            'type' => 'system',
            'status' => 'published',
            'data' => $metrics,
            'generated_by' => null,
            'published_at' => now(),
        ]);
    }

    /**
     * Get report statistics.
     */
    public function getReportStatistics(): array
    {
        return [
            'total_reports' => Report::count(),
            'published' => Report::where('status', 'published')->count(),
            'draft' => Report::where('status', 'draft')->count(),
            'archived' => Report::where('status', 'archived')->count(),
            'by_type' => Report::selectRaw('type, COUNT(*) as count')
                ->groupBy('type')
                ->pluck('count', 'type')
                ->toArray(),
            'this_month' => Report::thisMonth()->count(),
        ];
    }
}
