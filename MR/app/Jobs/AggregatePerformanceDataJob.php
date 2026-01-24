<?php

namespace App\Jobs;

use App\Models\PerformanceMetric;
use App\Models\PerformanceDataset;
use App\Services\CacheService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AggregatePerformanceDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of seconds the job can run before timing out.
     */
    public int $timeout = 300;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 2;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private string $period = 'hourly'
    ) {
        $this->onQueue('aggregation');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Log::info('Starting performance data aggregation', [
                'period' => $this->period
            ]);

            $timeRange = $this->getTimeRange();
            $metrics = $this->getMetricsInTimeRange($timeRange);

            if ($metrics->isEmpty()) {
                Log::info('No metrics found for aggregation', [
                    'period' => $this->period,
                    'time_range' => $timeRange
                ]);
                return;
            }

            $dataset = $this->createAggregatedDataset($metrics, $timeRange);

            // Cache the aggregated data
            $cacheService = app(CacheService::class);
            $cacheService->invalidatePerformanceCache();

            Log::info('Performance data aggregation completed', [
                'period' => $this->period,
                'dataset_id' => $dataset->id,
                'metrics_count' => $metrics->count(),
                'time_range' => $timeRange
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to aggregate performance data', [
                'period' => $this->period,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $this->fail($e);
        }
    }

    /**
     * Get time range based on aggregation period
     */
    private function getTimeRange(): array
    {
        $now = now();
        
        switch ($this->period) {
            case 'hourly':
                return [
                    'start' => $now->copy()->subHour(),
                    'end' => $now,
                    'name' => 'hourly_' . $now->format('Y_m_d_H'),
                ];
            case 'daily':
                return [
                    'start' => $now->copy()->subDay(),
                    'end' => $now,
                    'name' => 'daily_' . $now->format('Y_m_d'),
                ];
            case 'weekly':
                return [
                    'start' => $now->copy()->subWeek(),
                    'end' => $now,
                    'name' => 'weekly_' . $now->format('Y_W'),
                ];
            case 'monthly':
                return [
                    'start' => $now->copy()->subMonth(),
                    'end' => $now,
                    'name' => 'monthly_' . $now->format('Y_m'),
                ];
            default:
                return [
                    'start' => $now->copy()->subHour(),
                    'end' => $now,
                    'name' => 'hourly_' . $now->format('Y_m_d_H'),
                ];
        }
    }

    /**
     * Get metrics within the specified time range
     */
    private function getMetricsInTimeRange(array $timeRange)
    {
        return PerformanceMetric::whereBetween('recorded_at', [$timeRange['start'], $timeRange['end']])
            ->with(['operation.client'])
            ->get();
    }

    /**
     * Create aggregated dataset
     */
    private function createAggregatedDataset($metrics, array $timeRange): PerformanceDataset
    {
        $aggregatedData = $this->aggregateMetricsByType($metrics);
        
        return PerformanceDataset::create([
            'dataset_name' => $timeRange['name'],
            'period_start' => $timeRange['start'],
            'period_end' => $timeRange['end'],
            'period_type' => $this->period,
            'total_metrics' => $metrics->count(),
            'aggregated_data' => $aggregatedData,
            'server_metrics' => $this->getServerMetrics($metrics),
            'api_metrics' => $this->getApiMetrics($metrics),
            'generated_at' => now(),
        ]);
    }

    /**
     * Aggregate metrics by type
     */
    private function aggregateMetricsByType($metrics): array
    {
        $grouped = $metrics->groupBy('metric_type');
        $aggregated = [];

        foreach ($grouped as $type => $typeMetrics) {
            $aggregated[$type] = [
                'count' => $typeMetrics->count(),
                'average_value' => round($typeMetrics->avg('value'), 2),
                'min_value' => $typeMetrics->min('value'),
                'max_value' => $typeMetrics->max('value'),
                'sum_value' => $typeMetrics->sum('value'),
                'status_distribution' => $this->getStatusDistribution($typeMetrics),
                'by_operation' => $this->aggregateByOperation($typeMetrics),
            ];
        }

        return $aggregated;
    }

    /**
     * Get status distribution
     */
    private function getStatusDistribution($metrics): array
    {
        $distribution = [
            'normal' => 0,
            'warning' => 0,
            'critical' => 0,
        ];

        foreach ($metrics as $metric) {
            $distribution[$metric->status]++;
        }

        return $distribution;
    }

    /**
     * Aggregate metrics by operation
     */
    private function aggregateByOperation($metrics): array
    {
        $byOperation = [];
        
        foreach ($metrics->groupBy('operation_id') as $operationId => $operationMetrics) {
            $operation = $operationMetrics->first()->operation;
            
            $byOperation[$operationId] = [
                'operation_name' => $operation->name,
                'client_name' => $operation->client->name,
                'count' => $operationMetrics->count(),
                'average_value' => round($operationMetrics->avg('value'), 2),
                'status_distribution' => $this->getStatusDistribution($operationMetrics),
            ];
        }

        return $byOperation;
    }

    /**
     * Get server metrics summary
     */
    private function getServerMetrics($metrics): array
    {
        $serverMetrics = $metrics->filter(function ($metric) {
            return in_array($metric->metric_type, ['cpu_usage', 'memory_usage', 'disk_usage', 'network_usage']);
        });

        if ($serverMetrics->isEmpty()) {
            return [
                'cpu' => ['avg' => 0, 'max' => 0, 'min' => 0],
                'memory' => ['avg' => 0, 'max' => 0, 'min' => 0],
                'disk' => ['avg' => 0, 'max' => 0, 'min' => 0],
                'network' => ['avg' => 0, 'max' => 0, 'min' => 0],
            ];
        }

        return [
            'cpu' => $this->calculateMetricStats($serverMetrics->where('metric_type', 'cpu_usage')),
            'memory' => $this->calculateMetricStats($serverMetrics->where('metric_type', 'memory_usage')),
            'disk' => $this->calculateMetricStats($serverMetrics->where('metric_type', 'disk_usage')),
            'network' => $this->calculateMetricStats($serverMetrics->where('metric_type', 'network_usage')),
        ];
    }

    /**
     * Get API metrics summary
     */
    private function getApiMetrics($metrics): array
    {
        $apiMetrics = $metrics->filter(function ($metric) {
            return in_array($metric->metric_type, ['api_response_time', 'api_error_rate', 'api_throughput']);
        });

        if ($apiMetrics->isEmpty()) {
            return [
                'response_time' => ['avg' => 0, 'max' => 0, 'min' => 0],
                'error_rate' => ['avg' => 0, 'max' => 0, 'min' => 0],
                'throughput' => ['avg' => 0, 'max' => 0, 'min' => 0],
            ];
        }

        return [
            'response_time' => $this->calculateMetricStats($apiMetrics->where('metric_type', 'api_response_time')),
            'error_rate' => $this->calculateMetricStats($apiMetrics->where('metric_type', 'api_error_rate')),
            'throughput' => $this->calculateMetricStats($apiMetrics->where('metric_type', 'api_throughput')),
        ];
    }

    /**
     * Calculate metric statistics
     */
    private function calculateMetricStats($metrics): array
    {
        if ($metrics->isEmpty()) {
            return ['avg' => 0, 'max' => 0, 'min' => 0];
        }

        return [
            'avg' => round($metrics->avg('value'), 2),
            'max' => $metrics->max('value'),
            'min' => $metrics->min('value'),
        ];
    }

    /**
     * Get the tags that should be assigned to the job.
     */
    public function tags(): array
    {
        return ['performance-aggregation', $this->period];
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Performance aggregation job failed', [
            'period' => $this->period,
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);
    }
}
