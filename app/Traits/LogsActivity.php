<?php

    namespace App\Traits;

    use Illuminate\Support\Facades\Log;
    use Illuminate\Support\Str;

    trait LogsActivity
    {
        /**
         * Log business activity with rich context
         */
        public function logActivity(string $action, array $context = [], string $level = 'info'): void
        {
            $logData = [
                'model' => static::class,
                'model_id' => $this->getKey(),
                'model_name' => Str::kebab(class_basename(static::class)),
                'model_type' => $this->getMorphClass(),
                'action' => $action,
                'context' => $context,
                'changed_attributes' => $this->getDirty(),
                'original_attributes' => $this->getOriginal(),
                'user_id' => auth()->id(),
                'session_id' => session()->getId(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'timestamp' => now()->toISOString(),
                'correlation_id' => $this->getCorrelationId(),
            ];

            Log::channel('business_analytics')->log($level, "Model activity: {$action}", $logData);
        }

        /**
         * Log performance metrics for model operations
         */
        public function logPerformance(string $operation, float $executionTime, array $metrics = []): void
        {
            Log::channel('performance_logs')->info("Model Performance: {$operation}", [
                'model' => static::class,
                'model_id' => $this->getKey(),
                'operation' => $operation,
                'execution_time_ms' => round($executionTime * 1000, 2),
                'memory_usage' => memory_get_peak_usage(true),
                'metrics' => $metrics,
                'timestamp' => now()->toISOString(),
            ]);
        }

        /**
         * Log security-related activities
         */
        public function logSecurity(string $event, array $context = [], string $level = 'warning'): void
        {
            Log::channel('security_logs')->log($level, "Security Event: {$event}", [
                'model' => static::class,
                'model_id' => $this->getKey(),
                'event' => $event,
                'context' => $context,
                'user_id' => auth()->id(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'session_id' => session()->getId(),
                'timestamp' => now()->toISOString(),
            ]);
        }

        /**
         * Boot the trait
         */
        protected static function bootLogsActivity(): void
        {
            // Log model events automatically
            static::created(function ($model) {
                $model->logActivity('created', [
                    'attributes' => $model->getAttributes(),
                ]);
            });

            static::updated(function ($model) {
                if ($model->wasChanged()) {
                    $model->logActivity('updated', [
                        'changes' => $model->getChanges(),
                        'changed_fields' => array_keys($model->getChanges()),
                    ]);
                }
            });

            static::deleted(function ($model) {
                $model->logActivity('deleted', [
                    'soft_deleted' => method_exists($model, 'trashed') ? $model->trashed() : false,
                    'final_attributes' => $model->getAttributes(),
                ]);
            });

            if (method_exists(static::class, 'restored')) {
                static::restored(function ($model) {
                    $model->logActivity('restored');
                });
            }
        }

        /**
         * Get or generate correlation ID for tracking related operations
         */
        private function getCorrelationId(): string
        {
            return request()->header('X-Correlation-ID')
                ?? session('correlation_id')
                ?? Str::uuid()->toString();
        }
    }
