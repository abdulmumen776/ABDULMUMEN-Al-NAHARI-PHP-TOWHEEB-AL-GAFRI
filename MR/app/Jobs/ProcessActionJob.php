<?php

namespace App\Jobs;

use App\Services\ActionReceptionService;
use App\Services\MetadataExtractionService;
use App\Services\DataValidationService;
use App\Models\Action;
use App\Models\ActionMetadata;
use App\Models\EnrichedAction;
use App\Models\ValidationResult;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessActionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of seconds the job can run before timing out.
     */
    public int $timeout = 120;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     */
    public int $retryAfter = 5;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private array $actionData,
        private int $clientId
    ) {
        $this->onQueue('actions');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Log::info('Processing action job started', [
                'client_id' => $this->clientId,
                'action_data_keys' => array_keys($this->actionData)
            ]);

            // Step 1: Extract metadata
            $action = $this->createAction();
            $metadata = $this->extractMetadata($action);
            
            // Step 2: Validate data
            $this->validateData($metadata->enrichedAction);

            // Step 3: Update action status
            $action->update([
                'status' => 'processed',
                'processed_at' => now(),
            ]);

            Log::info('Action processed successfully', [
                'action_id' => $action->id,
                'client_id' => $this->clientId
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to process action', [
                'client_id' => $this->clientId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Update action status to failed
            if (isset($action)) {
                $action->update([
                    'status' => 'failed',
                    'processed_at' => now(),
                ]);
            }

            $this->fail($e);
        }
    }

    /**
     * Create action record
     */
    private function createAction(): Action
    {
        return Action::create([
            'client_id' => $this->clientId,
            'action_type' => $this->actionData['action_type'] ?? 'unknown',
            'raw_data' => $this->actionData,
            'status' => 'processing',
            'received_at' => now(),
        ]);
    }

    /**
     * Extract metadata from action
     */
    private function extractMetadata(Action $action): ActionMetadata
    {
        $metadataService = app(MetadataExtractionService::class);
        return $metadataService->extractMetadata($action);
    }

    /**
     * Validate enriched action data
     */
    private function validateData(EnrichedAction $enrichedAction): void
    {
        $validationService = app(DataValidationService::class);
        $validationService->validateCompleteAction($enrichedAction);
    }

    /**
     * Get the tags that should be assigned to the job.
     */
    public function tags(): array
    {
        return ['action-processing', 'client-' . $this->clientId];
    }

    /**
     * Calculate the number of seconds a job should wait before timing out.
     */
    public function retryUntil(): int
    {
        return 3;
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Action processing job failed', [
            'client_id' => $this->clientId,
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);
    }
}
