<?php

namespace App\Http\Controllers;

use App\Services\PerformanceMonitoringService;
use App\Models\Api;
use App\Models\PerformanceDataset;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class PerformanceMonitoringController extends Controller
{
    public function __construct(
        private PerformanceMonitoringService $performanceMonitoringService
    ) {}

    /**
     * Process 3.1: Monitor Server Performance Data
     */
    public function monitorServer(): JsonResponse
    {
        try {
            $serverData = $this->performanceMonitoringService->monitorServerPerformance();

            return response()->json([
                'success' => true,
                'server_performance' => $serverData,
                'message' => 'Server performance data collected successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Server performance monitoring failed', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to monitor server performance',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process 3.2: Monitor API Performance
     */
    public function monitorApi(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'api_id' => 'required|integer|exists:apis,id'
            ]);

            $api = Api::findOrFail($validated['api_id']);
            $apiData = $this->performanceMonitoringService->monitorApiPerformance($api);

            return response()->json([
                'success' => true,
                'api_performance' => $apiData,
                'message' => 'API performance data collected successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('API performance monitoring failed', [
                'api_id' => $request->api_id ?? 'unknown',
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to monitor API performance',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Monitor all APIs
     */
    public function monitorAllApis(): JsonResponse
    {
        try {
            $apis = Api::where('status', '=', 'monitored')->get();
            $apiPerformanceData = [];

            foreach ($apis as $api) {
                try {
                    $apiPerformanceData[] = $this->performanceMonitoringService->monitorApiPerformance($api);
                } catch (\Exception $e) {
                    Log::warning('Failed to monitor individual API', [
                        'api_id' => $api->id,
                        'error' => $e->getMessage()
                    ]);
                    // Continue with other APIs
                }
            }

            return response()->json([
                'success' => true,
                'apis_monitored' => count($apiPerformanceData),
                'api_performance_data' => $apiPerformanceData,
                'message' => 'All APIs performance data collected successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('All APIs performance monitoring failed', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to monitor APIs performance',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process 3.3: Aggregate Performance Data
     */
    public function aggregatePerformance(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'server_data' => 'required|array',
                'api_data' => 'required|array',
            ]);

            $aggregatedData = $this->performanceMonitoringService->aggregatePerformanceData(
                $validated['server_data'],
                $validated['api_data']
            );

            return response()->json([
                'success' => true,
                'aggregated_performance' => $aggregatedData,
                'message' => 'Performance data aggregated successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Performance aggregation failed', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to aggregate performance data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process 3.4: Calculate Performance Dataset
     */
    public function calculateDataset(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'server_data' => 'required|array',
                'api_data' => 'required|array',
            ]);

            $dataset = $this->performanceMonitoringService->calculatePerformanceDataset(
                $validated['server_data'],
                $validated['api_data']
            );

            return response()->json([
                'success' => true,
                'dataset' => $dataset,
                'message' => 'Performance dataset calculated successfully'
            ], 201);

        } catch (\Exception $e) {
            Log::error('Performance dataset calculation failed', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to calculate performance dataset',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get performance datasets
     */
    public function getDatasets(): JsonResponse
    {
        try {
            $datasets = PerformanceDataset::latest('created_at')->paginate(20);

            return response()->json([
                'success' => true,
                'datasets' => $datasets
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to retrieve performance datasets', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve datasets',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get specific performance dataset
     */
    public function getDataset(int $datasetId): JsonResponse
    {
        try {
            $dataset = PerformanceDataset::findOrFail($datasetId);

            return response()->json([
                'success' => true,
                'dataset' => $dataset
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to retrieve performance dataset', [
                'dataset_id' => $datasetId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve dataset',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Complete performance monitoring workflow
     */
    public function completeMonitoring(): JsonResponse
    {
        try {
            // Process 3.1: Monitor Server Performance
            $serverData = $this->performanceMonitoringService->monitorServerPerformance();

            // Process 3.2: Monitor API Performance
            $apis = Api::where('status', '=', 'monitored')->get();
            $apiData = [];
            
            foreach ($apis as $api) {
                try {
                    $apiData[] = $this->performanceMonitoringService->monitorApiPerformance($api);
                } catch (\Exception $e) {
                    Log::warning('API monitoring failed in complete workflow', [
                        'api_id' => $api->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            // Process 3.3: Aggregate Performance Data
            $aggregatedData = $this->performanceMonitoringService->aggregatePerformanceData($serverData, $apiData);

            // Process 3.4: Calculate Performance Dataset
            $dataset = $this->performanceMonitoringService->calculatePerformanceDataset($serverData, $apiData);

            return response()->json([
                'success' => true,
                'monitoring_results' => [
                    'server_data' => $serverData,
                    'api_data' => $apiData,
                    'aggregated_data' => $aggregatedData,
                    'dataset' => $dataset
                ],
                'message' => 'Complete performance monitoring workflow executed successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Complete performance monitoring workflow failed', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to execute complete monitoring workflow',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
