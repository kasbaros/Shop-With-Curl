<?php

    namespace App\Http\Middleware;

    use Closure;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Log;
    use Illuminate\Support\Facades\Auth;

    class SessionDebugMiddleware
    {
        public function handle(Request $request, Closure $next)
        {
            $sessionId = session()->getId();
            $isAuthenticated = Auth::check();
            $user = Auth::user();

            Log::info('Session Debug - Before Request', [
                'session_id' => $sessionId,
                'is_authenticated' => $isAuthenticated,
                'user_id' => $user?->id,
                'url' => $request->fullUrl(),
                'method' => $request->method(),
            ]);

            $response = $next($request);

            $sessionIdAfter = session()->getId();
            $isAuthenticatedAfter = Auth::check();
            $userAfter = Auth::user();

            Log::info('Session Debug - After Request', [
                'session_id_before' => $sessionId,
                'session_id_after' => $sessionIdAfter,
                'session_changed' => $sessionId !== $sessionIdAfter,
                'is_authenticated_before' => $isAuthenticated,
                'is_authenticated_after' => $isAuthenticatedAfter,
                'user_id_before' => $user?->id,
                'user_id_after' => $userAfter?->id,
                'auth_changed' => $isAuthenticated !== $isAuthenticatedAfter,
            ]);

            return $response;
        }
    }
