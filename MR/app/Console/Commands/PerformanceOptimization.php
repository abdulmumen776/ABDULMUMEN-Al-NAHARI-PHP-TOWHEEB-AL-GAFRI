<?php

namespace App\Console\Commands;

use App\Services\CacheService;
use App\Jobs\MonitorApiPerformanceJob;
use App\Jobs\AggregatePerformanceDataJob;
use App\Models\Api;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PerformanceOptimization extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'performance:optimize {--cleanup : Clean up old performance data} {--monitor : Start API monitoring} {--aggregate : Aggregate performance data}';

    /**
     * The console command description.
     */
    protected $description = 'Optimize system performance with caching, monitoring, and data aggregation';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting performance optimization...');

        try {
            $startTime = microtime(true);

            // Step 1: Warm up cache
            if (!$this->option('cleanup')) {
                $this->info('Step 1: Warming up cache...');
                $this->call('cache:warm-up');
            }

            // Step 2: Start API monitoring
            if ($this->option('monitor')) {
                $this->info('Step 2: Starting API monitoring...');
                $this->startApiMonitoring();
            }

            // Step 3: Aggregate performance data
            if ($this->option('aggregate')) {
                $this->info('Step 3: Aggregating performance data...');
                $this->aggregatePerformanceData();
            }

            // Step 4: Clean up old data
            if ($this->option('cleanup')) {
                $this->info('Step 4: Cleaning up old performance data...');
                $this->cleanupOldData();
            }

            // Step 5: Optimize database
            $this->info('Step 5: Optimizing database...');
            $this->optimizeDatabase();

            $endTime = microtime(true);
            $duration = round(($endTime - $startTime) * 1000, 2);

            $this->info('Performance optimization completed successfully!');
            $this->info("Total duration: {$duration}ms");

            // Show performance metrics
            $this->showPerformanceMetrics();

            Log::info('Performance optimization completed', [
                'duration_ms' => $duration,
                'cleanup' => $this->option('cleanup'),
                'monitor' => $this->option('monitor'),
                'aggregate' => $this->option('aggregate'),
            ]);

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('Performance optimization failed: ' . $e->getMessage());
            
            Log::error('Performance optimization failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return Command::FAILURE;
        }
    }

    /**
     * Start API monitoring jobs
     */
    private function startApiMonitoring(): void
    {
        $apis = Api::where('status', 'monitored')->get();
        
        $this->info("Found {$apis->count()} APIs to monitor");

        foreach ($apis as $api) {
            MonitorApiPerformanceJob::dispatch($api->id);
            $this->line("  - Queued monitoring for: {$api->name}");
        }

        $this->info('API monitoring jobs queued successfully');
    }

    /**
     * Aggregate performance data
     */
    private function aggregatePerformanceData(): void
    {
        // Queue aggregation jobs for different periods
        $periods = ['hourly', 'daily', 'weekly', 'monthly'];
        
        foreach ($periods as $period) {
            AggregatePerformanceDataJob::dispatch($period);
            $this->line("  - Queued {$period} aggregation");
        }

        $this->info('Performance data aggregation jobs queued successfully');
    }

    /**
     * Clean up old performance data
     */
    private function cleanupOldData(): void
    {
        $this->info('Cleaning up old API performance logs...');
        
        // Delete logs older than 30 days
        $deletedLogs = DB::table('api_performance_logs')
            ->where('monitored_at', '<', now()->subDays(30))
            ->delete();

        $this->info("Deleted {$deletedLogs} old API performance logs");

        // Clean up old action metadata
        $this->info('Cleaning up old action metadata...');
        
        $deletedMetadata = DB::table('action_metadata')
            ->where('created_at', '<', now()->subDays(90))
            ->delete();

        $this->info("Deleted {$deletedMetadata} old action metadata records");

        // Clean up old validation results
        $this->info('Cleaning up old validation results...');
        
        $deletedValidations = DB::table('validation_results')
            ->where('created_at', '<', now()->subDays(90))
            ->delete();

        $this->info("Deleted {$deletedValidations} old validation result records");
    }

    /**
     * Optimize database tables
     */
    private function optimizeDatabase(): void
    {
        $this->info('Optimizing database tables...');

        $tables = [
            'clients',
            'operations',
            'performance_metrics',
            'api_performance_logs',
            'actions',
            'action_metadata',
            'validation_results',
        ];

        foreach ($tables as $table) {
            try {
                DB::statement("OPTIMIZE TABLE {$table}");
                $this->line("  - Optimized: {$table}");
            } catch (\Exception $e) {
                $this->warn("Could not optimize table {$table}: {$e->getMessage()}");
            }
        }

        $this->info('Database optimization completed');
    }

    /**
     * Show performance metrics
     */
    private function showPerformanceMetrics(): void
    {
        $cacheService = app(CacheService::class);
        
        $this->newLine();
        $this->info('=== Performance Metrics ===');
        
        // Cache statistics
        $cacheStats = $cacheService->getCacheStatistics();
        $hitRate = $cacheService->getCacheHitRate();
        
        $this->info("Cache Hit Rate: {$hitRate}%");
        
        if (isset($cacheStats['redis_dbsize'])) {
            $this->info("Redis Keys: {$cacheStats['redis_dbsize']}");
        }

        // Database statistics
        $this->newLine();
        $this->info('Database Statistics:');
        
        $tables = [
            'clients' => 'Clients',
            'operations' => 'Operations',
            'performance_metrics' => 'Performance Metrics',
            'api_performance_logs' => 'API Logs',
            'actions' => 'Actions',
            'alerts' => 'Alerts',
        ];

        foreach ($tables as $table => $label) {
            try {
                $count = DB::table($table)->count();
                $this->line("  - {$label}: {$count}");
            } catch (\Exception $e) {
                $this->warn("  - {$label}: Error getting count");
            }
        }

        // Queue statistics
        $this->newLine();
        $this->info('Queue Statistics:');
        
        try {
            $pendingJobs = DB::table('jobs')->count();
            $failedJobs = DB::table('failed_jobs')->count();
            
            $this->line("  - Pending Jobs: {$pendingJobs}");
            $this->line("  - Failed Jobs: {$failedJobs}");
        } catch (\Exception $e) {
            $this->warn("  - Queue: Error getting statistics");
        }
    }
}
