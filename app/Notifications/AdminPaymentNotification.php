<?php

    namespace App\Notifications;

    use App\Models\Payment;
    use App\Models\User;
    use Illuminate\Bus\Queueable;
    use Illuminate\Contracts\Queue\ShouldQueue;
    use Illuminate\Notifications\Messages\MailMessage;
    use Illuminate\Notifications\Messages\SlackMessage;
    use Illuminate\Notifications\Notification;

    class AdminPaymentNotification extends Notification implements ShouldQueue
    {
        use Queueable;

        protected $payment;
        protected $type;
        protected $data;

        public function __construct(Payment $payment, string $type, array $data = [])
        {
            $this->payment = $payment;
            $this->type = $type;
            $this->data = $data;
        }

        public function via($notifiable)
        {
            $channels = ['database'];

            // Add mail for critical notifications
            if (in_array($this->type, ['manual_payment_pending', 'high_value_payment', 'payment_fraud_alert'])) {
                $channels[] = 'mail';
            }

            // Add slack for urgent notifications if configured
            if (setting('slack_webhook_url') && in_array($this->type, ['payment_fraud_alert', 'gateway_error'])) {
                $channels[] = 'slack';
            }

            return $channels;
        }

        public function toMail($notifiable)
        {
            $message = new MailMessage();

            switch ($this->type) {
                case 'manual_payment_pending':
                    return $message
                        ->subject('Manual Payment Approval Required')
                        ->greeting('Hello Admin!')
                        ->line('A manual payment requires your approval.')
                        ->line('Order: #' . $this->payment->order->order_number)
                        ->line('Customer: ' . ($this->payment->order->user->name ?? 'Guest'))
                        ->line('Amount: ' . format_currency($this->payment->amount, $this->payment->currency))
                        ->line('Payment Method: ' . ucwords(str_replace('_', ' ', $this->payment->payment_method)))
                        ->action('Review Payment', route('admin.payments.show', $this->payment))
                        ->line('Please review and approve this payment as soon as possible.');

                case 'high_value_payment':
                    return $message
                        ->subject('High Value Payment Alert')
                        ->greeting('Hello Admin!')
                        ->line('A high-value payment has been processed.')
                        ->line('Order: #' . $this->payment->order->order_number)
                        ->line('Amount: ' . format_currency($this->payment->amount, $this->payment->currency))
                        ->line('Payment Method: ' . ucwords(str_replace('_', ' ', $this->payment->payment_method)))
                        ->line('Status: ' . ucfirst($this->payment->status))
                        ->action('View Details', route('admin.payments.show', $this->payment))
                        ->line('Please monitor this transaction closely.');

                case 'payment_fraud_alert':
                    return $message
                        ->subject('⚠️ Payment Fraud Alert')
                        ->greeting('URGENT: Admin Action Required!')
                        ->line('Potential fraudulent payment detected.')
                        ->line('Order: #' . $this->payment->order->order_number)
                        ->line('Red Flags: ' . implode(', ', $this->data['red_flags'] ?? []))
                        ->action('Investigate Now', route('admin.payments.show', $this->payment))
                        ->line('Please investigate this payment immediately.');

                default:
                    return $message
                        ->subject('Payment Admin Notification')
                        ->line('Payment notification: ' . $this->getNotificationMessage())
                        ->action('View Payment', route('admin.payments.show', $this->payment));
            }
        }

        public function toSlack($notifiable)
        {
            $slackMessage = new SlackMessage();

            switch ($this->type) {
                case 'payment_fraud_alert':
                    return $slackMessage
                        ->error()
                        ->content('🚨 Payment Fraud Alert!')
                        ->attachment(function ($attachment) {
                            $attachment->title('Suspicious Payment Detected')
                                ->fields([
                                    'Order' => '#' . $this->payment->order->order_number,
                                    'Amount' => format_currency($this->payment->amount, $this->payment->currency),
                                    'Red Flags' => implode(', ', $this->data['red_flags'] ?? []),
                                ])
                                ->action('Investigate', route('admin.payments.show', $this->payment));
                        });

                case 'gateway_error':
                    return $slackMessage
                        ->warning()
                        ->content('⚠️ Payment Gateway Error')
                        ->attachment(function ($attachment) {
                            $attachment->title('Gateway Error Detected')
                                ->fields([
                                    'Gateway' => ucfirst($this->payment->gateway),
                                    'Error' => $this->data['error'] ?? 'Unknown error',
                                    'Order' => '#' . $this->payment->order->order_number,
                                ]);
                        });

                default:
                    return $slackMessage
                        ->content($this->getNotificationMessage());
            }
        }

        public function toDatabase($notifiable)
        {
            return [
                'type' => $this->type,
                'payment_id' => $this->payment->id,
                'order_id' => $this->payment->order_id,
                'order_number' => $this->payment->order->order_number,
                'amount' => $this->payment->amount,
                'currency' => $this->payment->currency,
                'payment_method' => $this->payment->payment_method,
                'gateway' => $this->payment->gateway,
                'message' => $this->getNotificationMessage(),
                'data' => $this->data,
                'priority' => $this->getNotificationPriority(),
                'created_at' => now(),
            ];
        }

        private function getNotificationMessage(): string
        {
            switch ($this->type) {
                case 'manual_payment_pending':
                    return "Manual payment approval required for order #{$this->payment->order->order_number}";

                case 'high_value_payment':
                    return "High value payment (" . format_currency($this->payment->amount, $this->payment->currency) . ") for order #{$this->payment->order->order_number}";

                case 'payment_fraud_alert':
                    return "Potential fraud detected for payment #{$this->payment->transaction_id}";

                case 'gateway_error':
                    return "Gateway error in {$this->payment->gateway} for payment #{$this->payment->transaction_id}";

                case 'refund_requested':
                    return "Refund requested for payment #{$this->payment->transaction_id}";

                case 'daily_payment_summary':
                    return "Daily payment summary: " . ($this->data['count'] ?? 0) . " payments, " . format_currency($this->data['total'] ?? 0);

                default:
                    return "Admin notification for payment #{$this->payment->transaction_id}";
            }
        }

        private function getNotificationPriority(): string
        {
            return match($this->type) {
                'payment_fraud_alert' => 'critical',
                'gateway_error', 'high_value_payment' => 'high',
                'manual_payment_pending', 'refund_requested' => 'medium',
                default => 'low'
            };
        }
    }
