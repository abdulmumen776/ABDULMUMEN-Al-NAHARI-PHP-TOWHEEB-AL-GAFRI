<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Alert;
use Illuminate\Http\JsonResponse;

class AlertDataController extends Controller
{
    /**
     * List alerts for the frontend tables.
     */
    public function index(): JsonResponse
    {
        $alerts = Alert::with('performanceMetric')
            ->latest('triggered_at')
            ->get()
            ->map(function (Alert $alert) {
                return [
                    'id' => $alert->id,
                    'title' => $alert->title,
                    'message' => $alert->description,
                    'severity' => $alert->severity,
                    'type' => $this->determineType($alert),
                    'status' => $alert->status,
                    'created_at' => optional($alert->triggered_at ?? $alert->created_at)->toDateTimeString(),
                    'resolved_at' => optional($alert->resolved_at)->toDateTimeString(),
                ];
            });

        return response()->json([
            'alerts' => $alerts,
        ]);
    }

    /**
     * Return aggregate alert statistics.
     */
    public function statistics(): JsonResponse
    {
        return response()->json([
            'total_alerts' => Alert::count(),
            'open_alerts' => Alert::where('status', 'open')->count(),
            'critical_alerts' => Alert::where('severity', 'critical')->count(),
            'resolved_today' => Alert::where('status', 'resolved')
                ->whereDate('resolved_at', today())
                ->count(),
        ]);
    }

    public function acknowledge(Alert $alert): JsonResponse
    {
        $alert->update(['status' => 'acknowledged']);

        return response()->json([
            'success' => true,
            'message' => 'Alert acknowledged successfully',
        ]);
    }

    public function resolve(Alert $alert): JsonResponse
    {
        $alert->update([
            'status' => 'resolved',
            'resolved_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Alert resolved successfully',
        ]);
    }

    public function dismiss(Alert $alert): JsonResponse
    {
        $alert->update([
            'status' => 'dismissed',
            'resolved_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Alert dismissed successfully',
        ]);
    }

    private function determineType(Alert $alert): string
    {
        if ($alert->severity === 'critical') {
            return 'security';
        }

        if ($alert->performanceMetric && str_contains(
            strtolower($alert->performanceMetric->metric_name),
            'api'
        )) {
            return 'api';
        }

        return 'performance';
    }
}
