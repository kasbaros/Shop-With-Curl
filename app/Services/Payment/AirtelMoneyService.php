<?php

    namespace App\Services\Payment;

    use App\Models\Payment;
    use App\Models\PaymentLog;
    use Illuminate\Support\Facades\Http;
    use Illuminate\Support\Facades\Log;
    use Illuminate\Support\Str;

    class AirtelMoneyService implements PaymentGatewayInterface
    {
        private $baseUrl;
        private $clientId;
        private $clientSecret;

        public function __construct()
        {
            $this->baseUrl = config('app.env') === 'production'
                ? 'https://openapiuat.airtel.africa'
                : 'https://openapiuat.airtel.africa'; // Same for sandbox

            $this->clientId = setting('airtel_client_id');
            $this->clientSecret = setting('airtel_client_secret');
        }

        public function initiatePayment(Payment $payment): array
        {
            try {
                $token = $this->getAccessToken();

                if (!$token) {
                    throw new \Exception('Failed to get access token');
                }

                $transactionId = 'AM_' . strtoupper(Str::random(12));

                $payload = [
                    'reference' => $payment->transaction_id,
                    'subscriber' => [
                        'country' => 'UG',
                        'currency' => $payment->currency,
                        'msisdn' => $this->formatPhoneNumber($payment->metadata['phone'] ?? ''),
                    ],
                    'transaction' => [
                        'amount' => $payment->amount,
                        'country' => 'UG',
                        'currency' => $payment->currency,
                        'id' => $transactionId,
                    ],
                ];

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => 'application/json',
                    'X-Country' => 'UG',
                    'X-Currency' => $payment->currency,
                ])->post($this->baseUrl . '/merchant/v1/payments/', $payload);

                PaymentLog::create([
                    'payment_id' => $payment->id,
                    'type' => 'request',
                    'gateway' => 'airtel',
                    'data' => [
                        'url' => $this->baseUrl . '/merchant/v1/payments/',
                        'payload' => $payload,
                        'transaction_id' => $transactionId,
                    ],
                    'status' => $response->status(),
                ]);

                if ($response->successful()) {
                    $responseData = $response->json();

                    $payment->update([
                        'status' => 'processing',
                        'gateway_transaction_id' => $transactionId,
                        'metadata' => array_merge($payment->metadata ?? [], [
                            'airtel_transaction_id' => $transactionId,
                            'airtel_response' => $responseData,
                        ]),
                    ]);

                    return [
                        'success' => true,
                        'reference_id' => $transactionId,
                        'message' => 'Payment request sent. Please check your phone and enter your PIN.',
                        'status' => 'processing',
                    ];
                }

                PaymentLog::create([
                    'payment_id' => $payment->id,
                    'type' => 'error',
                    'gateway' => 'airtel',
                    'data' => $response->json(),
                    'status' => $response->status(),
                ]);

                return [
                    'success' => false,
                    'message' => 'Failed to initiate payment: ' . $response->body(),
                ];

            } catch (\Exception $e) {
                Log::error('Airtel Money Payment Error: ' . $e->getMessage());

                PaymentLog::create([
                    'payment_id' => $payment->id,
                    'type' => 'error',
                    'gateway' => 'airtel',
                    'data' => ['error' => $e->getMessage()],
                ]);

                return [
                    'success' => false,
                    'message' => 'Payment service temporarily unavailable.',
                ];
            }
        }

        public function verifyPayment(Payment $payment): array
        {
            try {
                $token = $this->getAccessToken();
                $transactionId = $payment->gateway_transaction_id;

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                    'X-Country' => 'UG',
                    'X-Currency' => $payment->currency,
                ])->get($this->baseUrl . '/standard/v1/payments/' . $transactionId);

                PaymentLog::create([
                    'payment_id' => $payment->id,
                    'type' => 'response',
                    'gateway' => 'airtel',
                    'data' => $response->json(),
                    'status' => $response->status(),
                ]);

                if ($response->successful()) {
                    $data = $response->json();

                    if (isset($data['data']['transaction']['status']) && $data['data']['transaction']['status'] === 'TS') {
                        $payment->markAsCompleted($transactionId, $data);

                        return [
                            'success' => true,
                            'status' => 'completed',
                            'transaction_id' => $data['data']['transaction']['airtel_money_id'] ?? $transactionId,
                        ];
                    } elseif (isset($data['data']['transaction']['status']) && $data['data']['transaction']['status'] === 'TF') {
                        $payment->markAsFailed('Payment failed', $data);

                        return [
                            'success' => false,
                            'status' => 'failed',
                            'message' => 'Payment failed',
                        ];
                    }

                    return [
                        'success' => true,
                        'status' => 'pending',
                        'message' => 'Payment is still being processed',
                    ];
                }

                return [
                    'success' => false,
                    'message' => 'Failed to verify payment',
                ];

            } catch (\Exception $e) {
                Log::error('Airtel Money Verification Error: ' . $e->getMessage());

                return [
                    'success' => false,
                    'message' => 'Payment verification failed',
                ];
            }
        }

        public function handleWebhook(array $payload): array
        {
            // Airtel Money webhook handling
            try {
                $transactionId = $payload['transaction']['id'] ?? null;

                if ($transactionId) {
                    $payment = Payment::where('gateway_transaction_id', $transactionId)->first();

                    if ($payment) {
                        return $this->verifyPayment($payment);
                    }
                }

                return ['success' => false, 'message' => 'Payment not found'];

            } catch (\Exception $e) {
                Log::error('Airtel Money Webhook Error: ' . $e->getMessage());
                return ['success' => false, 'message' => 'Webhook processing failed'];
            }
        }

        public function refundPayment(Payment $payment, float $amount = null): array
        {
            return [
                'success' => false,
                'message' => 'Refunds not supported for Airtel Money',
            ];
        }

        private function getAccessToken(): ?string
        {
            try {
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                ])->post($this->baseUrl . '/auth/oauth2/token', [
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'grant_type' => 'client_credentials',
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    return $data['access_token'] ?? null;
                }

                Log::error('Airtel Token Error: ' . $response->body());
                return null;

            } catch (\Exception $e) {
                Log::error('Airtel Token Exception: ' . $e->getMessage());
                return null;
            }
        }

        private function formatPhoneNumber(string $phone): string
        {
            // Remove spaces, dashes, and plus signs
            $phone = preg_replace('/[\s\-\+]/', '', $phone);

            // If starts with 0, replace with 256
            if (substr($phone, 0, 1) === '0') {
                $phone = '256' . substr($phone, 1);
            }

            // If doesn't start with 256, add it
            if (substr($phone, 0, 3) !== '256') {
                $phone = '256' . $phone;
            }

            return $phone;
        }
    }
