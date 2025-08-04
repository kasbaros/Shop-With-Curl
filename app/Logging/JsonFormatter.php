<?php

    namespace App\Logging;

    use Monolog\Formatter\JsonFormatter as BaseJsonFormatter;
    use Monolog\LogRecord;

    class JsonFormatter extends BaseJsonFormatter
    {
        /**
         * @throws \JsonException
         */
        public function format(LogRecord $record): string
        {
            $normalized = $this->normalize($record);

            // Enhanced log structure with safe access to app services
            $logEntry = [
                'timestamp' => $normalized['datetime'],
                'level' => $normalized['level_name'],
                'message' => $normalized['message'],
                'context' => $normalized['context'] ?? [],
                'extra' => $normalized['extra'] ?? [],
                'environment' => $this->safeGetEnvironment(),
                'server' => $this->getServerData(),
                'request' => $this->getRequestData(),
                'user' => $this->getUserData(),
                'memory_usage' => $this->formatBytes(memory_get_usage(true)),
                'memory_peak' => $this->formatBytes(memory_get_peak_usage(true)),
                'execution_time' => defined('LARAVEL_START') ? round((microtime(true) - LARAVEL_START) * 1000, 2) . 'ms' : null,
            ];

            // Add trace for errors and above
            if (in_array($normalized['level'], [400, 500, 550, 600], true)) {
                $logEntry['trace'] = $this->getTraceData($record);
            }

            return json_encode($logEntry, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n";
        }

        private function safeGetEnvironment(): string
        {
            try {
                return app()->bound('env') && app()->isBooted() ? app()->environment() : 'unknown';
            } catch (\Exception $e) {
                return 'unknown';
            }
        }

        private function getServerData(): array
        {
            try {
                return [
                    'hostname' => gethostname() ?: 'unknown',
                    'ip' => $this->safeGetRequestIp(),
                    'user_agent' => $this->safeGetUserAgent(),
                ];
            } catch (\Exception $e) {
                return [
                    'hostname' => 'unknown',
                    'ip' => 'unknown',
                    'user_agent' => 'unknown',
                ];
            }
        }

        private function getRequestData(): array
        {
            try {
                if ($this->isWebRequest()) {
                    $request = $this->safeGetRequest();
                    if ($request) {
                        return [
                            'method' => $request->method(),
                            'url' => $request->fullUrl(),
                            'route' => $this->getRouteName($request),
                            'controller' => $this->getControllerAction($request),
                            'headers' => $this->sanitizeHeaders($request->headers->all()),
                            'ip' => $request->ip(),
                            'session_id' => $this->safeGetSessionId(),
                        ];
                    }
                }
            } catch (\Exception $e) {
                // Silent fail for request data
            }

            return ['type' => 'console'];
        }

        private function getUserData(): ?array
        {
            try {
                if ($this->isWebRequest() && $this->safeAuthCheck()) {
                    $user = auth()->user();
                    if ($user) {
                        return [
                            'id' => $user->id ?? null,
                            'email' => $user->email ?? null,
                            'roles' => method_exists($user, 'getRoleNames')
                                ? $user->getRoleNames()->toArray()
                                : [],
                        ];
                    }
                }
            } catch (\Exception $e) {
                // Silent fail for user data
            }

            return null;
        }

        private function getRouteName($request): ?string
        {
            try {
                return $request->route()?->getName();
            } catch (\Exception $e) {
                return null;
            }
        }

        private function getControllerAction($request): ?string
        {
            try {
                $route = $request->route();
                if ($route) {
                    $action = $route->getAction();
                    return $action['controller'] ?? null;
                }
            } catch (\Exception $e) {
                // Silent fail
            }

            return null;
        }

        private function sanitizeHeaders(array $headers): array
        {
            $sensitiveHeaders = ['authorization', 'cookie', 'x-api-key', 'x-auth-token'];

            foreach ($sensitiveHeaders as $header) {
                if (isset($headers[$header])) {
                    $headers[$header] = ['***REDACTED***'];
                }
            }

            return $headers;
        }

        private function getTraceData(LogRecord $record): array
        {
            try {
                $context = $record->context;
                if (isset($context['exception'])) {
                    $exception = $context['exception'];
                    if ($exception instanceof \Throwable) {
                        return [
                            'file' => $exception->getFile(),
                            'line' => $exception->getLine(),
                            'trace' => array_slice($exception->getTrace(), 0, 10), // Limit trace depth
                        ];
                    }
                }
            } catch (\Exception $e) {
                // Silent fail
            }

            return debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 10);
        }

        private function formatBytes(int $bytes): string
        {
            $units = ['B', 'KB', 'MB', 'GB'];
            $bytes = max($bytes, 0);
            $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
            $pow = min($pow, count($units) - 1);

            return round($bytes / (1024 ** $pow), 2) . ' ' . $units[$pow];
        }

        // Helper methods for safe access to Laravel services
        private function isWebRequest(): bool
        {
            try {
                return app()->bound('request') && !app()->runningInConsole();
            } catch (\Exception $e) {
                return false;
            }
        }

        private function safeGetRequest()
        {
            try {
                return app()->bound('request') ? app('request') : null;
            } catch (\Exception $e) {
                return null;
            }
        }

        private function safeGetRequestIp(): string
        {
            try {
                $request = $this->safeGetRequest();
                return $request ? $request->ip() : 'cli';
            } catch (\Exception $e) {
                return 'unknown';
            }
        }

        private function safeGetUserAgent(): string
        {
            try {
                $request = $this->safeGetRequest();
                return $request ? $request->userAgent() : 'cli';
            } catch (\Exception $e) {
                return 'unknown';
            }
        }

        private function safeGetSessionId(): ?string
        {
            try {
                return session()?->getId();
            } catch (\Exception $e) {
                return null;
            }
        }

        private function safeAuthCheck(): bool
        {
            try {
                return auth()->check();
            } catch (\Exception $e) {
                return false;
            }
        }
    }
