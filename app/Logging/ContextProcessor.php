<?php

    namespace App\Logging;

    use Monolog\Processor\ProcessorInterface;
    use Illuminate\Support\Facades\Request;

    class ContextProcessor implements ProcessorInterface
    {
        public function __invoke(array $record): array
        {
            $record['extra']['application'] = [
                'name' => config('app.name'),
                'environment' => config('app.env'),
                'version' => config('app.version', '1.0.0'),
            ];

            if (app()->runningInConsole()) {
                $record['extra']['context'] = [
                    'type' => 'console',
                    'command' => $_SERVER['argv'] ?? null,
                ];
            } else {
                $record['extra']['context'] = [
                    'type' => 'http',
                    'method' => Request::method(),
                    'url' => Request::fullUrl(),
                    'ip' => Request::ip(),
                    'user_agent' => Request::userAgent(),
                    'session_id' => session()->getId(),
                ];
            }

            return $record;
        }
    }
