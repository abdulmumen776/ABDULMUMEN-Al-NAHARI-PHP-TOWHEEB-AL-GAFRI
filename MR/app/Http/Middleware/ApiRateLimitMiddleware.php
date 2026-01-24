<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ApiRateLimitMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $limit = '60', string $window = '1'): Response
    {
        $key = $this->getRateLimitKey($request);
        $maxAttempts = (int) $limit;
        $decayMinutes = (int) $window;

        // Check if the request should be rate limited
        if ($this->shouldRateLimit($request)) {
            $attempts = $this->getAttempts($key, $decayMinutes);

            if ($attempts >= $maxAttempts) {
                Log::warning('Rate limit exceeded', [
                    'key' => $key,
                    'attempts' => $attempts,
                    'limit' => $maxAttempts,
                    'ip' => $request->ip(),
                    'endpoint' => $request->path(),
                    'user_agent' => $request->userAgent(),
                ]);

                return $this->rateLimitResponse($maxAttempts, $decayMinutes);
            }

            $this->incrementAttempts($key, $decayMinutes);
        }

        $response = $next($request);

        // Add rate limit headers
        $response->headers->set('X-RateLimit-Limit', $maxAttempts);
        $response->headers->set('X-RateLimit-Remaining', max(0, $maxAttempts - $this->getAttempts($key, $decayMinutes)));
        $response->headers->set('X-RateLimit-Reset', $this->getResetTime($decayMinutes));

        return $response;
    }

    /**
     * Get the rate limit key for the request.
     */
    private function getRateLimitKey(Request $request): string
    {
        // Use API token if available, otherwise use IP
        if ($request->has('api_token')) {
            $tokenId = $request->api_token->id;
            return "api_rate_limit:token:{$tokenId}";
        }

        return "api_rate_limit:ip:{$request->ip()}";
    }

    /**
     * Determine if the request should be rate limited.
     */
    private function shouldRateLimit(Request $request): bool
    {
        // Don't rate limit health checks
        if ($request->path() === 'api/health') {
            return false;
        }

        // Don't rate limit if user has unlimited access
        if ($request->has('api_token') && $request->api_token->can('unlimited')) {
            return false;
        }

        return true;
    }

    /**
     * Get the number of attempts for the given key.
     */
    private function getAttempts(string $key, int $decayMinutes): int
    {
        try {
            return (int) Redis::get($key) ?? 0;
        } catch (\Exception $e) {
            Log::error('Failed to get rate limit attempts', [
                'key' => $key,
                'error' => $e->getMessage(),
            ]);
            return 0;
        }
    }

    /**
     * Increment the attempts for the given key.
     */
    private function incrementAttempts(string $key, int $decayMinutes): void
    {
        try {
            Redis::incr($key);
            Redis::expire($key, $decayMinutes * 60);
        } catch (\Exception $e) {
            Log::error('Failed to increment rate limit attempts', [
                'key' => $key,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Get the reset time for the rate limit.
     */
    private function getResetTime(int $decayMinutes): int
    {
        return now()->addMinutes($decayMinutes)->timestamp;
    }

    /**
     * Return a rate limit response.
     */
    private function rateLimitResponse(int $maxAttempts, int $decayMinutes): Response
    {
        return response()->json([
            'success' => false,
            'message' => 'Too many requests. Please try again later.',
            'error_code' => 'RATE_LIMIT_EXCEEDED',
            'data' => [
                'limit' => $maxAttempts,
                'reset_in_minutes' => $decayMinutes,
                'retry_after' => $decayMinutes * 60,
            ],
        ], 429);
    }
}
