<?php

    namespace App\Http\Middleware;

    use Closure;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Log;
    use Symfony\Component\HttpFoundation\Response;

    class DeveloperMiddleware
    {
        /**
         * Handle an incoming request.
         */
        public function handle(Request $request, Closure $next): Response
        {
            if (!Auth::check()) {
                Log::warning('Unauthenticated user attempted to access developer area', [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'url' => $request->fullUrl(),
                ]);

                return redirect()->route('login')->with('error', 'Please log in to access this area.');
            }

            $user = Auth::user();

            if (!$user->isDeveloper()) {
                Log::warning('Non-developer user attempted to access developer area', [
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'user_role' => $user->role ?? 'none',
                    'ip' => $request->ip(),
                    'url' => $request->fullUrl(),
                ]);

                abort(403, 'Access denied. Developer privileges required.');
            }

            Log::info('Developer area accessed', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'url' => $request->fullUrl(),
            ]);

            return $next($request);
        }
    }
