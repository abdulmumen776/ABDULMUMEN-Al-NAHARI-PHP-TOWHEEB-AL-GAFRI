<?php

namespace App\Services;

use App\Models\Api;
use App\Models\ApiPerformanceLog;
use App\Models\PerformanceMetric;
use App\Models\PerformanceDataset;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class PerformanceMonitoringService
{
    /**
     * Process 3.1: Monitor Server Performance Data
     */
    public function monitorServerPerformance(): array
    {
        try {
            $serverMetrics = [
                'cpu_usage' => $this->getCpuUsage(),
                'memory_usage' => $this->getMemoryUsage(),
                'disk_usage' => $this->getDiskUsage(),
                'network_io' => $this->getNetworkIO(),
                'active_connections' => $this->getActiveConnections(),
                'timestamp' => now()->toISOString(),
            ];

            Log::info('Server performance data collected', $serverMetrics);

            return $serverMetrics;

        } catch (\Exception $e) {
            Log::error('Failed to monitor server performance', [
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }

    /**
     * Process 3.2: Monitor API Performance
     */
    public function monitorApiPerformance(Api $api): array
    {
        try {
            $startTime = microtime(true);
            
            $response = Http::timeout(10)->get($api->base_url);
            
            $endTime = microtime(true);
            $responseTime = ($endTime - $startTime) * 1000; // Convert to milliseconds

            $statusCode = $response->status();
            $body = $response->body();

            $performanceData = [
                'api_id' => $api->id,
                'status_code' => $statusCode,
                'response_time_ms' => round($responseTime, 2),
                'payload_size_kb' => round(strlen($body) / 1024, 2),
                'success' => $response->successful(),
                'timestamp' => now()->toISOString(),
            ];

            // Store performance log
            $this->storeApiPerformanceLog($performanceData);

            Log::info('API performance monitored', [
                'api_id' => $api->id,
                'response_time' => $performanceData['response_time_ms'],
                'status_code' => $performanceData['status_code']
            ]);

            return $performanceData;

        } catch (\Exception $e) {
            Log::error('Failed to monitor API performance', [
                'api_id' => $api->id,
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }

    /**
     * Process 3.3: Aggregate Performance Data
     */
    public function aggregatePerformanceData(array $serverData, array $apiData): array
    {
        try {
            $aggregatedMetrics = [
                'server_metrics' => $this->aggregateServerMetrics($serverData),
                'api_metrics' => $this->aggregateApiMetrics($apiData),
                'overall_health_score' => $this->calculateHealthScore($serverData, $apiData),
                'performance_trends' => $this->calculateTrends($serverData, $apiData),
                'aggregated_at' => now()->toISOString(),
            ];

            Log::info('Performance data aggregated successfully', [
                'health_score' => $aggregatedMetrics['overall_health_score']
            ]);

            return $aggregatedMetrics;

        } catch (\Exception $e) {
            Log::error('Failed to aggregate performance data', [
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }

    /**
     * Process 3.4: Calculate Performance Data
     */
    public function calculatePerformanceDataset(array $serverData, array $apiData): PerformanceDataset
    {
        try {
            $calculatedMetrics = $this->performCalculations($serverData, $apiData);
            
            $dataset = PerformanceDataset::create([
                'dataset_name' => 'performance_dataset_' . now()->format('Y-m-d_H-i-s'),
                'server_performance_data' => $serverData,
                'api_performance_data' => $apiData,
                'calculated_metrics' => $calculatedMetrics,
                'generated_at' => now(),
            ]);

            Log::info('Performance dataset calculated and stored', [
                'dataset_id' => $dataset->id,
                'metrics_count' => count($calculatedMetrics)
            ]);

            return $dataset;

        } catch (\Exception $e) {
            Log::error('Failed to calculate performance dataset', [
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }

    /**
     * Get CPU usage percentage
     */
    private function getCpuUsage(): float
    {
        // Simplified CPU usage calculation
        $load = sys_getloadavg();
        return $load ? round($load[0] * 100, 2) : 0.0;
    }

    /**
     * Get memory usage percentage
     */
    private function getMemoryUsage(): array
    {
        $memoryUsage = memory_get_usage(true);
        $memoryLimit = $this->parseMemoryLimit(ini_get('memory_limit'));
        
        return [
            'used' => round($memoryUsage / 1024 / 1024, 2), // MB
            'limit' => round($memoryLimit / 1024 / 1024, 2), // MB
            'percentage' => $memoryLimit > 0 ? round(($memoryUsage / $memoryLimit) * 100, 2) : 0,
        ];
    }

    /**
     * Get disk usage information
     */
    private function getDiskUsage(): array
    {
        $totalSpace = disk_total_space('/');
        $freeSpace = disk_free_space('/');
        $usedSpace = $totalSpace - $freeSpace;
        
        return [
            'total_gb' => round($totalSpace / 1024 / 1024 / 1024, 2),
            'used_gb' => round($usedSpace / 1024 / 1024 / 1024, 2),
            'free_gb' => round($freeSpace / 1024 / 1024 / 1024, 2),
            'percentage' => round(($usedSpace / $totalSpace) * 100, 2),
        ];
    }

    /**
     * Get network I/O statistics
     */
    private function getNetworkIO(): array
    {
        // Simplified network monitoring
        return [
            'connections' => $this->getActiveConnections(),
            'bytes_sent' => 0, // Would need system-level access
            'bytes_received' => 0, // Would need system-level access
        ];
    }

    /**
     * Get active connections count
     */
    private function getActiveConnections(): int
    {
        // Simplified connection counting
        return 0; // Would need system-level access for accurate count
    }

    /**
     * Parse PHP memory limit string
     */
    private function parseMemoryLimit(string $limit): int
    {
        $limit = strtolower($limit);
        $multiplier = 1;
        
        if (str_ends_with($limit, 'g')) {
            $multiplier = 1024 * 1024 * 1024;
            $limit = substr($limit, 0, -1);
        } elseif (str_ends_with($limit, 'm')) {
            $multiplier = 1024 * 1024;
            $limit = substr($limit, 0, -1);
        } elseif (str_ends_with($limit, 'k')) {
            $multiplier = 1024;
            $limit = substr($limit, 0, -1);
        }
        
        return (int) $limit * $multiplier;
    }

    /**
     * Store API performance log
     */
    private function storeApiPerformanceLog(array $performanceData): void
    {
        ApiPerformanceLog::create([
            'api_id' => $performanceData['api_id'],
            'status_code' => $performanceData['status_code'],
            'response_time_ms' => $performanceData['response_time_ms'],
            'payload_size_kb' => $performanceData['payload_size_kb'],
            'monitored_at' => now(),
        ]);
    }

    /**
     * Aggregate server metrics
     */
    private function aggregateServerMetrics(array $serverData): array
    {
        return [
            'avg_cpu' => $serverData['cpu_usage'],
            'memory_pressure' => $serverData['memory_usage']['percentage'],
            'disk_pressure' => $serverData['disk_usage']['percentage'],
            'connection_load' => $serverData['active_connections'],
        ];
    }

    /**
     * Aggregate API metrics
     */
    private function aggregateApiMetrics(array $apiData): array
    {
        if (empty($apiData)) {
            return [];
        }

        $responseTimes = array_column($apiData, 'response_time_ms');
        $successRates = array_map(fn($data) => $data['success'] ? 1 : 0, $apiData);

        return [
            'avg_response_time' => round(array_sum($responseTimes) / count($responseTimes), 2),
            'max_response_time' => max($responseTimes),
            'min_response_time' => min($responseTimes),
            'success_rate' => round((array_sum($successRates) / count($successRates)) * 100, 2),
            'total_apis' => count($apiData),
        ];
    }

    /**
     * Calculate overall health score
     */
    private function calculateHealthScore(array $serverData, array $apiData): float
    {
        $cpuScore = max(0, 100 - $serverData['cpu_usage']);
        $memoryScore = max(0, 100 - $serverData['memory_usage']['percentage']);
        $diskScore = max(0, 100 - $serverData['disk_usage']['percentage']);
        
        $serverScore = ($cpuScore + $memoryScore + $diskScore) / 3;
        
        if (!empty($apiData)) {
            $apiMetrics = $this->aggregateApiMetrics($apiData);
            $apiScore = $apiMetrics['success_rate'];
            
            return round(($serverScore + $apiScore) / 2, 2);
        }
        
        return round($serverScore, 2);
    }

    /**
     * Calculate performance trends
     */
    private function calculateTrends(array $serverData, array $apiData): array
    {
        // Simplified trend calculation
        return [
            'cpu_trend' => 'stable',
            'memory_trend' => 'stable',
            'api_response_trend' => 'stable',
            'overall_trend' => 'stable',
        ];
    }

    /**
     * Perform detailed calculations on performance data
     */
    private function performCalculations(array $serverData, array $apiData): array
    {
        return [
            'performance_score' => $this->calculateHealthScore($serverData, $apiData),
            'resource_efficiency' => $this->calculateResourceEfficiency($serverData),
            'api_reliability' => $this->calculateApiReliability($apiData),
            'system_stability' => $this->calculateSystemStability($serverData, $apiData),
            'bottlenecks' => $this->identifyBottlenecks($serverData, $apiData),
        ];
    }

    /**
     * Calculate resource efficiency
     */
    private function calculateResourceEfficiency(array $serverData): float
    {
        $cpuEfficiency = max(0, 100 - $serverData['cpu_usage']);
        $memoryEfficiency = max(0, 100 - $serverData['memory_usage']['percentage']);
        
        return round(($cpuEfficiency + $memoryEfficiency) / 2, 2);
    }

    /**
     * Calculate API reliability
     */
    private function calculateApiReliability(array $apiData): float
    {
        if (empty($apiData)) {
            return 100.0;
        }
        
        $successCount = array_sum(array_map(fn($data) => $data['success'] ? 1 : 0, $apiData));
        
        return round(($successCount / count($apiData)) * 100, 2);
    }

    /**
     * Calculate system stability
     */
    private function calculateSystemStability(array $serverData, array $apiData): float
    {
        $resourceStability = $this->calculateResourceEfficiency($serverData);
        $apiStability = $this->calculateApiReliability($apiData);
        
        return round(($resourceStability + $apiStability) / 2, 2);
    }

    /**
     * Identify performance bottlenecks
     */
    private function identifyBottlenecks(array $serverData, array $apiData): array
    {
        $bottlenecks = [];
        
        if ($serverData['cpu_usage'] > 80) {
            $bottlenecks[] = 'High CPU usage';
        }
        
        if ($serverData['memory_usage']['percentage'] > 80) {
            $bottlenecks[] = 'High memory usage';
        }
        
        if ($serverData['disk_usage']['percentage'] > 80) {
            $bottlenecks[] = 'High disk usage';
        }
        
        foreach ($apiData as $api) {
            if ($api['response_time_ms'] > 5000) { // 5 seconds
                $bottlenecks[] = "Slow API response: {$api['api_id']}";
            }
        }
        
        return $bottlenecks;
    }
}
