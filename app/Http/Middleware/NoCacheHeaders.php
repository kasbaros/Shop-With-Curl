<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NoCacheHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate, private');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');
        // LiteSpeed-specific: disable caching and content transformation
        $response->headers->set('X-LiteSpeed-Cache-Control', 'no-cache, no-transform');

        // Prevent LiteSpeed/cPanel from injecting HTML into JSON responses
        // (e.g., Livewire update endpoint at /livewire/update)
        if ($request->is('livewire/*') && $response->headers->get('Content-Type') === 'application/json') {
            $content = $response->getContent();
            // Strip any HTML injected before the JSON by server modules
            if ($content && !str_starts_with(trim($content), '{') && !str_starts_with(trim($content), '[')) {
                $jsonStart = strpos($content, '{');
                if ($jsonStart !== false) {
                    $response->setContent(substr($content, $jsonStart));
                }
            }
        }

        return $response;
    }
}
