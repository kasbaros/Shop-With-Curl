<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * Blocks authenticated privileged users (admin/developer) from accessing guest-visible
 * storefront pages like categories and product details, while allowing true guests
 * and normal customers to view them.
 */
class PrivilegedBlockMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->isAdmin() || $user->isDeveloper()) {
                Log::channel('enhanced_security')->info('Privileged user blocked from guest storefront route', [
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'role' => $user->role,
                    'url' => $request->fullUrl(),
                    'ip' => $request->ip(),
                ]);
                // Redirect to admin dashboard for clarity
                return redirect()->route('admin.dashboard')
                    ->with('info', 'Administrators and developers cannot access storefront browsing pages.');
            }
        }

        return $next($request);
    }
}
