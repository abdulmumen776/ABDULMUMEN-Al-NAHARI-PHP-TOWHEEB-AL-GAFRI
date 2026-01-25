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
        // Get real statistics from the database with error handling
        try {
            $totalClients = \App\Models\Client::count();
        } catch (\Exception $e) {
            $totalClients = 0;
        }
        
        try {
            $activeOperations = \App\Models\Operation::where('status', 'active')->count();
        } catch (\Exception $e) {
            $activeOperations = 0;
        }
        
        try {
            $monitoredApis = \App\Models\Api::where('status', 'monitored')->count();
        } catch (\Exception $e) {
            $monitoredApis = 0;
        }
        
        try {
            $openAlerts = \App\Models\Alert::where('status', 'open')->count();
        } catch (\Exception $e) {
            $openAlerts = 0;
        }
        
        // Get recent activities
        $recentOperations = collect([]);
        try {
            $recentOperations = \App\Models\Operation::with('client', 'api')
                ->latest()
                ->take(10)
                ->get()
                ->map(function($operation) {
                    return [
                        'id' => $operation->id,
                        'type' => $operation->type ?? 'N/A',
                        'status' => $operation->status ?? 'unknown',
                        'client_name' => $operation->client ? $operation->client->name : 'N/A',
                        'api_name' => $operation->api ? $operation->api->name : 'N/A',
                        'created_at' => $operation->created_at ? $operation->created_at->format('Y-m-d H:i:s') : 'N/A',
                    ];
                });
        } catch (\Exception $e) {
            // Keep empty collection if there's an error
        }
        
        // Get system status
        $systemStatus = [
            'database' => $this->checkDatabaseStatus(),
            'redis_cache' => $this->checkRedisStatus(),
            'queue_worker' => $this->checkQueueWorkerStatus(),
            'api_monitoring' => $monitoredApis > 0 ? 'active' : 'inactive',
            'email_service' => $this->checkEmailServiceStatus(),
        ];
        
        // Get performance data for charts
        $performanceData = $this->getPerformanceData();
        
        return view('dashboard.index', compact(
            'totalClients',
            'activeOperations', 
            'monitoredApis',
            'openAlerts',
            'recentOperations',
            'systemStatus',
            'performanceData'
        ));
    }
    
    /**
     * Check database status
     */
    private function checkDatabaseStatus()
    {
        try {
            \DB::select('SELECT 1');
            return 'active';
        } catch (\Exception $e) {
            return 'error';
        }
    }
    
    /**
     * Check Redis status
     */
    private function checkRedisStatus()
    {
        try {
            // Check if Redis is configured
            if (!config('redis.default.host')) {
                return 'inactive';
            }
            
            // Check if Redis extension is loaded
            if (!extension_loaded('redis')) {
                return 'inactive';
            }
            
            // Try to connect to Redis
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
    
    /**
     * Check queue worker status
     */
    private function checkQueueWorkerStatus()
    {
        // This is a simplified check - in production you might want to check supervisor or other queue monitoring
        try {
            $failedJobs = \DB::table('failed_jobs')->count();
            return $failedJobs > 10 ? 'error' : 'active';
        } catch (\Exception $e) {
            return 'inactive';
        }
    }
    
    /**
     * Check email service status
     */
    private function checkEmailServiceStatus()
    {
        try {
            // Check if mail configuration is set
            $config = config('mail.default');
            return $config ? 'active' : 'inactive';
        } catch (\Exception $e) {
            return 'error';
        }
    }
    
    /**
     * Get performance data for charts
     */
    private function getPerformanceData()
    {
        $performanceData = [
            'response_time' => [],
            'success_rate' => []
        ];
        
        try {
            // Get API performance logs for the last 7 days
            $logs = \App\Models\ApiPerformanceLog::where('monitored_at', '>=', now()->subDays(7))
                ->orderBy('monitored_at')
                ->get()
                ->groupBy(function($log) {
                    return $log->monitored_at->format('Y-m-d');
                });
            
            $responseTimeData = [];
            $successRateData = [];
            
            foreach ($logs as $date => $dayLogs) {
                $responseTimeData[] = [
                    'date' => $date,
                    'value' => round($dayLogs->avg('response_time_ms'), 2)
                ];
                
                $successfulRequests = $dayLogs->where('status_code', '<', 400)->count();
                $successRate = $dayLogs->count() > 0 ? ($successfulRequests / $dayLogs->count()) * 100 : 0;
                $successRateData[] = [
                    'date' => $date,
                    'value' => round($successRate, 2)
                ];
            }
            
            $performanceData = [
                'response_time' => $responseTimeData,
                'success_rate' => $successRateData
            ];
        } catch (\Exception $e) {
            // Return empty data if there's an error
            Log::error('Failed to get performance data', ['error' => $e->getMessage()]);
        }
        
        return $performanceData;
    }

    /**
     * Generate a report
     */
    public function generateReport()
    {
        try {
            // Get current statistics
            $totalClients = \App\Models\Client::count();
            $activeOperations = \App\Models\Operation::where('status', 'active')->count();
            $monitoredApis = \App\Models\Api::where('status', 'monitored')->count();
            $openAlerts = \App\Models\Alert::where('status', 'open')->count();
            
            // Get performance data
            $performanceData = $this->getPerformanceData();
            
            // Generate HTML report
            $viewData = compact(
                'totalClients',
                'activeOperations',
                'monitoredApis',
                'openAlerts',
                'performanceData'
            );

            $html = view('reports.dashboard', $viewData)->render();

            return response($html)
                ->header('Content-Type', 'text/html')
                ->header('Content-Disposition', 'attachment; filename="dashboard-report-' . date('Y-m-d') . '.html"');
            
        } catch (\Exception $e) {
            Log::error('Failed to generate report', ['error' => $e->getMessage()]);
            
            // Return a simple HTML report if there's an error
            $html = $this->generateSimpleReport();
            return response($html)
                ->header('Content-Type', 'text/html')
                ->header('Content-Disposition', 'attachment; filename="dashboard-report-' . date('Y-m-d') . '.html"');
        }
    }
    
    /**
     * Generate a simple HTML report as fallback
     */
    private function generateSimpleReport()
    {
        $date = date('Y-m-d H:i:s');
        return "
        <!DOCTYPE html>
        <html dir='rtl' lang='ar'>
        <head>
            <meta charset='UTF-8'>
            <title>تقرير لوحة التحكم</title>
            <style>
                body { font-family: Arial, sans-serif; padding: 20px; direction: rtl; }
                .header { text-align: center; margin-bottom: 30px; }
                .stats { display: flex; justify-content: space-around; margin: 20px 0; }
                .stat { text-align: center; padding: 20px; border: 1px solid #ddd; border-radius: 8px; }
                .stat-value { font-size: 2em; font-weight: bold; color: #007bff; }
                .stat-label { color: #666; margin-top: 10px; }
            </style>
        </head>
        <body>
            <div class='header'>
                <h1>تقرير لوحة التحكم</h1>
                <p>تاريخ الإنشاء: {$date}</p>
            </div>
            <div class='stats'>
                <div class='stat'>
                    <div class='stat-value'>{$this->getStatWithFallback('Client::count')}</div>
                    <div class='stat-label'>إجمالي العملاء</div>
                </div>
                <div class='stat'>
                    <div class='stat-value'>{$this->getStatWithFallback('Operation::where', 'status', 'active', 'count')}</div>
                    <div class='stat-label'>العمليات النشطة</div>
                </div>
                <div class='stat'>
                    <div class='stat-value'>{$this->getStatWithFallback('Api::where', 'status', 'monitored', 'count')}</div>
                    <div class='stat-label'>الـ APIs المراقبة</div>
                </div>
                <div class='stat'>
                    <div class='stat-value'>{$this->getStatWithFallback('Alert::where', 'status', 'open', 'count')}</div>
                    <div class='stat-label'>التنبيهات المفتوحة</div>
                </div>
            </div>
        </body>
        </html>";
    }
    
    /**
     * Helper method to get statistics with fallback
     */
    private function getStatWithFallback($method, ...$args)
    {
        try {
            if ($method === 'Client::count') {
                return \App\Models\Client::count();
            } elseif ($method === 'Operation::where') {
                return \App\Models\Operation::where($args[0], $args[1])->count();
            } elseif ($method === 'Api::where') {
                return \App\Models\Api::where($args[0], $args[1])->count();
            } elseif ($method === 'Alert::where') {
                return \App\Models\Alert::where($args[0], $args[1])->count();
            }
            return 0;
        } catch (\Exception $e) {
            return 0;
        }
    }
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
