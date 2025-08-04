<?php

    namespace App\Notifications;

    use App\Models\Payment;
    use Illuminate\Bus\Queueable;
    use Illuminate\Contracts\Queue\ShouldQueue;
    use Illuminate\Notifications\Messages\MailMessage;
    use Illuminate\Notifications\Messages\DatabaseMessage;
    use Illuminate\Notifications\Notification;

    class PaymentNotification extends Notification implements ShouldQueue
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

            // Add mail for important notifications
            if (in_array($this->type, ['payment_confirmed', 'payment_failed', 'refund_processed'])) {
                $channels[] = 'mail';
            }

            return $channels;
        }

        public function toMail($notifiable)
        {
            $message = new MailMessage();

            switch ($this->type) {
                case 'payment_confirmed':
                    return $message
                        ->subject('Payment Confirmed - Order #' . $this->payment->order->order_number)
                        ->greeting('Hello ' . $notifiable->name . '!')
                        ->line('Great news! Your payment has been confirmed.')
                        ->line('Order Number: #' . $this->payment->order->order_number)
                        ->line('Payment Amount: ' . format_currency($this->payment->amount, $this->payment->currency))
                        ->line('Transaction ID: ' . ($this->payment->gateway_transaction_id ?? $this->payment->transaction_id))
                        ->action('View Order', route('orders.show', $this->payment->order))
                        ->line('Thank you for shopping with us!');

                case 'payment_failed':
                    return $message
                        ->subject('Payment Failed - Order #' . $this->payment->order->order_number)
                        ->greeting('Hello ' . $notifiable->name . '!')
                        ->line('We were unable to process your payment.')
                        ->line('Order Number: #' . $this->payment->order->order_number)
                        ->line('Reason: ' . ($this->data['reason'] ?? 'Payment processing failed'))
                        ->action('Try Again', route('payment.select', $this->payment->order))
                        ->line('If you need help, please contact our support team.');

                case 'refund_processed':
                    return $message
                        ->subject('Refund Processed - Order #' . $this->payment->order->order_number)
                        ->greeting('Hello ' . $notifiable->name . '!')
                        ->line('Your refund has been processed successfully.')
                        ->line('Order Number: #' . $this->payment->order->order_number)
                        ->line('Refund Amount: ' . format_currency($this->data['amount'] ?? $this->payment->amount, $this->payment->currency))
                        ->line('The refund will appear in your account within 3-5 business days.')
                        ->line('Thank you for your patience.');

                default:
                    return $message
                        ->subject('Payment Update - Order #' . $this->payment->order->order_number)
                        ->line('Your payment status has been updated.')
                        ->action('View Order', route('orders.show', $this->payment->order));
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
                'transaction_id' => $this->payment->transaction_id,
                'message' => $this->getNotificationMessage(),
                'data' => $this->data,
                'created_at' => now(),
            ];
        }

        private function getNotificationMessage(): string
        {
            switch ($this->type) {
                case 'payment_initiated':
                    return "Payment initiated for order #{$this->payment->order->order_number}";

                case 'payment_confirmed':
                    return "Payment confirmed for order #{$this->payment->order->order_number}";

                case 'payment_failed':
                    return "Payment failed for order #{$this->payment->order->order_number}";

                case 'payment_pending':
                    return "Payment pending for order #{$this->payment->order->order_number} - action required";

                case 'refund_requested':
                    return "Refund requested for order #{$this->payment->order->order_number}";

                case 'refund_processed':
                    return "Refund processed for order #{$this->payment->order->order_number}";

                default:
                    return "Payment update for order #{$this->payment->order->order_number}";
            }
        }
    }
