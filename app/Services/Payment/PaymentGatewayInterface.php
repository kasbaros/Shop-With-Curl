<?php

    namespace App\Services\Payment;

    use App\Models\Payment;

    interface PaymentGatewayInterface
    {
        public function initiatePayment(Payment $payment): array;
        public function verifyPayment(Payment $payment): array;
        public function handleWebhook(array $payload): array;
        public function refundPayment(Payment $payment, float $amount = null): array;
    }
