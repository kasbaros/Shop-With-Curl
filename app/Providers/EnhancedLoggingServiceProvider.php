<?php
//
//    namespace App\Providers;
//
//    use Illuminate\Support\ServiceProvider;
//    use Illuminate\Support\Facades\Log;
//    use Monolog\Logger;
//    use Monolog\Handler\StreamHandler;
//    use Monolog\Formatter\JsonFormatter;
//    use Monolog\Processor\UidProcessor;
//    use App\Logging\ContextProcessor;
//    use App\Logging\UserProcessor;
//
//    class EnhancedLoggingServiceProvider extends ServiceProvider
//    {
//        public function register()
//        {
//            $this->app->singleton('enhanced.logger', function ($app) {
//                $logger = new Logger('enhanced');
//
//                $handler = new StreamHandler(
//                    storage_path('logs/enhanced.log'),
//                    Logger::INFO
//                );
//
//                $formatter = new JsonFormatter();
//                $handler->setFormatter($formatter);
//
//                $logger->pushHandler($handler);
//                $logger->pushProcessor(new UidProcessor());
//                $logger->pushProcessor(new ContextProcessor());
//                $logger->pushProcessor(new UserProcessor());
//
//                return $logger;
//            });
//        }
//
//        public function boot()
//        {
//            // Register custom log channels
//            Log::extend('enhanced', function ($app, $config) {
//                return $app->make('enhanced.logger');
//            });
//        }
//    }



namespace App\Providers;

use App\Logging\JsonFormatter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Processor\UidProcessor;
use Monolog\Processor\ProcessIdProcessor;
use Monolog\Processor\MemoryUsageProcessor;
use Monolog\Processor\IntrospectionProcessor;
use Monolog\Processor\WebProcessor;
use Monolog\Processor\PsrLogMessageProcessor;

class EnhancedLoggingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register specialized loggers to avoid duplicate files
        $this->registerSecurityLogger();
        $this->registerPerformanceLogger();
        $this->registerUserActivityLogger();
        $this->registerBusinessAnalyticsLogger();
    }

    public function boot()
    {
        // Register custom log channels
        Log::extend('enhanced_security', function ($app, $config) {
            return $app->make('enhanced.security.logger');
        });

        Log::extend('enhanced_performance', function ($app, $config) {
            return $app->make('enhanced.performance.logger');
        });

        Log::extend('enhanced_user_activity', function ($app, $config) {
            return $app->make('enhanced.user_activity.logger');
        });

        Log::extend('enhanced_business', function ($app, $config) {
            return $app->make('enhanced.business.logger');
        });
    }

    private function registerSecurityLogger(): void
    {
        $this->app->singleton('enhanced.security.logger', function ($app) {
            $logger = new Logger('security');

            $handler = new RotatingFileHandler(
                storage_path('logs/security.log'),
                30, // Keep 30 days
                Logger::WARNING
            );

            $formatter = new JsonFormatter();
            $handler->setFormatter($formatter);

            $logger->pushHandler($handler);

            // Add processors for security context
            $logger->pushProcessor(new UidProcessor());
            $logger->pushProcessor(new ProcessIdProcessor());
            $logger->pushProcessor(new WebProcessor());
            $logger->pushProcessor(new PsrLogMessageProcessor());

            return $logger;
        });
    }

    private function registerPerformanceLogger(): void
    {
        $this->app->singleton('enhanced.performance.logger', function ($app) {
            $logger = new Logger('performance');

            $handler = new RotatingFileHandler(
                storage_path('logs/performance.log'),
                14, // Keep 14 days
                Logger::INFO
            );

            $formatter = new JsonFormatter();
            $handler->setFormatter($formatter);

            $logger->pushHandler($handler);

            // Add processors for performance context
            $logger->pushProcessor(new MemoryUsageProcessor());
            $logger->pushProcessor(new ProcessIdProcessor());
            $logger->pushProcessor(new PsrLogMessageProcessor());

            return $logger;
        });
    }

    private function registerUserActivityLogger(): void
    {
        $this->app->singleton('enhanced.user_activity.logger', function ($app) {
            $logger = new Logger('user_activity');

            $handler = new RotatingFileHandler(
                storage_path('logs/user-activity.log'),
                30, // Keep 30 days
                Logger::INFO
            );

            $formatter = new JsonFormatter();
            $handler->setFormatter($formatter);

            $logger->pushHandler($handler);

            // Add processors for user activity context
            $logger->pushProcessor(new UidProcessor());
            $logger->pushProcessor(new WebProcessor());
            $logger->pushProcessor(new PsrLogMessageProcessor());

            return $logger;
        });
    }

    private function registerBusinessAnalyticsLogger(): void
    {
        $this->app->singleton('enhanced.business.logger', function ($app) {
            $logger = new Logger('business');

            $handler = new RotatingFileHandler(
                storage_path('logs/business-analytics.log'),
                365, // Keep 1 year
                Logger::INFO
            );

            $formatter = new JsonFormatter();
            $handler->setFormatter($formatter);

            $logger->pushHandler($handler);

            // Minimal processors for business data
            $logger->pushProcessor(new PsrLogMessageProcessor());

            return $logger;
        });
    }
}
