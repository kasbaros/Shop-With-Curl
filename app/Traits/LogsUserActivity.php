<?php

    namespace App\Traits;

    use Illuminate\Support\Facades\Log;

    trait LogsUserActivity
    {
        /**
         * Log user authentication events
         */
        public function logAuthEvent(string $event, array $context = []): void
        {
            Log::channel('security_logs')->info("Auth Event: {$event}", array_merge([
                'user_id' => $this->id,
                'email' => $this->email,
                'event' => $event,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'session_id' => session()->getId(),
                'timestamp' => now()->toISOString(),
            ], $context));
        }

        /**
         * Log user behavior for analytics
         */
        public function logBehavior(string $action, array $context = []): void
        {
            Log::channel('user_activity')->info("User Behavior: {$action}", array_merge([
                'user_id' => $this->id,
                'email' => $this->email,
                'role' => $this->role,
                'action' => $action,
                'session_id' => session()->getId(),
                'page_url' => request()->fullUrl(),
                'referrer' => request()->header('referer'),
                'timestamp' => now()->toISOString(),
            ], $context));
        }

        /**
         * Log security-sensitive user actions
         */
        public function logSecurityAction(string $action, array $context = [], string $level = 'warning'): void
        {
            Log::channel('security_logs')->log($level, "Security Action: {$action}", array_merge([
                'user_id' => $this->id,
                'email' => $this->email,
                'action' => $action,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'session_id' => session()->getId(),
                'timestamp' => now()->toISOString(),
            ], $context));
        }

        /**
         * Log user profile changes
         */
        public function logProfileChange(array $changes, array $context = []): void
        {
            $sensitiveFields = ['email', 'password', 'phone', 'role'];
            $hasSensitiveChanges = !empty(array_intersect(array_keys($changes), $sensitiveFields));

            $channel = $hasSensitiveChanges ? 'security_logs' : 'user_activity';
            $level = $hasSensitiveChanges ? 'warning' : 'info';

            Log::channel($channel)->log($level, 'User Profile Updated', array_merge([
                'user_id' => $this->id,
                'email' => $this->email,
                'changes' => $changes,
                'sensitive_changes' => array_intersect(array_keys($changes), $sensitiveFields),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'timestamp' => now()->toISOString(),
            ], $context));
        }

        public function logLogin(array $context = []): void
        {
            $this->logBehavior('login', array_merge([
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ], $context));
        }

        public function logLogout(array $context = []): void
        {
            $this->logBehavior('logout', array_merge([
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ], $context));
        }

        public function logPageView(string $page, array $context = []): void
        {
            $this->logBehavior('page_view', array_merge([
                'page' => $page,
                'method' => request()->method(),
            ], $context));
        }
    }
