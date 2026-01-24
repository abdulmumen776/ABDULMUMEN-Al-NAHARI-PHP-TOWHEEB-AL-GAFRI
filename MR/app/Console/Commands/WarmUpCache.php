<?php

namespace App\Console\Commands;

use App\Services\CacheService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class WarmUpCache extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'cache:warm-up {--force : Force warm up even if cache exists}';

    /**
     * The console command description.
     */
    protected $description = 'Warm up application cache with commonly used data';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting cache warm up...');

        try {
            $cacheService = app(CacheService::class);

            if ($this->option('force')) {
                $this->info('Clearing existing cache...');
                $cacheService->clearAllCache();
            }

            $startTime = microtime(true);

            // Warm up system statistics
            $this->info('Warming up system statistics...');
            $cacheService->getSystemStatistics();

            // Warm up active clients
            $this->info('Warming up active clients...');
            $cacheService->getActiveClients();

            // Warm up active alerts
            $this->info('Warming up active alerts...');
            $cacheService->getActiveAlerts();

            // Warm up dashboard data
            $this->info('Warming up dashboard data...');
            $cacheService->getDashboardData();

            // Warm up performance metrics
            $this->info('Warming up performance metrics...');
            $cacheService->getPerformanceMetrics('1h', 50);
            $cacheService->getPerformanceMetrics('24h', 100);

            $endTime = microtime(true);
            $duration = round(($endTime - $startTime) * 1000, 2);

            // Get cache statistics
            $stats = $cacheService->getCacheStatistics();
            $hitRate = $cacheService->getCacheHitRate();

            $this->info('Cache warm up completed successfully!');
            $this->info("Duration: {$duration}ms");
            $this->info("Cache hit rate: {$hitRate}%");

            if (isset($stats['redis_dbsize'])) {
                $this->info("Redis keys: {$stats['redis_dbsize']}");
            }

            Log::info('Cache warm up completed', [
                'duration_ms' => $duration,
                'hit_rate' => $hitRate,
                'forced' => $this->option('force'),
            ]);

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('Failed to warm up cache: ' . $e->getMessage());
            
            Log::error('Cache warm up failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return Command::FAILURE;
        }
    }
}
