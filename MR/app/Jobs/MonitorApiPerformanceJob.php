<?php

namespace App\Jobs;

use App\Models\Api;
use App\Models\ApiPerformanceLog;
use App\Services\CacheService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MonitorApiPerformanceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of seconds the job can run before timing out.
     */
    public int $timeout = 30;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 2;

    /**
     * The number of seconds to wait before retrying the job.
     */
    public int $retryAfter = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private int $apiId
    ) {
        $this->onQueue('monitoring');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $api = Api::findOrFail($this->apiId);
            
            if ($api->status !== 'monitored') {
                Log::info('Skipping API monitoring - not monitored', [
                    'api_id' => $this->apiId,
                    'status' => $api->status
                ]);
                return;
            }

            $startTime = microtime(true);
            
            // Make HTTP request
            $response = Http::timeout(10)->get($api->base_url);
            
            $endTime = microtime(true);
            $responseTime = ($endTime - $startTime) * 1000; // Convert to milliseconds

            $performanceData = [
                'api_id' => $api->id,
                'status_code' => $response->status(),
                'response_time_ms' => round($responseTime, 2),
                'payload_size_kb' => round(strlen($response->body()) / 1024, 2),
                'success' => $response->successful(),
                'monitored_at' => now(),
            ];

            // Store performance log
            $log = ApiPerformanceLog::create([
                'api_id' => $performanceData['api_id'],
                'status_code' => $performanceData['status_code'],
                'response_time_ms' => $performanceData['response_time_ms'],
                'payload_size_kb' => $performanceData['payload_size_kb'],
                'monitored_at' => $performanceData['monitored_at'],
            ]);

            // Cache performance data
            $cacheService = app(CacheService::class);
            $cacheService->cacheApiPerformance($api->id, $performanceData);

            // Invalidate API cache to force refresh
            $cacheService->invalidateApiCache($api->id);

            Log::info('API performance monitored successfully', [
                'api_id' => $api->id,
                'api_name' => $api->name,
                'response_time_ms' => $performanceData['response_time_ms'],
                'status_code' => $performanceData['status_code'],
                'success' => $performanceData['success'],
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to monitor API performance', [
                'api_id' => $this->apiId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $this->fail($e);
        }
    }

    /**
     * Get the tags that should be assigned to the job.
     */
    public function tags(): array
    {
        return ['api-monitoring', 'api-' . $this->apiId];
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('API monitoring job failed', [
            'api_id' => $this->apiId,
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);
    }
}
