<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use App\Models\Client;
use App\Models\Operation;
use App\Models\Api;
use App\Models\PerformanceMetric;
use App\Models\Alert;

class CacheService
{
    /**
     * Get cached active clients with relationships
     */
    public function getActiveClients(): mixed
    {
        return Cache::remember('clients.active_with_relations', 3600, function () {
            return Client::where('status', 'active')
                ->with(['operations', 'apis'])
                ->orderBy('name')
                ->get();
        });
    }

    /**
     * Get cached system statistics
     */
    public function getSystemStatistics(): array
    {
        return Cache::remember('system.statistics', 1800, function () {
            return [
                'total_clients' => Client::count(),
                'active_clients' => Client::where('status', 'active')->count(),
                'total_operations' => Operation::count(),
                'active_operations' => Operation::where('status', 'active')->count(),
                'scheduled_operations' => Operation::where('status', 'scheduled')->count(),
                'completed_operations' => Operation::where('status', 'completed')->count(),
                'total_apis' => Api::count(),
                'monitored_apis' => Api::where('status', 'monitored')->count(),
                'total_metrics' => PerformanceMetric::count(),
                'critical_metrics' => PerformanceMetric::where('status', 'critical')->count(),
                'warning_metrics' => PerformanceMetric::where('status', 'warning')->count(),
                'normal_metrics' => PerformanceMetric::where('status', 'normal')->count(),
                'total_alerts' => Alert::count(),
                'open_alerts' => Alert::where('status', 'open')->count(),
                'critical_alerts' => Alert::where('status', 'open')->where('severity', 'critical')->count(),
                'medium_alerts' => Alert::where('status', 'open')->where('severity', 'medium')->count(),
                'low_alerts' => Alert::where('status', 'open')->where('severity', 'low')->count(),
            ];
        });
    }

    /**
     * Get cached client statistics
     */
    public function getClientStatistics(int $clientId): array
    {
        return Cache::remember("client.{$clientId}.statistics", 900, function () use ($clientId) {
            $client = Client::findOrFail($clientId);
            
            return [
                'total_operations' => $client->operations()->count(),
                'active_operations' => $client->operations()->where('status', 'active')->count(),
                'scheduled_operations' => $client->operations()->where('status', 'scheduled')->count(),
                'completed_operations' => $client->operations()->where('status', 'completed')->count(),
                'total_apis' => $client->apis()->count(),
                'monitored_apis' => $client->apis()->where('status', 'monitored')->count(),
                'total_performance_metrics' => $client->operations()->withCount('performanceMetrics')->get()->sum('performance_metrics_count'),
                'critical_metrics' => $client->operations()->with('performanceMetrics')->get()
                    ->pluck('performanceMetrics')
                    ->flatten()
                    ->where('status', 'critical')
                    ->count(),
                'warning_metrics' => $client->operations()->with('performanceMetrics')->get()
                    ->pluck('performanceMetrics')
                    ->flatten()
                    ->where('status', 'warning')
                    ->count(),
                'normal_metrics' => $client->operations()->with('performanceMetrics')->get()
                    ->pluck('performanceMetrics')
                    ->flatten()
                    ->where('status', 'normal')
                    ->count(),
            ];
        });
    }

    /**
     * Get cached operation statistics
     */
    public function getOperationStatistics(int $operationId): array
    {
        return Cache::remember("operation.{$operationId}.statistics", 600, function () use ($operationId) {
            $operation = Operation::findOrFail($operationId);
            
            return [
                'total_metrics' => $operation->performanceMetrics()->count(),
                'critical_metrics' => $operation->performanceMetrics()->where('status', 'critical')->count(),
                'warning_metrics' => $operation->performanceMetrics()->where('status', 'warning')->count(),
                'normal_metrics' => $operation->performanceMetrics()->where('status', 'normal')->count(),
                'average_metric_value' => $operation->performanceMetrics()->avg('value'),
                'max_metric_value' => $operation->performanceMetrics()->max('value'),
                'min_metric_value' => $operation->performanceMetrics()->min('value'),
                'latest_metric' => $operation->performanceMetrics()->latest('recorded_at')->first(),
                'metrics_by_type' => $operation->performanceMetrics()
                    ->selectRaw('metric_type, COUNT(*) as count, AVG(value) as avg_value')
                    ->groupBy('metric_type')
                    ->get()
                    ->keyBy('metric_type'),
            ];
        });
    }

    /**
     * Get cached API statistics
     */
    public function getApiStatistics(int $apiId): array
    {
        return Cache::remember("api.{$apiId}.statistics", 300, function () use ($apiId) {
            $api = Api::findOrFail($apiId);
            $logs = $api->performanceLogs();
            
            return [
                'total_logs' => $logs->count(),
                'successful_requests' => $logs->where('status_code', '<', 400)->count(),
                'failed_requests' => $logs->where('status_code', '>=', 400)->count(),
                'average_response_time' => round($logs->avg('response_time_ms'), 2),
                'max_response_time' => $logs->max('response_time_ms'),
                'min_response_time' => $logs->min('response_time_ms'),
                'average_payload_size' => round($logs->avg('payload_size_kb'), 2),
                'error_rate' => $this->calculateErrorRate($logs),
                'latest_log' => $logs->latest('monitored_at')->first(),
                'logs_by_hour' => $logs
                    ->selectRaw('HOUR(monitored_at) as hour, COUNT(*) as count')
                    ->where('monitored_at', '>=', now()->subHours(24))
                    ->groupBy('hour')
                    ->orderBy('hour')
                    ->get()
                    ->keyBy('hour'),
            ];
        });
    }

