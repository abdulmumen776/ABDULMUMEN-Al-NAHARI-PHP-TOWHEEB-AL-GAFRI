<?php

namespace App\Http\Controllers;

use App\Models\Operation;
use App\Models\Client;
use App\Models\PerformanceMetric;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class OperationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('operations.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::all();
        return view('operations.create', compact('clients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'client_id' => 'required|exists:clients,id',
                'name' => 'required|string|max:255',
                'type' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'status' => 'required|in:scheduled,active,completed,cancelled',
                'scheduled_at' => 'nullable|date',
            ]);

            $operation = Operation::create($validated);

            Log::info('Operation created successfully', [
                'operation_id' => $operation->id,
                'operation_name' => $operation->name,
                'client_id' => $operation->client_id
            ]);

            return response()->json([
                'success' => true,
                'operation' => $operation,
                'message' => 'Operation created successfully'
            ], 201);

        } catch (\Exception $e) {
            Log::error('Failed to create operation', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create operation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Operation $operation): JsonResponse
    {
        try {
            $operation->load(['client', 'performanceMetrics']);

            return response()->json([
                'success' => true,
                'operation' => $operation
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to retrieve operation', [
                'operation_id' => $operation->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve operation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Operation $operation)
    {
        $clients = Client::all();
        return view('operations.edit', compact('operation', 'clients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Operation $operation): JsonResponse
    {
        try {
            $validated = $request->validate([
                'client_id' => 'required|exists:clients,id',
                'name' => 'required|string|max:255',
                'type' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'status' => 'required|in:scheduled,active,completed,cancelled',
                'scheduled_at' => 'nullable|date',
            ]);

            $operation->update($validated);

            Log::info('Operation updated successfully', [
                'operation_id' => $operation->id,
                'operation_name' => $operation->name
            ]);

            return response()->json([
                'success' => true,
                'operation' => $operation,
                'message' => 'Operation updated successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to update operation', [
                'operation_id' => $operation->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update operation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Operation $operation): JsonResponse
    {
        try {
            $operationName = $operation->name;
            $operation->delete();

            Log::info('Operation deleted successfully', [
                'operation_id' => $operation->id,
                'operation_name' => $operationName
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Operation deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to delete operation', [
                'operation_id' => $operation->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete operation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get operations by client
     */
    public function byClient(Client $client): JsonResponse
    {
        try {
            $operations = $client->operations()->with('performanceMetrics')->get();

            return response()->json([
                'success' => true,
                'operations' => $operations
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to retrieve client operations', [
                'client_id' => $client->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve client operations',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get operation statistics
     */
    public function statistics(Operation $operation): JsonResponse
    {
        try {
            $stats = [
                'total_metrics' => $operation->performanceMetrics()->count(),
                'critical_metrics' => $operation->performanceMetrics()->where('status', 'critical')->count(),
                'warning_metrics' => $operation->performanceMetrics()->where('status', 'warning')->count(),
                'normal_metrics' => $operation->performanceMetrics()->where('status', 'normal')->count(),
                'average_metric_value' => $operation->performanceMetrics()->avg('value'),
                'latest_metric' => $operation->performanceMetrics()->latest('recorded_at')->first(),
            ];

            return response()->json([
                'success' => true,
                'statistics' => $stats
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to get operation statistics', [
                'operation_id' => $operation->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to get operation statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get operation performance metrics
     */
    public function performanceMetrics(Operation $operation): JsonResponse
    {
        try {
            $metrics = $operation->performanceMetrics()->latest('recorded_at')->get();

            return response()->json([
                'success' => true,
                'performance_metrics' => $metrics
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to retrieve operation performance metrics', [
                'operation_id' => $operation->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve performance metrics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update operation status
     */
    public function updateStatus(Request $request, Operation $operation): JsonResponse
    {
        try {
            $validated = $request->validate([
                'status' => 'required|in:scheduled,active,completed,cancelled',
            ]);

            $operation->update(['status' => $validated['status']]);

            Log::info('Operation status updated', [
                'operation_id' => $operation->id,
                'new_status' => $validated['status']
            ]);

            return response()->json([
                'success' => true,
                'operation' => $operation,
                'message' => 'Operation status updated successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to update operation status', [
                'operation_id' => $operation->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update operation status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get active operations
     */
    public function active(): JsonResponse
    {
        try {
            $activeOperations = Operation::where('status', 'active')
                ->with(['client', 'performanceMetrics'])
                ->get();

            return response()->json([
                'success' => true,
                'active_operations' => $activeOperations
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to retrieve active operations', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve active operations',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
