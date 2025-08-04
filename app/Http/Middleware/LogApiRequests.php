<?php

    namespace App\Http\Middleware;

    use Closure;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Log;
    use Symfony\Component\HttpFoundation\Response;

    class LogApiRequests
    {
        public function handle(Request $request, Closure $next): Response
        {
            $startTime = microtime(true);

            $response = $next($request);

            $endTime = microtime(true);
            $executionTime = ($endTime - $startTime) * 1000; // Convert to milliseconds

            Log::channel('api_logs')->info('API Request', [
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'status_code' => $response->getStatusCode(),
                'execution_time_ms' => round($executionTime, 2),
                'memory_usage' => memory_get_peak_usage(true),
                'request_size' => strlen(json_encode($request->all())),
                'response_size' => strlen($response->getContent()),
                'headers' => $request->headers->all(),
                'parameters' => $request->all(),
            ]);

            return $response;
        }
    }
