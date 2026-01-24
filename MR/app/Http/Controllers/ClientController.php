<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Operation;
use App\Models\Api;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = Client::withCount(['operations', 'apis'])
            ->orderByDesc('created_at')
            ->get()
            ->map(function (Client $client) {
                return [
                    'id' => $client->id,
                    'name' => $client->name,
                    'contact_email' => $client->contact_email,
                    'contact_phone' => $client->contact_phone,
                    'status' => $client->status,
                    'industry' => $client->industry,
                    'operations_count' => $client->operations_count,
                    'apis_count' => $client->apis_count,
                    'created_at' => optional($client->created_at)->toDateString(),
                ];
            });

        $statistics = [
            'total_clients' => Client::count(),
            'active_clients' => Client::where('status', 'active')->count(),
            'total_operations' => Operation::count(),
            'total_apis' => Api::count(),
        ];

        return view('clients.index', compact('clients', 'statistics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('clients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'industry' => 'nullable|string|max:255',
                'contact_email' => 'nullable|email|max:255',
                'contact_phone' => 'nullable|string|max:20',
                'status' => 'required|in:active,inactive,suspended',
            ]);

            $client = Client::create($validated);

            Log::info('Client created successfully', [
                'client_id' => $client->id,
                'client_name' => $client->name
            ]);

            // Check if request expects JSON (API call) or regular form submission
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'client' => $client,
                    'message' => 'Client created successfully'
                ], 201);
            }

            // Regular form submission - redirect with success message
            return redirect()
                ->route('clients.index')
                ->with('success', 'تم إنشاء العميل بنجاح');

        } catch (\Exception $e) {
            Log::error('Failed to create client', [
                'error' => $e->getMessage()
            ]);

            // Check if request expects JSON (API call) or regular form submission
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create client',
                    'error' => $e->getMessage()
                ], 500);
            }

            // Regular form submission - redirect back with error message
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'فشل في إنشاء العميل: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client): JsonResponse
    {
        try {
            $client->load(['operations', 'apis', 'operations.performanceMetrics']);

            return response()->json([
                'success' => true,
                'client' => $client
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to retrieve client', [
                'client_id' => $client->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve client',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'industry' => 'nullable|string|max:255',
                'contact_email' => 'nullable|email|max:255',
                'contact_phone' => 'nullable|string|max:20',
                'status' => 'required|in:active,inactive,suspended',
            ]);

            $client->update($validated);

            Log::info('Client updated successfully', [
                'client_id' => $client->id,
                'client_name' => $client->name
            ]);

            return response()->json([
                'success' => true,
                'client' => $client,
                'message' => 'Client updated successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to update client', [
                'client_id' => $client->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update client',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client): JsonResponse
    {
        try {
            $clientName = $client->name;
            $client->delete();

            Log::info('Client deleted successfully', [
                'client_id' => $client->id,
                'client_name' => $clientName
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Client deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to delete client', [
                'client_id' => $client->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete client',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get client statistics
     */
    public function statistics(Client $client): JsonResponse
    {
        try {
            $stats = [
                'total_operations' => $client->operations()->count(),
                'active_operations' => $client->operations()->where('status', 'active')->count(),
                'total_apis' => $client->apis()->count(),
                'monitored_apis' => $client->apis()->where('status', 'monitored')->count(),
                'total_performance_metrics' => $client->operations()->withCount('performanceMetrics')->get()->sum('performance_metrics_count'),
            ];

            return response()->json([
                'success' => true,
                'statistics' => $stats
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to get client statistics', [
                'client_id' => $client->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to get client statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get client dashboard data
     */
    public function dashboard(Client $client): JsonResponse
    {
        try {
            $dashboardData = [
                'client_info' => $client,
                'recent_operations' => $client->operations()->latest()->take(5)->get(),
                'active_apis' => $client->apis()->where('status', 'monitored')->get(),
                'performance_summary' => $this->getClientPerformanceSummary($client),
            ];

            return response()->json([
                'success' => true,
                'dashboard_data' => $dashboardData
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to get client dashboard', [
                'client_id' => $client->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to get client dashboard',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get client performance summary
     */
    private function getClientPerformanceSummary(Client $client): array
    {
        $metrics = $client->operations()->with('performanceMetrics')->get()
            ->pluck('performanceMetrics')
            ->flatten();

        return [
            'total_metrics' => $metrics->count(),
            'critical_metrics' => $metrics->where('status', 'critical')->count(),
            'warning_metrics' => $metrics->where('status', 'warning')->count(),
            'normal_metrics' => $metrics->where('status', 'normal')->count(),
            'average_value' => $metrics->avg('value'),
        ];
    }
}
