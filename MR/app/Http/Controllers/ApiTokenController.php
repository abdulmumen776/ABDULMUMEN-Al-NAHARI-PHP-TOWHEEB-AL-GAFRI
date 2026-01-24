<?php

namespace App\Http\Controllers;

use App\Models\ApiToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ApiTokenController extends Controller
{
    /**
     * Display a listing of the user's API tokens.
     */
    public function index()
    {
        return view('tokens.index');
    }

    /**
     * Store a newly created API token in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'abilities' => 'nullable|array',
                'abilities.*' => 'string',
                'expires_at' => 'nullable|date|after:now',
            ]);

            // Set default abilities if not provided
            $abilities = $validated['abilities'] ?? ['*'];

            // Validate abilities
            $this->validateAbilities($abilities);

            $token = ApiToken::create([
                'user_id' => Auth::id(),
                'token' => ApiToken::generateToken(),
                'name' => $validated['name'],
                'abilities' => $abilities,
                'expires_at' => $validated['expires_at'],
            ]);

            Log::info('API token created', [
                'token_id' => $token->id,
                'user_id' => Auth::id(),
                'token_name' => $token->name,
                'abilities' => $abilities,
            ]);

            return response()->json([
                'success' => true,
                'token' => [
                    'id' => $token->id,
                    'name' => $token->name,
                    'token' => $token->token, // Only show full token on creation
                    'formatted_token' => $token->formatted_token,
                    'abilities' => $token->abilities,
                    'expires_at' => $token->expires_at,
                    'created_at' => $token->created_at,
                ],
                'message' => 'API token created successfully'
            ], 201);

        } catch (\Exception $e) {
            Log::error('Failed to create API token', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create API token',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified API token.
     */
    public function show(ApiToken $apiToken): JsonResponse
    {
        try {
            // Ensure the user owns this token
            if ($apiToken->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            return response()->json([
                'success' => true,
                'token' => [
                    'id' => $apiToken->id,
                    'name' => $apiToken->name,
                    'formatted_token' => $apiToken->formatted_token,
                    'abilities' => $apiToken->abilities,
                    'status' => $apiToken->status,
                    'status_color' => $apiToken->status_color,
                    'last_used_at' => $apiToken->last_used_at,
                    'days_since_last_use' => $apiToken->days_since_last_use,
                    'expires_at' => $apiToken->expires_at,
                    'remaining_days' => $apiToken->remaining_days,
                    'created_at' => $apiToken->created_at,
                    'usage_stats' => $apiToken->usage_stats,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to retrieve API token', [
                'token_id' => $apiToken->id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve API token',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified API token.
     */
    public function update(Request $request, ApiToken $apiToken): JsonResponse
    {
        try {
            // Ensure the user owns this token
            if ($apiToken->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'abilities' => 'nullable|array',
                'abilities.*' => 'string',
                'expires_at' => 'nullable|date|after:now',
            ]);

            // Validate abilities if provided
            if (isset($validated['abilities'])) {
                $this->validateAbilities($validated['abilities']);
            }

            $apiToken->update([
                'name' => $validated['name'],
                'abilities' => $validated['abilities'] ?? $apiToken->abilities,
                'expires_at' => $validated['expires_at'],
            ]);

            Log::info('API token updated', [
                'token_id' => $apiToken->id,
                'user_id' => Auth::id(),
                'changes' => $validated,
            ]);

            return response()->json([
                'success' => true,
                'token' => [
                    'id' => $apiToken->id,
                    'name' => $apiToken->name,
                    'formatted_token' => $apiToken->formatted_token,
                    'abilities' => $apiToken->abilities,
                    'expires_at' => $apiToken->expires_at,
                    'updated_at' => $apiToken->updated_at,
                ],
                'message' => 'API token updated successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to update API token', [
                'token_id' => $apiToken->id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update API token',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified API token.
     */
    public function destroy(ApiToken $apiToken): JsonResponse
    {
        try {
            // Ensure the user owns this token
            if ($apiToken->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            $tokenName = $apiToken->name;
            $apiToken->delete();

            Log::info('API token deleted', [
                'token_id' => $apiToken->id,
                'user_id' => Auth::id(),
                'token_name' => $tokenName,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'API token deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to delete API token', [
                'token_id' => $apiToken->id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete API token',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Revoke the specified API token.
     */
    public function revoke(ApiToken $apiToken): JsonResponse
    {
        try {
            // Ensure the user owns this token
            if ($apiToken->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            $tokenName = $apiToken->name;
            $apiToken->revoke();

            Log::info('API token revoked', [
                'token_id' => $apiToken->id,
                'user_id' => Auth::id(),
                'token_name' => $tokenName,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'API token revoked successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to revoke API token', [
                'token_id' => $apiToken->id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to revoke API token',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get API token statistics.
     */
    public function statistics(): JsonResponse
    {
        try {
            $userTokens = Auth::user()->apiTokens();

            $stats = [
                'total_tokens' => $userTokens->count(),
                'active_tokens' => $userTokens->valid()->count(),
                'expired_tokens' => $userTokens->expired()->count(),
                'inactive_tokens' => $userTokens->filter(fn($token) => $token->isInactive())->count(),
                'tokens_expiring_soon' => $userTokens->valid()
                    ->where('expires_at', '<=', now()->addDays(7))
                    ->count(),
                'never_used_tokens' => $userTokens->whereNull('last_used_at')->count(),
                'tokens_by_status' => [
                    'active' => $userTokens->valid()->count(),
                    'expired' => $userTokens->expired()->count(),
                    'inactive' => $userTokens->filter(fn($token) => $token->isInactive())->count(),
                ],
                'recent_activity' => $userTokens->whereNotNull('last_used_at')
                    ->latest('last_used_at')
                    ->take(5)
                    ->get()
                    ->map(function ($token) {
                        return [
                            'id' => $token->id,
                            'name' => $token->name,
                            'last_used_at' => $token->last_used_at,
                            'days_since_last_use' => $token->days_since_last_use,
                        ];
                    }),
            ];

            return response()->json([
                'success' => true,
                'statistics' => $stats
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to get API token statistics', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to get API token statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validate token abilities.
     */
    private function validateAbilities(array $abilities): void
    {
        $validAbilities = [
            '*',
            'actions:read',
            'actions:write',
            'actions:delete',
            'operations:read',
            'operations:write',
            'operations:delete',
            'apis:read',
            'apis:write',
            'apis:delete',
            'performance:read',
            'performance:write',
            'dashboards:read',
            'dashboards:write',
            'alerts:read',
            'alerts:write',
            'patterns:read',
            'patterns:write',
            'unlimited',
        ];

        foreach ($abilities as $ability) {
            if (!in_array($ability, $validAbilities)) {
                throw new \InvalidArgumentException("Invalid ability: {$ability}");
            }
        }
    }
}
