<?php

    namespace App\Traits;

    use Illuminate\Support\Facades\Log;

    trait LogsPayments
    {
        /**
         * Log payment initiation
         */
        public function logPaymentInitiation(array $context = []): void
        {
            Log::channel('business_analytics')->info('Payment Initiated', array_merge([
                'payment_id' => $this->uuid,
                'order_id' => $this->order_id,
                'order_number' => $this->order->order_number ?? null,
                'amount' => $this->amount,
                'currency' => $this->currency,
                'payment_method' => $this->payment_method,
                'gateway' => $this->gateway,
                'customer_id' => $this->order->user_id ?? null,
                'customer_email' => $this->order->user->email ?? null,
                'metadata' => $this->metadata,
                'timestamp' => now()->toISOString(),
            ], $context));
        }

        /**
         * Log payment status changes
         */
        public function logStatusChange(string $oldStatus, string $newStatus, array $context = []): void
        {
            $level = $this->getLogLevelForStatus($newStatus);

            Log::channel('business_analytics')->log($level, 'Payment Status Changed', array_merge([
                'payment_id' => $this->uuid,
                'order_number' => $this->order->order_number ?? null,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'amount' => $this->amount,
                'currency' => $this->currency,
                'payment_method' => $this->payment_method,
                'gateway' => $this->gateway,
                'gateway_response' => $this->gateway_response,
                'timestamp' => now()->toISOString(),
            ], $context));

            // Log to security channel if suspicious
            if ($this->isSuspiciousStatusChange($oldStatus, $newStatus)) {
                $this->logSecurity('suspicious_payment_status_change', [
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'context' => $context,
                ], 'warning');
            }
        }

        /**
         * Log payment errors
         */
        public function logPaymentError(string $error, array $context = []): void
        {
            Log::channel('api_logs')->error('Payment Error', array_merge([
                'payment_id' => $this->uuid,
                'order_number' => $this->order->order_number ?? null,
                'error' => $error,
                'amount' => $this->amount,
                'currency' => $this->currency,
                'payment_method' => $this->payment_method,
                'gateway' => $this->gateway,
                'gateway_response' => $this->gateway_response,
                'attempts' => $this->attempts ?? 1,
                'timestamp' => now()->toISOString(),
            ], $context));
        }

        /**
         * Log successful payments with business metrics
         */
        public function logPaymentSuccess(array $context = []): void
        {
            Log::channel('business_analytics')->info('Payment Successful', array_merge([
                'payment_id' => $this->uuid,
                'order_id' => $this->order_id,
                'order_number' => $this->order->order_number ?? null,
                'amount' => $this->amount,
                'currency' => $this->currency,
                'payment_method' => $this->payment_method,
                'gateway' => $this->gateway,
                'gateway_fee' => $this->gateway_fee ?? 0,
                'net_amount' => $this->amount - ($this->gateway_fee ?? 0),
                'customer_id' => $this->order->user_id ?? null,
                'customer_segment' => $this->order->user->segment ?? 'regular',
                'processing_time' => $this->created_at->diffInSeconds($this->updated_at),
                'timestamp' => now()->toISOString(),
            ], $context));
        }

        /**
         * Determine log level based on payment status
         */
        private function getLogLevelForStatus(string $status): string
        {
            return match($status) {
                'completed', 'success' => 'info',
                'pending', 'processing' => 'info',
                'failed', 'cancelled', 'expired' => 'warning',
                'refunded', 'partially_refunded' => 'info',
                'disputed', 'chargeback' => 'error',
                default => 'info'
            };
        }

        /**
         * Check if status change is suspicious
         */
        private function isSuspiciousStatusChange(string $old, string $new): bool
        {
            $suspiciousChanges = [
                'completed' => ['pending', 'failed'], // Completed payment going back
                'failed' => ['completed'], // Failed payment suddenly completing
            ];

            return isset($suspiciousChanges[$old]) && in_array($new, $suspiciousChanges[$old]);
        }
    }
