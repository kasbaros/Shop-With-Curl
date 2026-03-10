<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

// Helper function for security exceptions (must be defined before the return)
if (! function_exists('isSecurityException')) {
    function isSecurityException(Throwable $e): bool
    {
        $securityExceptions = [
            \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException::class,
            \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException::class,
            \Illuminate\Auth\AuthenticationException::class,
            \Illuminate\Auth\Access\AuthorizationException::class,
            \Illuminate\Validation\ValidationException::class,
        ];

        return in_array(get_class($e), $securityExceptions) ||
            str_contains($e->getMessage(), 'unauthorized') ||
            str_contains($e->getMessage(), 'forbidden') ||
            str_contains($e->getMessage(), 'access denied');
    }
}

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders()
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Session cookie encryption is handled normally now that
        // ob_start() fixes the header flushing issue on LiteSpeed

        // Replace default CSRF middleware
        $middleware->web(replace: [
            \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class => \App\Http\Middleware\VerifyCsrfToken::class,
        ]);

        // Define middleware aliases
        $middleware->alias([
            'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
            'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
            'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
            'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
            'can' => \Illuminate\Auth\Middleware\Authorize::class,
            'guest' => \Illuminate\Auth\Middleware\RedirectIfAuthenticated::class,
            'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
            'precognitive' => \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class,
            'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
            'subscribed' => \Spark\Http\Middleware\VerifyBillableIsSubscribed::class,
            'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
            'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'developer' => \App\Http\Middleware\DeveloperMiddleware::class,
            'client' => \App\Http\Middleware\ClientOnlyMiddleware::class,
            'block.privileged' => \App\Http\Middleware\PrivilegedBlockMiddleware::class,
        ]);

        // Prevent LiteSpeed/proxy caching of dynamic pages
        $middleware->append(\App\Http\Middleware\NoCacheHeaders::class);

        // Apply log context middleware globally
        $middleware->append(\App\Http\Middleware\LogContextMiddleware::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Enhanced exception logging
        $exceptions->reportable(function (Throwable $e) {
            if (app()->isBooted()) {
                try {
                    $context = app()->bound('log.context') ? app('log.context') : [];
                    if (isSecurityException($e)) {
                        Log::channel('enhanced_security')->error('Security Exception', array_merge([
                            'exception' => get_class($e),
                            'message' => $e->getMessage(),
                            'file' => $e->getFile(),
                            'line' => $e->getLine(),
                            'trace' => $e->getTraceAsString(),
                            'timestamp' => now()->toISOString(),
                        ], $context));
                    } else {
                        Log::channel('structured_daily')->error('Exception occurred', array_merge([
                            'exception' => get_class($e),
                            'message' => $e->getMessage(),
                            'file' => $e->getFile(),
                            'line' => $e->getLine(),
                            'trace' => $e->getTraceAsString(),
                            'timestamp' => now()->toISOString(),
                        ], $context));
                    }
                } catch (\Exception $logException) {
                    error_log('Failed to log exception: ' . $logException->getMessage());
                }
            }
        });

        // Log 404s
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, Request $request) {
            if (app()->isBooted()) {
                try {
                    Log::channel('enhanced_security')->warning('404 Not Found', [
                        'url' => $request->fullUrl(),
                        'method' => $request->method(),
                        'user_agent' => $request->userAgent(),
                        'ip' => $request->ip(),
                        'referer' => $request->header('referer'),
                        'user_id' => auth()->id(),
                        'timestamp' => now()->toISOString(),
                    ]);
                } catch (\Exception $logException) {
                    error_log('Failed to log 404: ' . $logException->getMessage());
                }
            }
        });
    })
    ->create();
