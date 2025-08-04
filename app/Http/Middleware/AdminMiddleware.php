<?php
//
//    namespace App\Http\Middleware;
//
//    use Closure;
//    use Illuminate\Http\Request;
//    use Illuminate\Support\Facades\Auth;
//    use Log;
//    use Symfony\Component\HttpFoundation\Response;
//
//    class AdminMiddleware
//    {
//        /**
//         * Handle an incoming request.
//         */
//        public function handle(Request $request, Closure $next): Response
//        {
//            if (!Auth::check()) {
//                Log::warning('Unauthenticated user attempted to access admin area', [
//                    'ip' => $request->ip(),
//                    'user_agent' => $request->userAgent(),
//                    'url' => $request->fullUrl(),
//                ]);
//
//                return redirect()->route('login')->with('error', 'Please log in to access this area.');
//            }
//
//            $user = Auth::user();
//
//            // Check if user has admin or developer role
//            if (!in_array($user->role, ['admin', 'developer'])) {
//                Log::warning('Non-admin user attempted to access admin area', [
//                    'user_id' => $user->id,
//                    'user_email' => $user->email,
//                    'user_role' => $user->role ?? 'none',
//                    'ip' => $request->ip(),
//                    'url' => $request->fullUrl(),
//                ]);
//
//                abort(403, 'Access denied. Admin privileges required.');
//            }
//
//            // Log successful admin access (optional - can be disabled in production)
//            if (config('app.debug')) {
//                Log::info('Admin user accessed admin area', [
//                    'user_id' => $user->id,
//                    'user_email' => $user->email,
//                    'user_role' => $user->role,
//                    'url' => $request->fullUrl(),
//                ]);
//            }
//
//            // Update last login info
//            $user->update([
//                'last_login_at' => now(),
//                'last_login_ip' => $request->ip(),
//            ]);
//
//            return $next($request);
//        }
//    }

namespace App\Http\Middleware;

use Closure;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $minimumRole = 'admin'): Response
    {
        if (!Auth::check()) {
            Log::warning('Unauthenticated user attempted to access admin area', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
            ]);

            return redirect()->route('login')->with('error', 'Please log in to access this area.');
        }

        $user = Auth::user();

        // Check role hierarchy: developer > admin > customer
        $hasAccess = match($minimumRole) {
            'developer' => $user->isDeveloper(),
            'admin' => $user->isAdmin() || $user->isDeveloper(), // Developer can access admin routes
            default => false
        };

        if (!$hasAccess) {
            Log::warning('Insufficient privileges for admin area access', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'user_role' => $user->role ?? 'none',
                'required_role' => $minimumRole,
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
            ]);

            abort(403, "Access denied. {$minimumRole} privileges required.");
        }

        // Log successful admin access (optional - can be disabled in production)
        if (config('app.debug')) {
            Log::info('Admin area accessed', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'user_role' => $user->role,
                'required_role' => $minimumRole,
                'url' => $request->fullUrl(),
            ]);
        }

        // Login info update removed to prevent session invalidation
        // $this->updateLoginInfoIfNeeded($user, $request);

        return $next($request);
    }

    /**
     * Update login info only if it's been more than 5 minutes since last update
     */
    private function updateLoginInfoIfNeeded($user, $request): void
    {
        try {
            // Only update if last_login_at is null or more than 5 minutes old
            if (!$user->last_login_at || $user->last_login_at->diffInMinutes(now()) > 5) {
                // Use DB::table to avoid triggering model events that might affect sessions
                DB::table('users')
                    ->where('id', $user->id)
                    ->update([
                        'last_login_at' => now(),
                        'last_login_ip' => $request->ip(),
                        'updated_at' => now(),
                    ]);
            }
        } catch (\Exception $e) {
            // Don't let login tracking break the middleware
            Log::warning('Failed to update login info', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
