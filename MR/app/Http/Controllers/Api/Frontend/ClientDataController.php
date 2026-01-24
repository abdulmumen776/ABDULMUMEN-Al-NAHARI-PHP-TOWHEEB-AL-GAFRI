<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Api;
use App\Models\Client;
use App\Models\Operation;
use Illuminate\Http\JsonResponse;

class ClientDataController extends Controller
{
    /**
     * Return formatted clients for the frontend data tables.
     */
    public function index(): JsonResponse
    {
        $clients = Client::withCount(['operations', 'apis'])
            ->orderByDesc('created_at')
            ->get()
            ->map(function (Client $client) {
                return [
                    'id' => $client->id,
                    'name' => $client->name,
                    'contact_email' => $client->contact_email,
                    'contact_phone' => $client->contact_phone,
                    'status' => $client->status,
                    'industry' => $client->industry,
                    'operations_count' => $client->operations_count,
                    'apis_count' => $client->apis_count,
                    'created_at' => optional($client->created_at)->toDateString(),
                ];
            });

        return response()->json([
            'clients' => $clients,
        ]);
    }

    /**
     * Aggregate high level client statistics.
     */
    public function statistics(): JsonResponse
    {
        $totalClients = Client::count();
        $activeClients = Client::where('status', 'active')->count();

        return response()->json([
            'total_clients' => $totalClients,
            'active_clients' => $activeClients,
            'total_operations' => Operation::count(),
            'total_apis' => Api::count(),
        ]);
    }
}
