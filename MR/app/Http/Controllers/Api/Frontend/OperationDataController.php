<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Operation;
use Illuminate\Http\JsonResponse;

class OperationDataController extends Controller
{
    /**
     * List operations with client details for the frontend.
     */
    public function index(): JsonResponse
    {
        $operations = Operation::with(['client'])
            ->latest('created_at')
            ->get()
            ->map(fn (Operation $operation) => $this->transformOperation($operation));

        return response()->json([
            'operations' => $operations,
        ]);
    }

    /**
     * Latest operations for dashboard widgets.
     */
    public function recent(): JsonResponse
    {
        $operations = Operation::with('client')
            ->latest('created_at')
            ->take(5)
            ->get()
            ->map(fn (Operation $operation) => $this->transformOperation($operation));

        return response()->json([
            'operations' => $operations,
        ]);
    }

    /**
     * Aggregate operation statistics.
     */
    public function statistics(): JsonResponse
    {
        $total = Operation::count();
        $active = Operation::where('status', 'active')->count();
        $completed = Operation::where('status', 'completed')->count();
        $successRate = $total > 0 ? round(($completed / $total) * 100, 2) . '%' : '0%';

        return response()->json([
            'total_operations' => $total,
            'active_operations' => $active,
            'completed_operations' => $completed,
            'success_rate' => $successRate,
        ]);
    }

    /**
     * Format operation payload for the UI expectations.
     */
    private function transformOperation(Operation $operation): array
    {
        return [
            'id' => $operation->id,
            'client_id' => $operation->client_id,
            'client_name' => optional($operation->client)->name,
            'name' => $operation->name,
            'description' => $operation->description,
            'type' => $operation->type,
            'status' => $operation->status,
            'status_text' => $this->statusLabel($operation->status),
            'scheduled_at' => optional($operation->scheduled_at)->toDateTimeString(),
            'duration' => $this->formatDuration($operation),
            'created_at' => optional($operation->created_at)->toDateString(),
        ];
    }

    private function statusLabel(?string $status): string
    {
        return match ($status) {
            'active' => 'نشط',
            'scheduled' => 'مجدول',
            'completed' => 'مكتمل',
            'cancelled' => 'ملغي',
            default => 'غير معروف',
        };
    }

    /**
     * Build a readable duration string.
     */
    private function formatDuration(Operation $operation): ?string
    {
        if (!$operation->scheduled_at || !$operation->created_at) {
            return null;
        }

        $minutes = abs($operation->scheduled_at->diffInMinutes($operation->created_at, false));

        if ($minutes === 0) {
            return 'immediate';
        }

        if ($minutes < 60) {
            return $minutes . ' min';
        }

        $hours = intdiv($minutes, 60);
        $remainingMinutes = $minutes % 60;

        return $remainingMinutes > 0
            ? sprintf('%dh %dmin', $hours, $remainingMinutes)
            : sprintf('%dh', $hours);
    }
}
