<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $order;
    protected $status;
    protected $previousStatus;

    public function __construct(Order $order, string $status, ?string $previousStatus = null)
    {
        $this->order = $order;
        $this->status = $status;
        $this->previousStatus = $previousStatus;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $message = new MailMessage();

        switch ($this->status) {
            case 'confirmed':
            case 'processing':
                return $message
                    ->subject('Order Confirmed - Order #' . $this->order->order_number)
                    ->greeting('Hello ' . $notifiable->name . '!')
                    ->line('Great news! Your order has been confirmed and is being processed.')
                    ->line('Order Number: #' . $this->order->order_number)
                    ->line('Order Total: ' . $this->order->formatted_total)
                    ->line('We\'ll send you another update when your order ships.')
                    ->action('View Order Details', route('orders.show', $this->order))
                    ->line('Thank you for shopping with us!');

            case 'shipped':
                $message = $message
                    ->subject('Order Shipped - Order #' . $this->order->order_number)
                    ->greeting('Hello ' . $notifiable->name . '!')
                    ->line('Your order is on its way!')
                    ->line('Order Number: #' . $this->order->order_number);

                if ($this->order->tracking_number) {
                    $message->line('Tracking Number: ' . $this->order->tracking_number);
                    $message->line('You can track your package using the tracking number above.');
                }

                return $message
                    ->line('Expected delivery: 3-5 business days')
                    ->action('View Order Details', route('orders.show', $this->order))
                    ->line('Thank you for your patience!');

            case 'delivered':
                return $message
                    ->subject('Order Delivered - Order #' . $this->order->order_number)
                    ->greeting('Hello ' . $notifiable->name . '!')
                    ->line('Your order has been delivered successfully!')
                    ->line('Order Number: #' . $this->order->order_number)
                    ->line('Delivered on: ' . $this->order->delivered_at?->format('M j, Y'))
                    ->line('We hope you enjoy your purchase!')
                    ->action('Leave a Review', route('orders.show', $this->order))
                    ->line('Thank you for choosing us!');

            case 'cancelled':
                return $message
                    ->subject('Order Cancelled - Order #' . $this->order->order_number)
                    ->greeting('Hello ' . $notifiable->name . '!')
                    ->line('Your order has been cancelled as requested.')
                    ->line('Order Number: #' . $this->order->order_number)
                    ->line('If you have any questions, please don\'t hesitate to contact us.')
                    ->action('Contact Support', url('/contact'))
                    ->line('We apologize for any inconvenience.');

            default:
                return $message
                    ->subject('Order Update - Order #' . $this->order->order_number)
                    ->greeting('Hello ' . $notifiable->name . '!')
                    ->line('Your order status has been updated.')
                    ->line('Order Number: #' . $this->order->order_number)
                    ->line('Status: ' . ucfirst($this->status))
                    ->action('View Order Details', route('orders.show', $this->order))
                    ->line('Thank you for your business!');
        }
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'order_status_update',
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'status' => $this->status,
            'previous_status' => $this->previousStatus,
            'tracking_number' => $this->order->tracking_number,
            'message' => $this->getNotificationMessage(),
            'created_at' => now(),
        ];
    }

    private function getNotificationMessage(): string
    {
        switch ($this->status) {
            case 'confirmed':
            case 'processing':
                return "Your order #{$this->order->order_number} has been confirmed and is being processed.";

            case 'shipped':
                $message = "Your order #{$this->order->order_number} has been shipped.";
                if ($this->order->tracking_number) {
                    $message .= " Tracking: {$this->order->tracking_number}";
                }
                return $message;

            case 'delivered':
                return "Your order #{$this->order->order_number} has been delivered successfully.";

            case 'cancelled':
                return "Your order #{$this->order->order_number} has been cancelled.";

            default:
                return "Your order #{$this->order->order_number} status has been updated to: " . ucfirst($this->status);
        }
    }
}