    /**
     * Get cached performance metrics for time period
     */
    public function getPerformanceMetrics(string $period = '24h', int $limit = 100): mixed
    {
        $cacheKey = "performance_metrics.{$period}.{$limit}";
        
        return Cache::remember($cacheKey, 300, function () use ($period, $limit) {
            $query = PerformanceMetric::with(['operation.client', 'operation'])
                ->latest('recorded_at')
                ->limit($limit);
            
            // Filter by period
            switch ($period) {
                case '1h':
                    $query->where('recorded_at', '>=', now()->subHour());
                    break;
                case '6h':
                    $query->where('recorded_at', '>=', now()->subHours(6));
                    break;
                case '24h':
                    $query->where('recorded_at', '>=', now()->subDay());
                    break;
                case '7d':
                    $query->where('recorded_at', '>=', now()->subDays(7));
                    break;
                case '30d':
                    $query->where('recorded_at', '>=', now()->subDays(30));
                    break;
            }
            
            return $query->get();
        });
    }

    /**
     * Get cached active alerts
     */
    public function getActiveAlerts(): mixed
    {
        return Cache::remember('alerts.active', 600, function () {
            return Alert::where('status', 'open')
                ->with(['performanceMetric.operation.client'])
                ->orderBy('severity', 'desc')
                ->orderBy('triggered_at', 'desc')
                ->get();
        });
    }

    /**
     * Get cached dashboard data
     */
    public function getDashboardData(): array
    {
        return Cache::remember('dashboard.data', 900, function () {
            return [
                'statistics' => $this->getSystemStatistics(),
                'active_clients' => $this->getActiveClients(),
                'active_alerts' => $this->getActiveAlerts(),
                'recent_metrics' => $this->getPerformanceMetrics('1h', 10),
                'critical_metrics' => PerformanceMetric::where('status', 'critical')
                    ->with(['operation.client'])
                    ->latest('recorded_at')
                    ->take(5)
                    ->get(),
            ];
        });
    }

    /**
     * Cache API performance data
     */
    public function cacheApiPerformance(int $apiId, array $performanceData): void
    {
        $cacheKey = "api.{$apiId}.performance";
        Cache::put($cacheKey, $performanceData, 300);
    }

    /**
     * Cache operation metrics
     */
    public function cacheOperationMetrics(int $operationId, array $metrics): void
    {
        $cacheKey = "operation.{$operationId}.metrics";
        Cache::put($cacheKey, $metrics, 600);
    }

    /**
     * Invalidate client cache
     */
    public function invalidateClientCache(int $clientId): void
    {
        Cache::forget("client.{$clientId}.statistics");
        Cache::forget('clients.active_with_relations');
        Cache::forget('system.statistics');
    }

    /**
     * Invalidate operation cache
     */
    public function invalidateOperationCache(int $operationId): void
    {
        Cache::forget("operation.{$operationId}.statistics");
        Cache::forget("operation.{$operationId}.metrics");
        Cache::forget('system.statistics');
    }

    /**
     * Invalidate API cache
     */
    public function invalidateApiCache(int $apiId): void
    {
        Cache::forget("api.{$apiId}.statistics");
        Cache::forget("api.{$apiId}.performance");
        Cache::forget('system.statistics');
    }

    /**
     * Invalidate performance metrics cache
     */
    public function invalidatePerformanceCache(): void
    {
        $patterns = [
            'performance_metrics.1h.100',
            'performance_metrics.6h.100',
            'performance_metrics.24h.100',
            'performance_metrics.7d.100',
            'performance_metrics.30d.100',
        ];
        
        foreach ($patterns as $pattern) {
            Cache::forget($pattern);
        }
        
        Cache::forget('dashboard.data');
        Cache::forget('system.statistics');
    }

    /**
     * Invalidate alerts cache
     */
    public function invalidateAlertsCache(): void
    {
        Cache::forget('alerts.active');
        Cache::forget('dashboard.data');
        Cache::forget('system.statistics');
    }

    /**
     * Clear all cache
     */
    public function clearAllCache(): void
    {
        Cache::flush();
    }

    /**
     * Get cache statistics
     */
    public function getCacheStatistics(): array
    {
        if (config('cache.default') === 'redis') {
            $redis = Redis::connection();
            
            return [
                'redis_memory' => $redis->info('memory'),
                'redis_stats' => $redis->info('stats'),
                'redis_dbsize' => $redis->dbsize(),
            ];
        }
        
        return [
            'driver' => config('cache.default'),
            'message' => 'Cache statistics not available for this driver',
        ];
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

    /**
     * Warm up cache
     */
    public function warmUpCache(): void
    {
        // Preload commonly used data
        $this->getSystemStatistics();
        $this->getActiveClients();
        $this->getActiveAlerts();
        $this->getDashboardData();
        
        // Preload performance metrics
        $this->getPerformanceMetrics('1h', 50);
        $this->getPerformanceMetrics('24h', 100);
    }

    /**
     * Get cache hit rate (Redis only)
     */
    public function getCacheHitRate(): float
    {
        if (config('cache.default') === 'redis') {
            $stats = Redis::connection()->info('stats');
            $hits = $stats['keyspace_hits'] ?? 0;
            $misses = $stats['keyspace_misses'] ?? 0;
            $total = $hits + $misses;
            
            return $total > 0 ? round(($hits / $total) * 100, 2) : 0.0;
        }
        
        return 0.0;
    }
}
