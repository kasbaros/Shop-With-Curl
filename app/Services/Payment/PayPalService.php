<?php

    namespace App\Services\Payment;

    use App\Models\Payment;
    use App\Models\PaymentLog;
    use PayPalCheckoutSdk\Core\PayPalHttpClient;
    use PayPalCheckoutSdk\Core\SandboxEnvironment;
    use PayPalCheckoutSdk\Core\ProductionEnvironment;
    use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
    use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
    use PayPalCheckoutSdk\Orders\OrdersGetRequest;
    use Illuminate\Support\Facades\Log;

    class PayPalService implements PaymentGatewayInterface
    {
        private $client;

        public function __construct()
        {
            $clientId = setting('paypal_client_id');
            $clientSecret = setting('paypal_client_secret');
            $mode = setting('paypal_mode', 'sandbox');

            $environment = $mode === 'live'
                ? new ProductionEnvironment($clientId, $clientSecret)
                : new SandboxEnvironment($clientId, $clientSecret);

            $this->client = new PayPalHttpClient($environment);
        }

        public function initiatePayment(Payment $payment): array
        {
            try {
                $request = new OrdersCreateRequest();
                $request->prefer('return=representation');

                $request->body = [
                    'intent' => 'CAPTURE',
                    'purchase_units' => [[
                        'reference_id' => $payment->transaction_id,
                        'amount' => [
                            'currency_code' => $payment->currency,
                            'value' => number_format($payment->amount, 2, '.', ''),
                        ],
                        'description' => 'Order #' . $payment->order->order_number,
                    ]],
                    'application_context' => [
                        'return_url' => route('payment.success', $payment->uuid),
                        'cancel_url' => route('payment.cancel', $payment->uuid),
                        'brand_name' => setting('site_name'),
                        'user_action' => 'PAY_NOW',
                    ],
                ];

                $response = $this->client->execute($request);

                PaymentLog::create([
                    'payment_id' => $payment->id,
                    'type' => 'request',
                    'gateway' => 'paypal',
                    'data' => [
                        'request_body' => $request->body,
                        'response' => json_decode(json_encode($response->result), true),
                    ],
                    'status' => $response->statusCode,
                ]);

                if ($response->statusCode === 201) {
                    $result = $response->result;

                    $payment->update([
                        'status' => 'processing',
                        'gateway_transaction_id' => $result->id,
                        'metadata' => array_merge($payment->metadata ?? [], [
                            'paypal_order_id' => $result->id,
                            'paypal_response' => json_decode(json_encode($result), true),
                        ]),
                    ]);

                    // Find approval URL
                    $approvalUrl = null;
                    foreach ($result->links as $link) {
                        if ($link->rel === 'approve') {
                            $approvalUrl = $link->href;
                            break;
                        }
                    }

                    return [
                        'success' => true,
                        'approval_url' => $approvalUrl,
                        'order_id' => $result->id,
                        'status' => 'processing',
                    ];
                }

                return [
                    'success' => false,
                    'message' => 'Failed to create PayPal order',
                ];

            } catch (\Exception $e) {
                Log::error('PayPal Payment Error: ' . $e->getMessage());

                PaymentLog::create([
                    'payment_id' => $payment->id,
                    'type' => 'error',
                    'gateway' => 'paypal',
                    'data' => ['error' => $e->getMessage()],
                ]);

                return [
                    'success' => false,
                    'message' => 'PayPal service temporarily unavailable.',
                ];
            }
        }

        public function verifyPayment(Payment $payment): array
        {
            try {
                $orderId = $payment->gateway_transaction_id;

                $request = new OrdersGetRequest($orderId);
                $response = $this->client->execute($request);

                PaymentLog::create([
                    'payment_id' => $payment->id,
                    'type' => 'response',
                    'gateway' => 'paypal',
                    'data' => json_decode(json_encode($response->result), true),
                    'status' => $response->statusCode,
                ]);

                if ($response->statusCode === 200) {
                    $result = $response->result;

                    if ($result->status === 'COMPLETED') {
                        $captureId = $result->purchase_units[0]->payments->captures[0]->id ?? $orderId;
                        $payment->markAsCompleted($captureId, json_decode(json_encode($result), true));

                        return [
                            'success' => true,
                            'status' => 'completed',
                            'transaction_id' => $captureId,
                        ];
                    } elseif ($result->status === 'APPROVED') {
                        // Need to capture the payment
                        return $this->capturePayment($payment);
                    }

                    return [
                        'success' => true,
                        'status' => 'pending',
                        'message' => 'Payment is still being processed',
                    ];
                }

                return [
                    'success' => false,
                    'message' => 'Failed to verify PayPal payment',
                ];

            } catch (\Exception $e) {
                Log::error('PayPal Verification Error: ' . $e->getMessage());

                return [
                    'success' => false,
                    'message' => 'Payment verification failed',
                ];
            }
        }

        public function capturePayment(Payment $payment): array
        {
            try {
                $orderId = $payment->gateway_transaction_id;

                $request = new OrdersCaptureRequest($orderId);
                $response = $this->client->execute($request);

                if ($response->statusCode === 201) {
                    $result = $response->result;
                    $captureId = $result->purchase_units[0]->payments->captures[0]->id;

                    $payment->markAsCompleted($captureId, json_decode(json_encode($result), true));

                    return [
                        'success' => true,
                        'status' => 'completed',
                        'transaction_id' => $captureId,
                    ];
                }

                return [
                    'success' => false,
                    'message' => 'Failed to capture PayPal payment',
                ];

            } catch (\Exception $e) {
                Log::error('PayPal Capture Error: ' . $e->getMessage());
                return [
                    'success' => false,
                    'message' => 'Payment capture failed',
                ];
            }
        }

        public function handleWebhook(array $payload): array
        {
            // PayPal webhook handling
            try {
                $eventType = $payload['event_type'] ?? '';
                $resource = $payload['resource'] ?? [];

                if ($eventType === 'PAYMENT.CAPTURE.COMPLETED') {
                    $orderId = $resource['supplementary_data']['related_ids']['order_id'] ?? null;

                    if ($orderId) {
                        $payment = Payment::where('gateway_transaction_id', $orderId)->first();

                        if ($payment) {
                            return $this->verifyPayment($payment);
                        }
                    }
                }

                return ['success' => true, 'message' => 'Webhook processed'];

            } catch (\Exception $e) {
                Log::error('PayPal Webhook Error: ' . $e->getMessage());
                return ['success' => false, 'message' => 'Webhook processing failed'];
            }
        }

        public function refundPayment(Payment $payment, float $amount = null): array
        {
            // PayPal refund implementation would go here
            return [
                'success' => false,
                'message' => 'Refund functionality not implemented yet',
            ];
        }
    }
