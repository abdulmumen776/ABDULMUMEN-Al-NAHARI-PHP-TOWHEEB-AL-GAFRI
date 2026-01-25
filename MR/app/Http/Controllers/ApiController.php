<?php

namespace App\Http\Controllers;

use App\Models\Api;
use App\Models\Client;
use App\Models\ApiPerformanceLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get all APIs with their client relationship
        $apis = \App\Models\Api::with('client', 'performanceLogs')
            ->latest()
            ->get()
            ->map(function($api) {
                // Calculate performance metrics for this API
                $logs = $api->performanceLogs;
                $avgResponseTime = $logs->avg('response_time_ms') ?? 0;
                $successRate = 0;
                
                if ($logs->count() > 0) {
                    $successfulRequests = $logs->where('status_code', '<', 400)->count();
                    $successRate = round(($successfulRequests / $logs->count()) * 100, 1);
                }
                
                return [
                    'id' => $api->id,
                    'name' => $api->name,
                    'base_url' => $api->base_url,
                    'endpoint' => $api->endpoint,
                    'method' => $api->method,
                    'status' => $api->status,
                    'avg_response_time' => round($avgResponseTime, 2),
                    'success_rate' => $successRate,
                    'created_at' => $api->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $api->updated_at->format('Y-m-d H:i:s'),
                    'client' => $api->client ? [
                        'id' => $api->client->id,
                        'name' => $api->client->name
                    ] : null
                ];
            });

        // Calculate overall statistics
        $totalApis = $apis->count();
        $activeApis = $apis->where('status', 'active')->count();
        $monitoredApis = $apis->where('status', 'monitored')->count();
        $errorApis = $apis->where('status', 'error')->count();
        
        // Calculate overall error rate
        $errorRate = $totalApis > 0 ? round(($errorApis / $totalApis) * 100, 1) : 0;
        
        // Calculate overall average response time and success rate
        $allLogs = \App\Models\ApiPerformanceLog::all();
        $overallAvgResponseTime = $allLogs->avg('response_time_ms') ?? 0;
        $overallSuccessRate = 0;
        
        if ($allLogs->count() > 0) {
            $successfulRequests = $allLogs->where('status_code', '<', 400)->count();
            $overallSuccessRate = round(($successfulRequests / $allLogs->count()) * 100, 1);
        }
        
        // Get all clients for the filter dropdown
        $clients = \App\Models\Client::select('id', 'name')->get();

        // Pass data to the view
        return view('apis.index', [
            'initialData' => [
                'apis' => $apis,
                'clients' => $clients,
                'statistics' => [
                    'total_apis' => $totalApis,
                    'active_apis' => $activeApis,
                    'monitored_apis' => $monitoredApis,
                    'error_rate' => $errorRate . '%',
                    'avg_response_time' => round($overallAvgResponseTime, 2),
                    'success_rate' => $overallSuccessRate
                ]
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::all();
        return view('apis.create', compact('clients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'client_id' => 'nullable|exists:clients,id',
                'name' => 'required|string|max:255',
                'base_url' => 'required|url',
                'endpoint' => 'required|string|max:255',
                'method' => 'required|in:GET,POST,PUT,PATCH,DELETE,OPTIONS',
                'owner' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'status' => 'required|in:monitored,inactive,error',
            ]);

            // Create the API with the validated data
            $api = Api::create($validated);

            // Load the client relationship
            $api->load('client');

            Log::info('API created successfully', [
                'api_id' => $api->id,
                'api_name' => $api->name,
                'client_id' => $api->client_id
            ]);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'api' => $api,
                    'message' => 'API created successfully',
                    'redirect' => route('apis.success', $api)
                ], 201);
            }

            return redirect()
                ->route('apis.success', $api)
                ->with('success', 'تم إنشاء الـ API بنجاح');

        } catch (\Exception $e) {
            Log::error('Failed to create API', [
                'error' => $e->getMessage()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create API',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['api' => 'فشل إنشاء الـ API'])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    /**
     * Display the success page for a newly created API.
     */
    public function success(Api $api)
    {
        try {
            // Load relationships
            $api->load(['client', 'performance_logs']);

            return view('apis.success', [
                'api' => $api,
                'success' => session('success', 'تم إنشاء الـ API بنجاح')
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to load API success page', [
                'api_id' => $api->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('apis.index')
                ->with('error', 'حدث خطأ أثناء تحميل صفحة النجاح');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Api $api, Request $request)
    {
        try {
            // Load relationships
            $api->load(['client', 'performance_logs']);

            // If it's an AJAX request, return JSON
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'api' => $api
                ]);
            }

            // For regular requests, return the view
            return view('apis.show', [
                'api' => $api,
                'success' => session('success')
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to retrieve API', [
                'api_id' => $api->id,
                'error' => $e->getMessage()
            ]);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to retrieve API',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'حدث خطأ أثناء تحميل بيانات الـ API');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Api $api)
    {
        $clients = Client::all();
        
        return view('apis.edit', [
            'api' => $api,
            'clients' => $clients
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Api $api)
    {
        try {
            $validated = $request->validate([
                'client_id' => 'nullable|exists:clients,id',
                'name' => 'required|string|max:255',
                'base_url' => 'required|url',
                'owner' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'status' => 'required|in:monitored,inactive,error',
            ]);

            $api->update($validated);

            Log::info('API updated successfully', [
                'api_id' => $api->id,
                'api_name' => $api->name
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'api' => $api,
                    'message' => 'API updated successfully'
                ]);
            }

            return redirect()
                ->route('apis.edit', $api)
                ->with('success', 'تم تحديث الـ API بنجاح')
                ->with('updated_api', [
                    'id' => $api->id,
                    'name' => $api->name,
                    'base_url' => $api->base_url,
                    'status' => $api->status,
                    'client_id' => $api->client_id,
                ]);

        } catch (\Exception $e) {
            Log::error('Failed to update API', [
                'api_id' => $api->id,
                'error' => $e->getMessage()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update API',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['api' => 'فشل تحديث الـ API'])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Api $api): JsonResponse
    {
        try {
            $apiName = $api->name;
            $api->delete();

            Log::info('API deleted successfully', [
                'api_id' => $api->id,
                'api_name' => $apiName
            ]);

            return response()->json([
                'success' => true,
                'message' => 'API deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to delete API', [
                'api_id' => $api->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete API',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get APIs by client
     */
    public function byClient(Client $client): JsonResponse
    {
        try {
            $apis = $client->apis()->with('performanceLogs')->get();

            return response()->json([
                'success' => true,
                'apis' => $apis
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to retrieve client APIs', [
                'client_id' => $client->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve client APIs',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get monitored APIs
     */
    public function monitored(): JsonResponse
    {
        try {
            $monitoredApis = Api::where('status', 'monitored')
                ->with(['client', 'performanceLogs'])
                ->get();

            return response()->json([
                'success' => true,
                'monitored_apis' => $monitoredApis
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to retrieve monitored APIs', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve monitored APIs',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get API performance logs
     */
    public function performanceLogs(Api $api): JsonResponse
    {
        try {
            $logs = $api->performanceLogs()->latest('monitored_at')->get();

            return response()->json([
                'success' => true,
                'performance_logs' => $logs
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to retrieve API performance logs', [
                'api_id' => $api->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve performance logs',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get API statistics
     */
    public function statistics(Api $api): JsonResponse
    {
        try {
            $logs = $api->performanceLogs();
            
            $stats = [
                'total_logs' => $logs->count(),
                'successful_requests' => $logs->where('status_code', '<', 400)->count(),
                'failed_requests' => $logs->where('status_code', '>=', 400)->count(),
                'average_response_time' => $logs->avg('response_time_ms'),
                'max_response_time' => $logs->max('response_time_ms'),
                'min_response_time' => $logs->min('response_time_ms'),
                'average_payload_size' => $logs->avg('payload_size_kb'),
                'error_rate' => $this->calculateErrorRate($logs),
                'latest_log' => $logs->latest('monitored_at')->first(),
            ];

            return response()->json([
                'success' => true,
                'statistics' => $stats
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to get API statistics', [
                'api_id' => $api->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to get API statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update API status
     */
    public function updateStatus(Request $request, Api $api): JsonResponse
    {
        try {
            $validated = $request->validate([
                'status' => 'required|in:monitored,inactive,error',
            ]);

            $api->update(['status' => $validated['status']]);

            Log::info('API status updated', [
                'api_id' => $api->id,
                'new_status' => $validated['status']
            ]);

            return response()->json([
                'success' => true,
                'api' => $api,
                'message' => 'API status updated successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to update API status', [
                'api_id' => $api->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update API status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test API connectivity
     */
    public function test(Api $api): JsonResponse
    {
        try {
            $startTime = microtime(true);
            
            $response = \Illuminate\Support\Facades\Http::timeout(10)->get($api->base_url);
            
            $endTime = microtime(true);
            $responseTime = ($endTime - $startTime) * 1000;

            $testResult = [
                'status_code' => $response->status(),
                'response_time_ms' => round($responseTime, 2),
                'success' => $response->successful(),
                'payload_size_kb' => round(strlen($response->body()) / 1024, 2),
                'tested_at' => now()->toISOString(),
            ];

            // Store test result as performance log
            ApiPerformanceLog::create([
                'api_id' => $api->id,
                'status_code' => $testResult['status_code'],
                'response_time_ms' => $testResult['response_time_ms'],
                'payload_size_kb' => $testResult['payload_size_kb'],
                'monitored_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'test_result' => $testResult,
                'message' => 'API test completed successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('API test failed', [
                'api_id' => $api->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'API test failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculate error rate
     */
    private function calculateErrorRate($logs): float
    {
        $totalRequests = $logs->count();
        
        if ($totalRequests === 0) {
            return 0.0;
        }

        $failedRequests = $logs->where('status_code', '>=', 400)->count();
        
        return round(($failedRequests / $totalRequests) * 100, 2);
    }
}
