<?php

    namespace App\Listeners;

    use App\Models\User;
    use App\Models\Payment;
    use App\Notifications\PaymentNotification;
    use App\Notifications\AdminPaymentNotification;
    use Illuminate\Support\Facades\Notification;
    use Illuminate\Support\Facades\Log;

    class PaymentEventListener
    {
        public function handlePaymentCreated(Payment $payment)
        {
            try {
                // Notify customer
                if ($payment->order->user) {
                    $payment->order->user->notify(
                        new PaymentNotification($payment, 'payment_initiated')
                    );
                }

                // Notify admin for manual payments
                if ($payment->gateway === 'manual') {
                    $this->notifyAdmins($payment, 'manual_payment_pending');
                }

                // Check for high-value payments
                if ($payment->amount >= setting('high_value_threshold', 1000000)) {
                    $this->notifyAdmins($payment, 'high_value_payment');
                }

            } catch (\Exception $e) {
                Log::error('Payment creation notification failed: ' . $e->getMessage());
            }
        }

        public function handlePaymentCompleted(Payment $payment)
        {
            try {
                // Notify customer
                if ($payment->order->user) {
                    $payment->order->user->notify(
                        new PaymentNotification($payment, 'payment_confirmed')
                    );
                }

                // Update order status
                $payment->order->update([
                    'payment_status' => 'paid',
                    'status' => 'confirmed',
                ]);

                Log::info('Payment completed successfully', [
                    'payment_id' => $payment->id,
                    'order_id' => $payment->order_id,
                    'amount' => $payment->amount,
                ]);

            } catch (\Exception $e) {
                Log::error('Payment completion notification failed: ' . $e->getMessage());
            }
        }

        public function handlePaymentFailed(Payment $payment, string $reason = null)
        {
            try {
                // Notify customer
                if ($payment->order->user) {
                    $payment->order->user->notify(
                        new PaymentNotification($payment, 'payment_failed', [
                            'reason' => $reason ?? 'Payment processing failed'
                        ])
                    );
                }

                // Notify admin for investigation
                $this->notifyAdmins($payment, 'payment_failed', [
                    'reason' => $reason,
                    'requires_investigation' => true,
                ]);

            } catch (\Exception $e) {
                Log::error('Payment failure notification failed: ' . $e->getMessage());
            }
        }

        public function handlePaymentPending(Payment $payment)
        {
            try {
                // Notify customer with instructions
                if ($payment->order->user) {
                    $payment->order->user->notify(
                        new PaymentNotification($payment, 'payment_pending')
                    );
                }

            } catch (\Exception $e) {
                Log::error('Payment pending notification failed: ' . $e->getMessage());
            }
        }

        public function handleRefundRequested(Payment $payment, float $amount)
        {
            try {
                // Notify customer
                if ($payment->order->user) {
                    $payment->order->user->notify(
                        new PaymentNotification($payment, 'refund_requested', [
                            'amount' => $amount
                        ])
                    );
                }

                // Notify admin
                $this->notifyAdmins($payment, 'refund_requested', [
                    'amount' => $amount,
                    'requires_action' => true,
                ]);

            } catch (\Exception $e) {
                Log::error('Refund request notification failed: ' . $e->getMessage());
            }
        }

        public function handleRefundProcessed(Payment $payment, float $amount)
        {
            try {
                // Notify customer
                if ($payment->order->user) {
                    $payment->order->user->notify(
                        new PaymentNotification($payment, 'refund_processed', [
                            'amount' => $amount
                        ])
                    );
                }

                Log::info('Refund processed successfully', [
                    'payment_id' => $payment->id,
                    'refund_amount' => $amount,
                ]);

            } catch (\Exception $e) {
                Log::error('Refund processed notification failed: ' . $e->getMessage());
            }
        }

        public function handleSuspiciousActivity(Payment $payment, array $redFlags)
        {
            try {
                // Immediately notify all admins about potential fraud
                $this->notifyAdmins($payment, 'payment_fraud_alert', [
                    'red_flags' => $redFlags,
                    'risk_level' => $this->calculateRiskLevel($redFlags),
                ]);

                // Log for investigation
                Log::warning('Suspicious payment activity detected', [
                    'payment_id' => $payment->id,
                    'red_flags' => $redFlags,
                    'order_id' => $payment->order_id,
                ]);

            } catch (\Exception $e) {
                Log::error('Fraud alert notification failed: ' . $e->getMessage());
            }
        }

        public function handleGatewayError(Payment $payment, string $error)
        {
            try {
                // Notify technical team
                $this->notifyAdmins($payment, 'gateway_error', [
                    'error' => $error,
                    'gateway' => $payment->gateway,
                    'requires_technical_review' => true,
                ]);

                // Log the error
                Log::error('Payment gateway error', [
                    'payment_id' => $payment->id,
                    'gateway' => $payment->gateway,
                    'error' => $error,
                ]);

            } catch (\Exception $e) {
                Log::error('Gateway error notification failed: ' . $e->getMessage());
            }
        }

        private function notifyAdmins(Payment $payment, string $type, array $data = [])
        {
            $admins = User::where('role', 'admin')
                ->where('is_active', true)
                ->get();

            Notification::send($admins, new AdminPaymentNotification($payment, $type, $data));
        }

        private function calculateRiskLevel(array $redFlags): string
        {
            $riskScore = count($redFlags);

            if ($riskScore >= 5) return 'critical';
            if ($riskScore >= 3) return 'high';
            if ($riskScore >= 2) return 'medium';

            return 'low';
        }
    }
