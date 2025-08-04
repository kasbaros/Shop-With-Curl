<?php

    namespace App\Providers;

    use App\Models\Payment;
    use App\Listeners\PaymentEventListener;
    use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

    class PaymentEventServiceProvider extends ServiceProvider
    {
        protected $listen = [
            // Payment Events can be defined here if using formal events
            // For now, we'll use model observers
        ];

        public function boot()
        {
            parent::boot();

            // Register payment model observer
            Payment::observe(PaymentObserver::class);
        }
    }
