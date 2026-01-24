<?php

namespace App\Services;

use App\Models\Action;
use App\Models\ActionAcknowledgment;
use App\Models\Client;
use Illuminate\Support\Facades\Log;

class ActionReceptionService
{
    /**
     * Process 1.1: Receive Action from client
     */
    public function receiveAction(array $actionData, int $clientId): Action
    {
        try {
            // Validate client exists
            $client = Client::findOrFail($clientId);
            
            // Create action record
            $action = Action::create([
                'client_id' => $clientId,
                'action_type' => $actionData['action_type'] ?? 'unknown',
                'raw_data' => $actionData,
                'status' => 'received',
                'received_at' => now(),
            ]);

            // Generate acknowledgment
            $this->generateAcknowledgment($action);

            Log::info('Action received successfully', [
                'action_id' => $action->id,
                'client_id' => $clientId,
                'action_type' => $action->action_type
            ]);

            return $action;

        } catch (\Exception $e) {
            Log::error('Failed to receive action', [
                'client_id' => $clientId,
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }

    /**
     * Generate acknowledgment for received action
     */
    private function generateAcknowledgment(Action $action): ActionAcknowledgment
    {
        return ActionAcknowledgment::create([
            'action_id' => $action->id,
            'acknowledgment_type' => 'received',
            'acknowledgment_data' => [
                'action_id' => $action->id,
                'status' => 'received',
                'timestamp' => now()->toISOString(),
                'message' => 'Action received successfully'
            ],
            'sent_at' => now(),
        ]);
    }

    /**
     * Get acknowledgment for action
     */
    public function getAcknowledgment(int $actionId): ?ActionAcknowledgment
    {
        return ActionAcknowledgment::where('action_id', $actionId)
            ->where('acknowledgment_type', 'received')
            ->first();
    }
}
