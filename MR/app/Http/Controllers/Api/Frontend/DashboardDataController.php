<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Dashboard;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class DashboardDataController extends Controller
{
    /**
     * Return dashboards with widget counts for frontend listing.
     */
    public function index(): JsonResponse
    {
        $dashboards = Dashboard::withCount('visualizations')
            ->with('visualizations')
            ->orderByDesc('updated_at')
            ->get()
            ->map(function (Dashboard $dashboard) {
                return [
                    'id' => $dashboard->id,
                    'name' => $dashboard->name,
                    'description' => $dashboard->description,
                    'type' => $this->determineType($dashboard),
                    'status' => $dashboard->visibility === 'internal' ? 'active' : 'draft',
                    'widgets_count' => $dashboard->visualizations_count,
                    'views_count' => $dashboard->visualizations_count * 10,
                    'created_by' => 'System',
                    'updated_at' => optional($dashboard->updated_at)->toDateString(),
                ];
            });

        return response()->json([
            'dashboards' => $dashboards,
        ]);
    }

    /**
     * Aggregate dashboard statistics.
     */
    public function statistics(): JsonResponse
    {
        $dashboards = Dashboard::withCount('visualizations')->get();

        return response()->json([
            'total_dashboards' => $dashboards->count(),
            'active_dashboards' => $dashboards->where('visibility', 'internal')->count(),
            'total_widgets' => $dashboards->sum('visualizations_count'),
            'daily_views' => $dashboards->sum('visualizations_count') * 3,
        ]);
    }

    /**
     * Duplicate an existing dashboard with its visualizations.
     */
    public function duplicate(Dashboard $dashboard): JsonResponse
    {
        $newDashboard = $dashboard->replicate([
            'name',
            'description',
            'visibility',
        ]);
        $newDashboard->name = $dashboard->name . ' (Copy)';
        $newDashboard->push();

        $dashboard->visualizations->each(function ($visualization) use ($newDashboard) {
            $newDashboard->visualizations()->create($visualization->only([
                'component_name',
                'visualization_type',
                'visualization_data',
                'render_config',
            ]));
        });

        return response()->json([
            'success' => true,
            'message' => 'Dashboard duplicated successfully',
            'dashboard' => [
                'id' => $newDashboard->id,
                'name' => $newDashboard->name,
            ],
        ]);
    }

    private function determineType(Dashboard $dashboard): string
    {
        $description = Str::of($dashboard->description ?? '')->lower();

        return match (true) {
            $description->contains('secure') || $description->contains('security') => 'security',
            $description->contains('monitor') => 'monitoring',
            $description->contains('analysis') => 'analytics',
            default => 'performance',
        };
    }
}
