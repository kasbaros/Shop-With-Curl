<?php

    namespace App\Http\Middleware;

    use Closure;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Log;
    use Illuminate\Support\Str;

    class LogContextMiddleware
    {
        public function handle(Request $request, Closure $next)
        {
            $requestId = $request->header('X-Request-ID', Str::uuid());

            // Set global log context for this request
            $logContext = [
                'request_id' => $requestId,
                'user_id' => $request->user()?->id,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
            ];

            // Only add session_id if session is available
            if ($request->hasSession() && $request->session()->isStarted()) {
                $logContext['session_id'] = $request->session()->getId();
            }

            Log::withContext($logContext);

            // Store context in app container for use in exception handlers
            $appContext = [
                'request_id' => $requestId,
                'user_id' => $request->user()?->id,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
            ];

            // Only add session_id if session is available
            if ($request->hasSession() && $request->session()->isStarted()) {
                $appContext['session_id'] = $request->session()->getId();
            }

            app()->instance('log.context', $appContext);

            $response = $next($request);

            // Add request ID to response headers for debugging
            $response->headers->set('X-Request-ID', $requestId);

            return $response;
        }
    }
