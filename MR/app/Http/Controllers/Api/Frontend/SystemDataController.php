<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Alert;
use App\Models\Api;
use App\Models\ApiPerformanceLog;
use App\Models\Client;
use App\Models\Operation;
use App\Models\PerformanceDataset;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SystemDataController extends Controller
{
    /**
     * Statistics for the global dashboard header.
     */
    public function dashboardStatistics(): JsonResponse
    {
        return response()->json([
            'total_clients' => Client::count(),
            'active_operations' => Operation::where('status', 'active')->count(),
            'monitored_apis' => Api::where('status', 'monitored')->count(),
            'open_alerts' => Alert::where('status', 'open')->count(),
        ]);
    }

    /**
     * Charts data for the dashboard widgets.
     */
    public function dashboardCharts(): JsonResponse
    {
        $startDate = now()->subDays(6)->startOfDay();
        $labels = collect(range(6, 0))
            ->map(fn ($offset) => now()->subDays($offset)->format('Y-m-d'));

        $logs = ApiPerformanceLog::where('monitored_at', '>=', $startDate)
            ->get()
            ->groupBy(fn ($log) => optional($log->monitored_at)->format('Y-m-d'));

        $responseTimes = $labels->map(function (string $date) use ($logs) {
            $dayLogs = $logs->get($date, collect());
            return $dayLogs->isEmpty() ? 0 : round($dayLogs->avg('response_time_ms'), 2);
        });

        $errorRates = $labels->map(function (string $date) use ($logs) {
            $dayLogs = $logs->get($date, collect());
            if ($dayLogs->isEmpty()) {
                return 0;
            }
            $errors = $dayLogs->where('status_code', '>=', 400)->count();
            return round(($errors / $dayLogs->count()) * 100, 2);
        });

        $statusCounts = Operation::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $operationLabels = collect([
            'active' => 'نشط',
            'completed' => 'مكتمل',
            'scheduled' => 'مجدول',
            'cancelled' => 'ملغي',
        ]);

        $operationCounts = $operationLabels->keys()->map(fn ($status) => (int) ($statusCounts[$status] ?? 0));

        return response()->json([
            'performance' => [
                'labels' => $labels->values(),
                'response_times' => $responseTimes->values(),
                'error_rates' => $errorRates->values(),
            ],
            'operations' => [
                'labels' => $operationLabels->values(),
                'counts' => $operationCounts->values(),
            ],
        ]);
    }

    /**
     * System status used in the dashboard panel.
     */
    public function systemStatus(): JsonResponse
    {
        return response()->json([
            'database' => $this->checkDatabaseStatus(),
            'redis_cache' => $this->checkRedisStatus(),
            'queue_worker' => $this->checkQueueWorkerStatus(),
            'api_monitoring' => Api::where('status', 'monitored')->exists() ? 'active' : 'inactive',
            'email_service' => $this->checkEmailServiceStatus(),
        ]);
    }

    /**
     * Notifications shown in the layout dropdown.
     */
    public function notifications(): JsonResponse
    {
        $notifications = collect();

        $alerts = Alert::latest('triggered_at')
            ->take(5)
            ->get()
            ->map(fn (Alert $alert) => [
                'id' => 'alert-' . $alert->id,
                'message' => $alert->title,
                'type' => $alert->severity === 'critical' ? 'error' : 'warning',
                'time' => optional($alert->triggered_at ?? $alert->created_at)->diffForHumans(),
            ]);

        $operations = Operation::latest('updated_at')
            ->take(5)
            ->get()
            ->map(fn (Operation $operation) => [
                'id' => 'operation-' . $operation->id,
                'message' => "Operation {$operation->name} updated",
                'type' => 'info',
                'time' => optional($operation->updated_at)->diffForHumans(),
            ]);

        $notifications = $alerts->merge($operations)->take(6)->values();

        return response()->json($notifications);
    }

    /**
     * Monitoring sessions for the monitoring workspace.
     */
    public function monitoringSessions(): JsonResponse
    {
        $datasets = PerformanceDataset::latest('created_at')->take(8)->get();

        $sessions = $datasets->map(function (PerformanceDataset $dataset) {
            $serverData = $dataset->server_performance_data ?? [];

            return [
                'id' => $dataset->id,
                'name' => 'Monitoring Session #' . str_pad((string) $dataset->id, 3, '0', STR_PAD_LEFT),
                'started_at' => optional($dataset->created_at)->toDateTimeString(),
                'location' => $serverData['location'] ?? null,
                'camera_count' => $serverData['active_connections'] ?? 0,
                'resolution' => ($serverData['resolution'] ?? null),
                'status' => ($serverData['status'] ?? 'inactive'),
                'duration' => $this->formatDuration(
                    optional($dataset->generated_at)->diffInMinutes($dataset->created_at ?? now(), false)
                ),
            ];
        });

        return response()->json($sessions);
    }

    /**
     * Camera feed summary used inside the monitoring module.
     */
    public function cameras(): JsonResponse
    {
        $cameras = Api::with('client', 'performanceLogs')
            ->latest('created_at')
            ->take(8)
            ->get()
            ->map(function (Api $api) {
                $latestLog = $api->performanceLogs->sortByDesc('monitored_at')->first();

                return [
                    'id' => $api->id,
                    'name' => $api->name,
                    'location' => optional($api->client)->name ?? 'Unassigned',
                    'status' => $api->status === 'error' ? 'offline' : 'online',
                    'ip_address' => $this->extractHost($api->base_url),
                    'resolution' => $api->status === 'monitored' ? '1080p' : null,
                    'latency_ms' => $latestLog?->response_time_ms,
                ];
            });

        return response()->json($cameras);
    }

    private function formatDuration(?int $minutes): string
    {
        $minutes = abs($minutes ?? 0);

        if ($minutes < 60) {
            return $minutes . 'm';
        }

        $hours = intdiv($minutes, 60);
        $remaining = $minutes % 60;

        return sprintf('%dh %02dm', $hours, $remaining);
    }

    private function extractHost(?string $url): ?string
    {
        if (!$url) {
            return null;
        }

        $host = parse_url($url, PHP_URL_HOST);

        return $host ?: null;
    }

    private function checkDatabaseStatus(): string
    {
        try {
            DB::select('SELECT 1');
            return 'active';
        } catch (\Exception $e) {
            return 'error';
        }
    }

    private function checkRedisStatus(): string
    {
        try {
            if (!config('redis.default.host')) {
                return 'inactive';
            }

            if (!extension_loaded('redis')) {
                return 'inactive';
            }

            if (app()->bound('redis')) {
                $redis = app('redis');
                $redis->ping();
                return 'active';
            }

            return 'inactive';
        } catch (\Exception $e) {
            return 'error';
        }
    }

    private function checkQueueWorkerStatus(): string
    {
        try {
            $failedJobs = DB::table('failed_jobs')->count();
            return $failedJobs > 10 ? 'error' : 'active';
        } catch (\Exception $e) {
            return 'inactive';
        }
    }

    private function checkEmailServiceStatus(): string
    {
        try {
            $driver = config('mail.default');
            return $driver ? 'active' : 'inactive';
        } catch (\Exception $e) {
            return 'error';
        }
    }
}
