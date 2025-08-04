<?php
//
//    use Illuminate\Foundation\Application;
//    use Illuminate\Foundation\Configuration\Exceptions;
//    use Illuminate\Foundation\Configuration\Middleware;
//    use Illuminate\Http\Request;
//    use Illuminate\Support\Facades\Log;
//    use Illuminate\Support\Facades\Route;
//
//    return Application::configure(basePath: dirname(__DIR__))
//        ->withRouting(
//            web: __DIR__ . '/../routes/web.php',
//            api: __DIR__ . '/../routes/api.php',
//            commands: __DIR__ . '/../routes/console.php',
//            health: '/up',
//        // Remove the admin routes inclusion - everything will be in web.php now
//        )
//        ->withMiddleware(function (Middleware $middleware) {
//            $middleware->alias([
//                'admin' => \App\Http\Middleware\AdminMiddleware::class,
//                'developer' => \App\Http\Middleware\DeveloperMiddleware::class,
//                'log.context' => \App\Http\Middleware\LogContextMiddleware::class,
//                'log.api' => \App\Http\Middleware\LogApiRequests::class,
//            ]);
//
//            // Register session middleware globally to ensure it's available for all requests
//            $middleware->prepend(\Illuminate\Session\Middleware\StartSession::class);
//
//            // Add logging context middleware globally
//            $middleware->append(\App\Http\Middleware\LogContextMiddleware::class);
//        })
//        ->withExceptions(function (Exceptions $exceptions) {
//            // Enhanced exception logging - MINIMAL VERSION to avoid facade issues
//            $exceptions->reportable(function (Throwable $e) {
//                // Only log exceptions that occur after the application is fully booted
//                if (app()->isBooted()) {
//                    try {
//                        // Get the stored log context from the middleware
//                        $context = app()->bound('log.context') ? app('log.context') : [];
//
//                        Log::channel('security_logs')->error('Exception occurred', array_merge([
//                            'exception' => get_class($e),
//                            'message' => $e->getMessage(),
//                            'file' => $e->getFile(),
//                            'line' => $e->getLine(),
//                            'trace' => $e->getTraceAsString(),
//                            'timestamp' => now()->toISOString(),
//                        ], $context));
//                    } catch (\Exception $logException) {
//                        // Silent fail - don't break the application for logging issues
//                        error_log('Failed to log exception: ' . $logException->getMessage());
//                    }
//                }
//            });
//
//            // Log 404s as security events (potential reconnaissance)
//            $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, Request $request) {
//                if (app()->isBooted()) {
//                    try {
//                        Log::channel('security_logs')->warning('404 Not Found', [
//                            'url' => $request->fullUrl(),
//                            'method' => $request->method(),
//                            'user_agent' => $request->userAgent(),
//                            'ip' => $request->ip(),
//                            'referer' => $request->header('referer'),
//                            'timestamp' => now()->toISOString(),
//                        ]);
//                    } catch (\Exception $logException) {
//                        error_log('Failed to log 404: ' . $logException->getMessage());
//                    }
//                }
//            });
//        })
//        ->create();


use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

    return Application::configure(basePath: dirname(__DIR__))
        ->withRouting(
            web: __DIR__ . '/../routes/web.php',
            api: __DIR__ . '/../routes/api.php',
            commands: __DIR__ . '/../routes/console.php',
            health: '/up',
        )
        ->withMiddleware(function (Middleware $middleware) {

            // Replace default CSRF middleware with our custom one
            $middleware->web(replace: [
                \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class => \App\Http\Middleware\VerifyCsrfToken::class,
            ]);

            $middleware->alias([
                'admin' => \App\Http\Middleware\AdminMiddleware::class,
                'developer' => \App\Http\Middleware\DeveloperMiddleware::class,
                'session.debug' => \App\Http\Middleware\SessionDebugMiddleware::class,
            ]);

            // Add session debug middleware temporarily for web routes
            if (env('APP_DEBUG', false)) {
                $middleware->web(append: [
                    \App\Http\Middleware\SessionDebugMiddleware::class,
                ]);
            }

            // Apply log context middleware globally for better tracking
            $middleware->append(\App\Http\Middleware\LogContextMiddleware::class);
        })
    ->withExceptions(function (Exceptions $exceptions) {
        // Enhanced exception logging using our specialized channels
        $exceptions->reportable(function (Throwable $e) {
            if (app()->isBooted()) {
                try {
                    // Get the stored log context from the middleware
                    $context = app()->bound('log.context') ? app('log.context') : [];

                    // Log to security channel for security-related exceptions
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
                        // Log to main structured channel for other exceptions
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
                    // Silent fail - don't break the application for logging issues
                    error_log('Failed to log exception: ' . $logException->getMessage());
                }
            }
        });

        // Log 404s as potential security events
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

// Helper function to determine if exception is security-related
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
