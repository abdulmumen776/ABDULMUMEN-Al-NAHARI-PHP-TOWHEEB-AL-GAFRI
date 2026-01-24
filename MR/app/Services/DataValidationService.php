<?php

namespace App\Services;

use App\Models\EnrichedAction;
use App\Models\ValidationResult;
use App\Models\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class DataValidationService
{
    /**
     * Process 2.1: Validate Client Data
     */
    public function validateClientData(EnrichedAction $enrichedAction): ValidationResult
    {
        try {
            $client = Client::findOrFail($enrichedAction->client_id);
            $enrichedData = $enrichedAction->enriched_data;
            
            $validationRules = $this->getClientValidationRules();
            $validator = Validator::make($enrichedData, $validationRules);
            
            $isValid = $validator->fails();
            $errors = $isValid ? [] : $validator->errors()->toArray();
            
            $validationResult = ValidationResult::create([
                'enriched_action_id' => $enrichedAction->id,
                'validation_type' => 'client_data',
                'is_valid' => $isValid,
                'validation_errors' => $errors,
                'validated_at' => now(),
            ]);

            // Update enriched action validation status
            $enrichedAction->update(['validation_status' => $isValid ? 'validated' : 'failed']);

            Log::info('Client data validation completed', [
                'enriched_action_id' => $enrichedAction->id,
                'is_valid' => $isValid,
                'errors_count' => count($errors)
            ]);

            return $validationResult;

        } catch (\Exception $e) {
            Log::error('Client data validation failed', [
                'enriched_action_id' => $enrichedAction->id,
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }

    /**
     * Process 2.2: Validate Operands Data
     */
    public function validateOperandsData(EnrichedAction $enrichedAction): ValidationResult
    {
        try {
            $enrichedData = $enrichedAction->enriched_data;
            
            $validationRules = $this->getOperandsValidationRules($enrichedAction->action->action_type);
            $validator = Validator::make($enrichedData, $validationRules);
            
            $isValid = $validator->fails();
            $errors = $isValid ? [] : $validator->errors()->toArray();
            
            $validationResult = ValidationResult::create([
                'enriched_action_id' => $enrichedAction->id,
                'validation_type' => 'operands_data',
                'is_valid' => $isValid,
                'validation_errors' => $errors,
                'validated_at' => now(),
            ]);

            Log::info('Operands data validation completed', [
                'enriched_action_id' => $enrichedAction->id,
                'action_type' => $enrichedAction->action->action_type,
                'is_valid' => $isValid,
                'errors_count' => count($errors)
            ]);

            return $validationResult;

        } catch (\Exception $e) {
            Log::error('Operands data validation failed', [
                'enriched_action_id' => $enrichedAction->id,
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }

    /**
     * Get validation rules for client data
     */
    private function getClientValidationRules(): array
    {
        return [
            'client_id' => 'required|exists:clients,id',
            'metadata.subject.identifier' => 'required|string',
            'metadata.timestamps.processed_at' => 'required|date',
            'action_type' => 'required|string|in:movement,access,activity,alert',
        ];
    }

    /**
     * Get validation rules for operands data based on action type
     */
    private function getOperandsValidationRules(string $actionType): array
    {
        $baseRules = [
            'metadata.location' => 'sometimes|array',
            'metadata.parameters' => 'sometimes|array',
        ];

        switch ($actionType) {
            case 'movement':
                return array_merge($baseRules, [
                    'metadata.location.coordinates' => 'required|array',
                    'metadata.location.coordinates.lat' => 'required|numeric|between:-90,90',
                    'metadata.location.coordinates.lng' => 'required|numeric|between:-180,180',
                ]);
                
            case 'access':
                return array_merge($baseRules, [
                    'metadata.parameters.access_point' => 'required|string',
                    'metadata.parameters.access_type' => 'required|string|in:entry,exit,attempt',
                ]);
                
            case 'activity':
                return array_merge($baseRules, [
                    'metadata.parameters.activity_type' => 'required|string',
                    'metadata.parameters.duration' => 'sometimes|numeric|min:0',
                ]);
                
            case 'alert':
                return array_merge($baseRules, [
                    'metadata.parameters.alert_level' => 'required|string|in:low,medium,high,critical',
                    'metadata.parameters.alert_message' => 'required|string',
                ]);
                
            default:
                return $baseRules;
        }
    }

    /**
     * Validate complete enriched action
     */
    public function validateCompleteAction(EnrichedAction $enrichedAction): array
    {
        $clientValidation = $this->validateClientData($enrichedAction);
        $operandsValidation = $this->validateOperandsData($enrichedAction);
        
        $overallValid = $clientValidation->is_valid && $operandsValidation->is_valid;
        
        // Update enriched action with final status
        $enrichedAction->update([
            'validation_status' => $overallValid ? 'validated' : 'failed'
        ]);

        return [
            'overall_valid' => $overallValid,
            'client_validation' => $clientValidation,
            'operands_validation' => $operandsValidation,
        ];
    }
}
