<?php

namespace App\Http\Middleware;

use App\Models\ApiToken;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiTokenMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $this->getTokenFromRequest($request);

        if (!$token) {
            return $this->unauthorizedResponse('API token required');
        }

        $apiToken = ApiToken::findValidToken($token);

        if (!$apiToken) {
            Log::warning('Invalid or expired API token used', [
                'token' => substr($token, 0, 8) . '...',
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'endpoint' => $request->path(),
            ]);

            return $this->unauthorizedResponse('Invalid or expired API token');
        }

        // Update last used timestamp
        $apiToken->updateLastUsedAt();

        // Store the token and user in the request for later use
        $request->merge([
            'api_token' => $apiToken,
            'current_user' => $apiToken->user,
        ]);

        // Log API usage
        Log::info('API token used', [
            'token_id' => $apiToken->id,
            'user_id' => $apiToken->user_id,
            'ip' => $request->ip(),
            'endpoint' => $request->path(),
            'method' => $request->method(),
        ]);

        return $next($request);
    }

    /**
     * Extract token from request.
     */
    private function getTokenFromRequest(Request $request): ?string
    {
        // Check Authorization header first
        $authorization = $request->header('Authorization');
        if ($authorization && str_starts_with($authorization, 'Bearer ')) {
            return substr($authorization, 7);
        }

        // Check for token in query parameters
        $token = $request->query('api_token');
        if ($token) {
            return $token;
        }

        // Check for token in form data
        $token = $request->input('api_token');
        if ($token) {
            return $token;
        }

        return null;
    }

    /**
     * Return unauthorized response.
     */
    private function unauthorizedResponse(string $message): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'error_code' => 'UNAUTHORIZED',
        ], 401);
    }
}
