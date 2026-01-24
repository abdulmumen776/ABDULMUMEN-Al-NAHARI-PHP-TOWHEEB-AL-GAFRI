<?php

namespace App\Http\Controllers;

use App\Services\PatternAnalysisService;
use App\Services\AlertManagementService;
use App\Models\PatternAnalysisResult;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class PatternAnalysisController extends Controller
{
    public function __construct(
        private PatternAnalysisService $patternAnalysisService,
        private AlertManagementService $alertManagementService
    ) {}

    /**
     * Process 5.2: Analyze Patterns from External APIs
     */
    public function analyzeApiPatterns(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'api_data' => 'required|array',
            ]);

            $analysisResult = $this->patternAnalysisService->analyzeApiPatterns($validated['api_data']);

            return response()->json([
                'success' => true,
                'analysis_result' => $analysisResult,
                'message' => 'API pattern analysis completed successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('API pattern analysis failed', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to analyze API patterns',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process 5.2: Analyze Patterns from Dashboard Metrics
     */
    public function analyzeDashboardPatterns(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'dashboard_metrics' => 'required|array',
            ]);

            $analysisResult = $this->patternAnalysisService->analyzeDashboardPatterns($validated['dashboard_metrics']);

            return response()->json([
                'success' => true,
                'analysis_result' => $analysisResult,
                'message' => 'Dashboard pattern analysis completed successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Dashboard pattern analysis failed', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to analyze dashboard patterns',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process 5.3: Analyze Patterns (General)
     */
    public function analyzePatterns(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'data' => 'required|array',
                'analysis_type' => 'sometimes|string',
            ]);

            $analysisType = $validated['analysis_type'] ?? 'general';
            $analysisResult = $this->patternAnalysisService->analyzePatterns($validated['data'], $analysisType);

            return response()->json([
                'success' => true,
                'analysis_result' => $analysisResult,
                'message' => 'Pattern analysis completed successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('General pattern analysis failed', [
                'analysis_type' => $validated['analysis_type'] ?? 'general',
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to analyze patterns',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process 6.1: Manage Alerts
     */
    public function manageAlerts(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'identified_patterns' => 'required|array',
                'performance_metrics' => 'sometimes|array',
            ]);

            $alerts = $this->alertManagementService->manageAlerts(
                $validated['identified_patterns'],
                $validated['performance_metrics'] ?? []
            );

            return response()->json([
                'success' => true,
                'alerts' => $alerts,
                'message' => 'Alert management completed successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Alert management failed', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to manage alerts',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Complete pattern analysis and alert management workflow
     */
    public function completeWorkflow(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'data' => 'required|array',
                'analysis_type' => 'sometimes|string',
                'performance_metrics' => 'sometimes|array',
            ]);

            $analysisType = $validated['analysis_type'] ?? 'general';

            // Process 5.2/5.3: Analyze Patterns
            $analysisResult = $this->patternAnalysisService->analyzePatterns($validated['data'], $analysisType);

            // Process 6.1: Manage Alerts
            $alerts = $this->alertManagementService->manageAlerts(
                $analysisResult->identified_patterns,
                $validated['performance_metrics'] ?? []
            );

            return response()->json([
                'success' => true,
                'workflow_results' => [
                    'analysis_result' => $analysisResult,
                    'alerts_generated' => $alerts,
                ],
                'message' => 'Complete pattern analysis and alert management workflow executed successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Complete workflow failed', [
                'analysis_type' => $validated['analysis_type'] ?? 'general',
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to execute complete workflow',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get pattern analysis results
     */
    public function getAnalysisResults(): JsonResponse
    {
        try {
            $results = PatternAnalysisResult::latest('created_at')->paginate(20);

            return response()->json([
                'success' => true,
                'analysis_results' => $results
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to retrieve analysis results', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve analysis results',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get specific analysis result
     */
    public function getAnalysisResult(int $analysisId): JsonResponse
    {
        try {
            $result = PatternAnalysisResult::findOrFail($analysisId);

            return response()->json([
                'success' => true,
                'analysis_result' => $result
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to retrieve analysis result', [
                'analysis_id' => $analysisId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve analysis result',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Get active alerts
     */
    public function getActiveAlerts(): JsonResponse
    {
        try {
            $alerts = $this->alertManagementService->getActiveAlerts();

            return response()->json([
                'success' => true,
                'active_alerts' => $alerts
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to retrieve active alerts', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve active alerts',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Resolve alert
     */
    public function resolveAlert(Request $request, int $alertId): JsonResponse
    {
        try {
            $validated = $request->validate([
                'resolution' => 'sometimes|string',
            ]);

            $resolution = $validated['resolution'] ?? 'Manual resolution';
            $success = $this->alertManagementService->resolveAlert($alertId, $resolution);

            if ($success) {
                return response()->json([
                    'success' => true,
                    'message' => 'Alert resolved successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to resolve alert'
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('Failed to resolve alert', [
                'alert_id' => $alertId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to resolve alert',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get alert statistics
     */
    public function getAlertStatistics(): JsonResponse
    {
        try {
            $statistics = $this->alertManagementService->getAlertStatistics();

            return response()->json([
                'success' => true,
                'alert_statistics' => $statistics
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to get alert statistics', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to get alert statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
