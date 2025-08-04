<?php

    namespace App\Services\Payment;

    use InvalidArgumentException;

    class PaymentGatewayFactory
    {
        public static function create(string $gateway): PaymentGatewayInterface
        {
            return match($gateway) {
                'mtn' => new MtnMomoService(),
                'airtel' => new AirtelMoneyService(),
                'paypal' => new PayPalService(),
                'stripe' => new StripeService(),
                'manual' => new ManualPaymentService(),
                default => throw new InvalidArgumentException("Unsupported payment gateway: {$gateway}")
            };
        }

        public static function getAvailableGateways(): array
        {
            $gateways = [];

            if (setting('mtn_enabled', true)) {
                $gateways['mtn'] = [
                    'name' => 'MTN Mobile Money',
                    'type' => 'mobile_money',
                    'icon' => 'phone',
                    'description' => 'Pay with MTN Mobile Money',
                ];
            }

            if (setting('airtel_enabled', true)) {
                $gateways['airtel'] = [
                    'name' => 'Airtel Money',
                    'type' => 'mobile_money',
                    'icon' => 'phone',
                    'description' => 'Pay with Airtel Money',
                ];
            }

            if (setting('paypal_enabled', false)) {
                $gateways['paypal'] = [
                    'name' => 'PayPal',
                    'type' => 'online',
                    'icon' => 'paypal',
                    'description' => 'Pay with PayPal',
                ];
            }

            if (setting('stripe_enabled', false)) {
                $gateways['stripe'] = [
                    'name' => 'Credit/Debit Card',
                    'type' => 'card',
                    'icon' => 'credit-card',
                    'description' => 'Pay with card via Stripe',
                ];
            }

            if (setting('manual_momo_enabled', true)) {
                $gateways['manual'] = [
                    'name' => 'Manual Mobile Money',
                    'type' => 'manual',
                    'icon' => 'cash',
                    'description' => 'Send money and provide reference',
                ];
            }

            if (setting('cod_enabled', true)) {
                $gateways['cod'] = [
                    'name' => 'Cash on Delivery',
                    'type' => 'cod',
                    'icon' => 'truck',
                    'description' => 'Pay when you receive your order',
                ];
            }

            return $gateways;
        }
    }
