<?php

    namespace App\Observers;

    use App\Models\Payment;
    use App\Listeners\PaymentEventListener;

    class PaymentObserver
    {
        protected $listener;

        public function __construct(PaymentEventListener $listener)
        {
            $this->listener = $listener;
        }

        public function created(Payment $payment)
        {
            $this->listener->handlePaymentCreated($payment);
        }

        public function updated(Payment $payment)
        {
            // Check if status changed
            if ($payment->isDirty('status')) {
                $newStatus = $payment->status;
                $oldStatus = $payment->getOriginal('status');

                switch ($newStatus) {
                    case 'completed':
                        $this->listener->handlePaymentCompleted($payment);
                        break;

                    case 'failed':
                        $this->listener->handlePaymentFailed($payment, $payment->failure_reason);
                        break;

                    case 'pending':
                        if ($oldStatus !== 'pending') {
                            $this->listener->handlePaymentPending($payment);
                        }
                        break;
                }
            }
        }
    }
