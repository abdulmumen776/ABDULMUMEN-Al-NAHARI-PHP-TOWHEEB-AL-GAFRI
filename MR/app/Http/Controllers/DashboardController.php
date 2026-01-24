<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use App\Models\Dashboard;
use App\Models\PerformanceMetric;
use App\Models\PerformanceDataset;
use App\Models\PatternAnalysisResult;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function __construct(
        private DashboardService $dashboardService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboards.index');
    }

    /**
     * Process 4.1: Format Metrics Data
     */
    public function formatMetrics(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'metrics' => 'required|array',
            ]);

            $formattedMetrics = $this->dashboardService->formatMetricsData($validated['metrics']);

            return response()->json([
                'success' => true,
                'formatted_metrics' => $formattedMetrics,
                'message' => 'Metrics formatted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to format metrics', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to format metrics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process 4.2: Generate Dashboard Metrics
     */
    public function generateMetrics(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'formatted_metrics' => 'required|array',
                'identified_patterns' => 'sometimes|array',
            ]);

            $dashboardMetrics = $this->dashboardService->generateDashboardMetrics(
                $validated['formatted_metrics'],
                $validated['identified_patterns'] ?? []
            );

            return response()->json([
                'success' => true,
                'dashboard_metrics' => $dashboardMetrics,
                'message' => 'Dashboard metrics generated successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to generate dashboard metrics', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to generate dashboard metrics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process 4.3: Render Dashboard Components
     */
    public function renderComponents(Request $request, int $dashboardId): JsonResponse
    {
        try {
            $validated = $request->validate([
                'dashboard_metrics' => 'required|array',
            ]);

            $dashboard = Dashboard::findOrFail($dashboardId);
            $components = $this->dashboardService->renderDashboardComponents(
                $validated['dashboard_metrics'],
                $dashboard
            );

            return response()->json([
                'success' => true,
                'components' => $components,
                'message' => 'Dashboard components rendered successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to render dashboard components', [
                'dashboard_id' => $dashboardId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to render dashboard components',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process 4.4: Render Alerts
     */
    public function renderAlerts(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'alerts_summary' => 'required|array',
            ]);

            $renderedAlerts = $this->dashboardService->renderAlerts($validated['alerts_summary']);

            return response()->json([
                'success' => true,
                'rendered_alerts' => $renderedAlerts,
                'message' => 'Alerts rendered successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to render alerts', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to render alerts',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Complete dashboard generation workflow
     */
    public function generateComplete(Request $request, int $dashboardId): JsonResponse
    {
        try {
            $validated = $request->validate([
                'metrics' => 'required|array',
                'identified_patterns' => 'sometimes|array',
            ]);

            $dashboard = Dashboard::findOrFail($dashboardId);

            // Process 4.1: Format Metrics Data
            $formattedMetrics = $this->dashboardService->formatMetricsData($validated['metrics']);

            // Process 4.2: Generate Dashboard Metrics
            $dashboardMetrics = $this->dashboardService->generateDashboardMetrics(
                $formattedMetrics,
                $validated['identified_patterns'] ?? []
            );

            // Process 4.3: Render Dashboard Components
            $components = $this->dashboardService->renderDashboardComponents($dashboardMetrics, $dashboard);

            // Process 4.4: Render Alerts
            $renderedAlerts = $this->dashboardService->renderAlerts($dashboardMetrics['alerts_summary']);

            return response()->json([
                'success' => true,
                'dashboard_results' => [
                    'formatted_metrics' => $formattedMetrics,
                    'dashboard_metrics' => $dashboardMetrics,
                    'components' => $components,
                    'rendered_alerts' => $renderedAlerts,
                ],
                'message' => 'Complete dashboard generation workflow executed successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Complete dashboard generation failed', [
                'dashboard_id' => $dashboardId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to execute complete dashboard generation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Dashboard $dashboard): JsonResponse
    {
        try {
            $dashboard->load(['visualizations']);

            return response()->json([
                'success' => true,
                'dashboard' => $dashboard
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to retrieve dashboard', [
                'dashboard_id' => $dashboard->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve dashboard',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dashboard $dashboard)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dashboard $dashboard)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dashboard $dashboard)
    {
        //
    }
}
