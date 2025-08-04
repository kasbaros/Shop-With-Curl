<?php

    namespace App\Traits;

    use Illuminate\Support\Facades\Log;

    trait LogsApiRequests
    {
        /**
         * Log API request with comprehensive details
         */
        public function logApiRequest(string $endpoint, string $method, array $context = []): void
        {
            $startTime = microtime(true);

            Log::channel('api_logs')->info('API Request Started', array_merge([
                'endpoint' => $endpoint,
                'method' => $method,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'user_id' => auth()->id(),
                'parameters' => request()->all(),
                'headers' => $this->sanitizeHeaders(request()->headers->all()),
                'timestamp' => now()->toISOString(),
                'correlation_id' => request()->header('X-Correlation-ID') ?? session()->getId(),
            ], $context));
        }

        /**
         * Log API response
         */
        public function logApiResponse(int $statusCode, array $context = []): void
        {
            $executionTime = microtime(true) - (app('api_request_start_time') ?? microtime(true));

            Log::channel('api_logs')->info('API Response', array_merge([
                'status_code' => $statusCode,
                'execution_time_ms' => round($executionTime * 1000, 2),
                'memory_usage' => memory_get_peak_usage(true),
                'timestamp' => now()->toISOString(),
            ], $context));
        }

        /**
         * Log API rate limiting events
         */
        public function logRateLimit(string $event, array $context = []): void
        {
            Log::channel('security_logs')->warning("Rate Limit: {$event}", array_merge([
                'ip_address' => request()->ip(),
                'user_id' => auth()->id(),
                'endpoint' => request()->path(),
                'method' => request()->method(),
                'user_agent' => request()->userAgent(),
                'timestamp' => now()->toISOString(),
            ], $context));
        }

        /**
         * Sanitize headers to remove sensitive information
         */
        private function sanitizeHeaders(array $headers): array
        {
            $sensitiveHeaders = ['authorization', 'cookie', 'x-api-key', 'x-auth-token'];

            foreach ($sensitiveHeaders as $header) {
                if (isset($headers[$header])) {
                    $headers[$header] = ['[REDACTED]'];
                }
            }

            return $headers;
        }
    }
