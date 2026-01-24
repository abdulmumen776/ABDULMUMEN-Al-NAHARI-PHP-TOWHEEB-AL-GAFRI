<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Api;
use App\Models\ApiPerformanceLog;
use Illuminate\Http\JsonResponse;

class ApiDataController extends Controller
{
    /**
     * List APIs with performance summaries.
     */
    public function index(): JsonResponse
    {
        $apis = Api::with(['client', 'performanceLogs'])
            ->latest('created_at')
            ->get()
            ->map(function (Api $api) {
                $stats = $this->calculateLogStats($api);

                return [
                    'id' => $api->id,
                    'name' => $api->name,
                    'base_url' => $api->base_url,
                    'client_id' => $api->client_id,
                    'client_name' => optional($api->client)->name,
                    'type' => $this->inferType($api),
                    'status' => $api->status,
                    'avg_response_time' => $stats['avg_response_time'],
                    'success_rate' => $stats['success_rate'],
                    'created_at' => optional($api->created_at)->toDateString(),
                ];
            });

        return response()->json([
            'apis' => $apis,
        ]);
    }

    /**
     * Aggregate API statistics for dashboard cards.
     */
    public function statistics(): JsonResponse
    {
        $total = Api::count();
        $monitored = Api::where('status', 'monitored')->count();
        $active = Api::whereIn('status', ['monitored', 'active'])->count();

        $totalLogs = ApiPerformanceLog::count();
        $erroredLogs = ApiPerformanceLog::where('status_code', '>=', 400)->count();
        $errorRate = $totalLogs > 0 ? round(($erroredLogs / $totalLogs) * 100, 2) . '%' : '0%';

        return response()->json([
            'total_apis' => $total,
            'active_apis' => $active,
            'monitored_apis' => $monitored,
            'error_rate' => $errorRate,
        ]);
    }

    private function calculateLogStats(Api $api): array
    {
        $logs = $api->performanceLogs;

        if ($logs->isEmpty()) {
            return [
                'avg_response_time' => 0,
                'success_rate' => 100,
            ];
        }

        $averageResponse = round($logs->avg('response_time_ms'), 2);
        $successCount = $logs->where('status_code', '<', 400)->count();
        $successRate = round(($successCount / $logs->count()) * 100, 2);

        return [
            'avg_response_time' => $averageResponse,
            'success_rate' => $successRate,
        ];
    }

    private function inferType(Api $api): string
    {
        $url = strtolower($api->base_url);

        return match (true) {
            str_contains($url, 'graphql') => 'graphql',
            str_contains($url, 'soap') => 'soap',
            str_contains($url, 'ws') || str_contains($url, 'socket') => 'websocket',
            default => 'rest',
        };
    }
}
