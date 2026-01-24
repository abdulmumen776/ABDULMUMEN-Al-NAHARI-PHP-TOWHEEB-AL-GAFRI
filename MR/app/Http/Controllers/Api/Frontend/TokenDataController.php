<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ApiToken;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class TokenDataController extends Controller
{
    /**
     * Return the authenticated user's tokens.
     */
    public function index(): JsonResponse
    {
        $tokens = $this->userTokens()
            ->latest('created_at')
            ->get()
            ->map(fn (ApiToken $token) => $this->transformToken($token));

        return response()->json([
            'tokens' => $tokens,
        ]);
    }

    /**
     * Token statistics for dashboard cards.
     */
    public function statistics(): JsonResponse
    {
        $tokens = $this->userTokens()->get();

        $active = $tokens->filter(fn (ApiToken $token) => !$token->isExpired())->count();
        $expired = $tokens->filter(fn (ApiToken $token) => $token->isExpired())->count();
        $inactive = $tokens->filter(fn (ApiToken $token) => $token->isInactive())->count();

        return response()->json([
            'total_tokens' => $tokens->count(),
            'active_tokens' => $active,
            'expired_tokens' => $expired,
            'inactive_tokens' => $inactive,
        ]);
    }

    /**
     * Recently created tokens.
     */
    public function recent(): JsonResponse
    {
        $tokens = $this->userTokens()
            ->latest('created_at')
            ->take(5)
            ->get()
            ->map(fn (ApiToken $token) => $this->transformToken($token));

        return response()->json([
            'tokens' => $tokens,
        ]);
    }

    private function userTokens()
    {
        return Auth::user()
            ? Auth::user()->apiTokens()
            : ApiToken::query()->whereRaw('1 = 0');
    }

    private function transformToken(ApiToken $token): array
    {
        return [
            'id' => $token->id,
            'name' => $token->name,
            'token' => $token->token,
            'abilities' => $token->abilities,
            'status' => $token->status,
            'created_at' => optional($token->created_at)->toDateString(),
            'expires_at' => optional($token->expires_at)->toDateString(),
        ];
    }
}
