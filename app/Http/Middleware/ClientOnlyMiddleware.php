<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ClientOnlyMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            // Let auth middleware handle redirects when combined, but log for visibility
            Log::warning('Unauthenticated user attempted to access client-only route', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
            ]);
            return redirect()->route('login');
        }

        $user = Auth::user();

        // If user is admin or developer, block access to client-only routes
        if ($user->isAdmin() || $user->isDeveloper()) {
            Log::channel('enhanced_security')->warning('Privileged user blocked from client-only route', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'user_role' => $user->role ?? 'none',
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
            ]);

            // Prefer redirect to admin dashboard to avoid confusion
            if (route('admin.dashboard', [], false)) {
                return redirect()->route('admin.dashboard')
                    ->with('info', 'You are signed in as a privileged user and cannot access client pages.');
            }

            abort(403, 'Administrators and developers cannot access client-only pages.');
        }

        return $next($request);
    }
}
