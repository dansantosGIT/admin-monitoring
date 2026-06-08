<?php

namespace Database\Seeders;

use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create a user
        $user = User::first() ?? User::factory()->create();

        // Create sample system reports
        Report::create([
            'title' => 'System Health Check - Initial Setup',
            'description' => 'Initial system health monitoring report after installation',
            'type' => 'system',
            'status' => 'published',
            'data' => [
                'database' => ['status' => 'healthy', 'response_time' => '2ms'],
                'cache' => ['status' => 'healthy', 'hit_rate' => '85%'],
                'storage' => ['status' => 'healthy', 'available_space' => '50GB'],
                'memory' => ['used' => '256MB', 'available' => '2GB'],
            ],
            'generated_by' => $user->id,
            'published_at' => now(),
        ]);

        // Create sample performance reports
        Report::create([
            'title' => 'Application Performance Report',
            'description' => 'Weekly performance metrics and optimization suggestions',
            'type' => 'performance',
            'status' => 'published',
            'data' => [
                'page_load_time' => '1.2s',
                'database_queries' => 45,
                'api_response_time' => '340ms',
                'error_rate' => '0.1%',
                'uptime' => '99.95%',
            ],
            'generated_by' => $user->id,
            'published_at' => now(),
        ]);

        // Create sample user activity reports
        Report::create([
            'title' => 'User Activity Report',
            'description' => 'Summary of user activities and engagement metrics',
            'type' => 'user',
            'status' => 'published',
            'data' => [
                'total_active_users' => 156,
                'new_users_this_week' => 23,
                'active_sessions' => 42,
                'most_used_features' => ['Reports', 'Monitoring', 'Analytics'],
                'user_satisfaction_score' => 4.5,
            ],
            'generated_by' => $user->id,
            'published_at' => now()->subDays(1),
        ]);

        // Create a draft report
        Report::create([
            'title' => 'Q2 Monitoring Analysis - In Progress',
            'description' => 'Quarterly comprehensive monitoring and performance analysis',
            'type' => 'custom',
            'status' => 'draft',
            'data' => [
                'period' => 'Q2 2026',
                'status' => 'Data collection in progress',
            ],
            'generated_by' => $user->id,
        ]);

        // Create archived report
        Report::create([
            'title' => 'Q1 2026 Monitoring Report',
            'description' => 'Q1 2026 comprehensive monitoring and performance analysis',
            'type' => 'custom',
            'status' => 'archived',
            'data' => [
                'period' => 'Q1 2026',
                'summary' => 'All systems operating nominally',
                'issues_resolved' => 12,
            ],
            'generated_by' => $user->id,
            'published_at' => now()->subMonths(3),
        ]);

        // Generate additional reports for testing
        for ($i = 1; $i <= 10; $i++) {
            Report::create([
                'title' => "Daily Monitoring Report - Day {$i}",
                'description' => "Daily system monitoring report for Day {$i}",
                'type' => 'system',
                'status' => 'published',
                'data' => [
                    'date' => now()->subDays($i)->format('Y-m-d'),
                    'uptime_percent' => 99.5 + (rand(0, 10) / 100),
                    'request_count' => rand(5000, 15000),
                    'error_count' => rand(5, 50),
                    'avg_response_time' => rand(200, 800) . 'ms',
                ],
                'generated_by' => $user->id,
                'published_at' => now()->subDays($i),
            ]);
        }
    }
}
