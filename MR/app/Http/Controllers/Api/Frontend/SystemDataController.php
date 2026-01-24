<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Alert;
use App\Models\Api;
use App\Models\Client;
use App\Models\Operation;
use App\Models\PerformanceDataset;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

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
                'location' => $serverData['location'] ?? 'Primary Data Center',
                'camera_count' => $serverData['active_connections'] ?? rand(4, 12),
                'resolution' => ($serverData['resolution'] ?? '1080p'),
                'status' => ($serverData['status'] ?? 'active'),
                'duration' => $this->formatDuration(
                    optional($dataset->generated_at)->diffInMinutes($dataset->created_at ?? now(), false)
                ),
            ];
        });

        if ($sessions->isEmpty()) {
            $sessions = collect([
                [
                    'id' => 1,
                    'name' => 'Monitoring Session #001',
                    'started_at' => now()->subHours(2)->toDateTimeString(),
                    'location' => 'Primary Data Center',
                    'camera_count' => 6,
                    'resolution' => '1080p',
                    'status' => 'active',
                    'duration' => '2h 00m',
                ],
            ]);
        }

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
                    'ip_address' => $this->fakeIp($api->id),
                    'resolution' => $api->status === 'monitored' ? '1080p' : '720p',
                    'latency_ms' => $latestLog?->response_time_ms ?? rand(40, 180),
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

    private function fakeIp(int $seed): string
    {
        return sprintf(
            '10.%d.%d.%d',
            ($seed * 31) % 255,
            ($seed * 17) % 255,
            ($seed * 11) % 255
        );
    }
}
