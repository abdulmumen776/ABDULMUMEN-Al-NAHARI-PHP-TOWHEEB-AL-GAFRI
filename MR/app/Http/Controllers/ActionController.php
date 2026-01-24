<?php

namespace App\Http\Controllers;

use App\Services\ActionReceptionService;
use App\Services\MetadataExtractionService;
use App\Services\DataValidationService;
use App\Models\Action;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ActionController extends Controller
{
    public function __construct(
        private ActionReceptionService $actionReceptionService,
        private MetadataExtractionService $metadataExtractionService,
        private DataValidationService $dataValidationService
    ) {}

    /**
     * Process 1.1: Receive Action from client
     */
    public function receive(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'client_id' => 'required|integer|exists:clients,id',
                'action_type' => 'required|string',
                'action_data' => 'required|array',
            ]);

            // Process 1.1: Receive Action
            $action = $this->actionReceptionService->receiveAction(
                $validated['action_data'],
                $validated['client_id']
            );

            // Process 1.2: Extract Metadata
            $this->metadataExtractionService->extractMetadata($action);

            return response()->json([
                'success' => true,
                'action_id' => $action->id,
                'status' => 'received',
                'message' => 'Action received and processed successfully',
                'acknowledgment' => $this->actionReceptionService->getAcknowledgment($action->id)
            ], 201);

        } catch (\Exception $e) {
            Log::error('Action reception failed', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to process action',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Validate action data (Processes 2.1 & 2.2)
     */
    public function validate(int $actionId): JsonResponse
    {
        try {
            $action = Action::with(['enrichedAction'])->findOrFail($actionId);
            
            if ($action->enrichedAction->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Action metadata not found. Please extract metadata first.'
                ], 400);
            }

            // Process 2.1 & 2.2: Validate Client and Operands Data
            $validationResults = $this->dataValidationService->validateCompleteAction($action->enrichedAction->first());

            return response()->json([
                'success' => true,
                'action_id' => $actionId,
                'validation_results' => $validationResults,
                'message' => $validationResults['overall_valid'] 
                    ? 'Action validated successfully' 
                    : 'Action validation failed'
            ]);

        } catch (\Exception $e) {
            Log::error('Action validation failed', [
                'action_id' => $actionId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to validate action',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Get action details with metadata and validation results
     */
    public function show(int $actionId): JsonResponse
    {
        try {
            $action = Action::with([
                'client',
                'metadata',
                'enrichedAction.validationResults',
                'acknowledgments'
            ])->findOrFail($actionId);

            return response()->json([
                'success' => true,
                'action' => $action
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to retrieve action', [
                'action_id' => $actionId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve action',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Get acknowledgment for action
     */
    public function acknowledgment(int $actionId): JsonResponse
    {
        try {
            $acknowledgment = $this->actionReceptionService->getAcknowledgment($actionId);

            if (!$acknowledgment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Acknowledgment not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'acknowledgment' => $acknowledgment
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to retrieve acknowledgment', [
                'action_id' => $actionId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve acknowledgment',
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
