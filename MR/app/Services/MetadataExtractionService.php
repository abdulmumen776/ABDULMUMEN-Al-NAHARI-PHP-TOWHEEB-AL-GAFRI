<?php

namespace App\Services;

use App\Models\Action;
use App\Models\ActionMetadata;
use App\Models\EnrichedAction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class MetadataExtractionService
{
    /**
     * Process 1.2: Extract Metadata from action details
     */
    public function extractMetadata(Action $action): ActionMetadata
    {
        try {
            $rawData = $action->raw_data;
            $metadata = $this->processMetadata($rawData, $action);

            $actionMetadata = ActionMetadata::create([
                'action_id' => $action->id,
                'metadata' => $metadata,
                'extracted_at' => now(),
            ]);

            // Create enriched action with timestamped data
            $this->createEnrichedAction($action, $metadata);

            Log::info('Metadata extracted successfully', [
                'action_id' => $action->id,
                'metadata_keys' => array_keys($metadata)
            ]);

            return $actionMetadata;

        } catch (\Exception $e) {
            Log::error('Failed to extract metadata', [
                'action_id' => $action->id,
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }

    /**
     * Process raw data to extract meaningful metadata
     */
    private function processMetadata(array $rawData, Action $action): array
    {
        $metadata = [];

        // Extract timestamp information
        $metadata['timestamps'] = $this->extractTimestamps($rawData);
        
        // Extract location data if available
        $metadata['location'] = $this->extractLocationData($rawData);
        
        // Extract user/subject information
        $metadata['subject'] = $this->extractSubjectData($rawData);
        
        // Extract action parameters
        $metadata['parameters'] = $this->extractActionParameters($rawData);
        
        // Extract system information
        $metadata['system'] = $this->extractSystemData($rawData);
        
        // Add processing metadata
        $metadata['processing'] = [
            'processed_at' => now()->toISOString(),
            'action_type' => $action->action_type,
            'client_id' => $action->client_id,
            'data_hash' => md5(json_encode($rawData))
        ];

        return $metadata;
    }

    /**
     * Extract timestamp information from raw data
     */
    private function extractTimestamps(array $rawData): array
    {
        $timestamps = [];
        
        // Look for common timestamp fields
        $timestampFields = ['timestamp', 'created_at', 'time', 'date', 'datetime'];
        
        foreach ($timestampFields as $field) {
            if (isset($rawData[$field])) {
                $timestamps[$field] = $this->parseTimestamp($rawData[$field]);
            }
        }
        
        // Add current processing timestamp
        $timestamps['processed_at'] = now()->toISOString();
        
        return $timestamps;
    }

    /**
     * Extract location data from raw data
     */
    private function extractLocationData(array $rawData): array
    {
        $location = [];
        
        $locationFields = ['location', 'coordinates', 'lat', 'lng', 'latitude', 'longitude', 'address'];
        
        foreach ($locationFields as $field) {
            if (isset($rawData[$field])) {
                $location[$field] = $rawData[$field];
            }
        }
        
        return $location;
    }

    /**
     * Extract subject/user data from raw data
     */
    private function extractSubjectData(array $rawData): array
    {
        $subject = [];
        
        $subjectFields = ['user_id', 'subject_id', 'person_id', 'individual_id', 'name', 'identifier'];
        
        foreach ($subjectFields as $field) {
            if (isset($rawData[$field])) {
                $subject[$field] = $rawData[$field];
            }
        }
        
        return $subject;
    }

    /**
     * Extract action parameters from raw data
     */
    private function extractActionParameters(array $rawData): array
    {
        $parameters = [];
        
        // Look for parameter-like fields
        foreach ($rawData as $key => $value) {
            if (is_array($value) || (is_string($key) && str_starts_with($key, 'param_'))) {
                $parameters[$key] = $value;
            }
        }
        
        return $parameters;
    }

    /**
     * Extract system data from raw data
     */
    private function extractSystemData(array $rawData): array
    {
        $system = [];
        
        $systemFields = ['system', 'device', 'platform', 'version', 'source'];
        
        foreach ($systemFields as $field) {
            if (isset($rawData[$field])) {
                $system[$field] = $rawData[$field];
            }
        }
        
        return $system;
    }

    /**
     * Parse timestamp to standard format
     */
    private function parseTimestamp($timestamp): string
    {
        try {
            return Carbon::parse($timestamp)->toISOString();
        } catch (\Exception $e) {
            return now()->toISOString();
        }
    }

    /**
     * Create enriched action with metadata
     */
    private function createEnrichedAction(Action $action, array $metadata): EnrichedAction
    {
        $enrichedData = array_merge($action->raw_data, [
            'metadata' => $metadata,
            'enriched_at' => now()->toISOString(),
            'status' => 'enriched'
        ]);

        return EnrichedAction::create([
            'action_id' => $action->id,
            'client_id' => $action->client_id,
            'enriched_data' => $enrichedData,
            'validation_status' => 'pending',
            'enriched_at' => now(),
        ]);
    }
}
