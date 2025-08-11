<?php

namespace App\Logging;

use Illuminate\Log\Logger;
use Monolog\Formatter\JsonFormatter;
use Monolog\Processor\IntrospectionProcessor;
use Monolog\Processor\MemoryUsageProcessor;
use Monolog\Processor\ProcessIdProcessor;
use Monolog\Processor\WebProcessor;

class StructuredLogFormatter
{
    /**
     * Customize the given logger instance.
     *
     * @param  \Illuminate\Log\Logger  $logger
     * @return void
     */
    public function __invoke(Logger $logger)
    {
        foreach ($logger->getHandlers() as $handler) {
            // Set JSON formatter for structured logging
            $handler->setFormatter(new JsonFormatter());

            // Add useful processors for additional context
            $logger->pushProcessor(new WebProcessor());
            $logger->pushProcessor(new ProcessIdProcessor());
            $logger->pushProcessor(new MemoryUsageProcessor());
            $logger->pushProcessor(new IntrospectionProcessor());

            // Add custom context from the application
            $logger->pushProcessor(function ($record) {
                $record['extra']['app_version'] = config('app.version', '1.0.0');
                $record['extra']['environment'] = config('app.env');

                // Add user information if authenticated
                if (auth()->check()) {
                    $record['extra']['user_id'] = auth()->id();
                    $record['extra']['user_email'] = auth()->user()->email;
                }

                // Add request information if available
                if (app()->bound('request') && request()) {
                    $record['extra']['url'] = request()->fullUrl();
                    $record['extra']['method'] = request()->method();
                    $record['extra']['ip'] = request()->ip();
                    $record['extra']['user_agent'] = request()->userAgent();
                }

                return $record;
            });
        }
    }
}
