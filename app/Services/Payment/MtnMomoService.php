<?php

    namespace App\Services\Payment;

    use App\Models\Payment;
    use App\Models\PaymentLog;
    use Illuminate\Support\Facades\Http;
    use Illuminate\Support\Facades\Log;
    use Illuminate\Support\Str;

    class MtnMomoService implements PaymentGatewayInterface
    {
        private $baseUrl;
        private $apiKey;
        private $subscriptionKey;

        public function __construct()
        {
            $this->baseUrl = config('app.env') === 'production'
                ? 'https://ericssonbasicapi2.azure-api.net/collection/v1_0'
                : 'https://sandbox.momodeveloper.mtn.com/collection/v1_0';

            $this->apiKey = setting('mtn_api_key');
            $this->subscriptionKey = setting('mtn_subscription_key');
        }

        public function initiatePayment(Payment $payment): array
        {
            try {
                // First get access token
                $token = $this->getAccessToken();

                if (!$token) {
                    throw new \Exception('Failed to get access token');
                }

                $referenceId = Str::uuid();

                $payload = [
                    'amount' => (string) $payment->amount,
                    'currency' => $payment->currency,
                    'externalId' => $payment->transaction_id,
                    'payer' => [
                        'partyIdType' => 'MSISDN',
                        'partyId' => $this->formatPhoneNumber($payment->metadata['phone'] ?? ''),
                    ],
                    'payerMessage' => 'Payment for Order #' . $payment->order->order_number,
                    'payeeNote' => 'Payment to ' . setting('site_name'),
                ];

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                    'X-Reference-Id' => $referenceId,
                    'X-Target-Environment' => config('app.env') === 'production' ? 'live' : 'sandbox',
                    'Ocp-Apim-Subscription-Key' => $this->subscriptionKey,
                    'Content-Type' => 'application/json',
                ])->post($this->baseUrl . '/requesttopay', $payload);

                // Log request
                PaymentLog::create([
                    'payment_id' => $payment->id,
                    'type' => 'request',
                    'gateway' => 'mtn',
                    'data' => [
                        'url' => $this->baseUrl . '/requesttopay',
                        'payload' => $payload,
                        'reference_id' => $referenceId,
                    ],
                    'status' => $response->status(),
                ]);

                if ($response->successful()) {
                    $payment->update([
                        'status' => 'processing',
                        'gateway_transaction_id' => $referenceId,
                        'metadata' => array_merge($payment->metadata ?? [], [
                            'reference_id' => $referenceId,
                            'mtn_response' => $response->json(),
                        ]),
                    ]);

                    return [
                        'success' => true,
                        'reference_id' => $referenceId,
                        'message' => 'Payment request sent. Please check your phone and enter your PIN.',
                        'status' => 'processing',
                    ];
                }

                // Log error response
                PaymentLog::create([
                    'payment_id' => $payment->id,
                    'type' => 'error',
                    'gateway' => 'mtn',
                    'data' => $response->json(),
                    'status' => $response->status(),
                ]);

                return [
                    'success' => false,
                    'message' => 'Failed to initiate payment: ' . $response->body(),
                ];

            } catch (\Exception $e) {
                Log::error('MTN MoMo Payment Error: ' . $e->getMessage());

                PaymentLog::create([
                    'payment_id' => $payment->id,
                    'type' => 'error',
                    'gateway' => 'mtn',
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
                $referenceId = $payment->gateway_transaction_id;

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                    'X-Target-Environment' => config('app.env') === 'production' ? 'live' : 'sandbox',
                    'Ocp-Apim-Subscription-Key' => $this->subscriptionKey,
                ])->get($this->baseUrl . '/requesttopay/' . $referenceId);

                PaymentLog::create([
                    'payment_id' => $payment->id,
                    'type' => 'response',
                    'gateway' => 'mtn',
                    'data' => $response->json(),
                    'status' => $response->status(),
                ]);

                if ($response->successful()) {
                    $data = $response->json();

                    if (isset($data['status']) && $data['status'] === 'SUCCESSFUL') {
                        $payment->markAsCompleted($referenceId, $data);

                        return [
                            'success' => true,
                            'status' => 'completed',
                            'transaction_id' => $data['financialTransactionId'] ?? $referenceId,
                        ];
                    } elseif (isset($data['status']) && $data['status'] === 'FAILED') {
                        $payment->markAsFailed($data['reason'] ?? 'Payment failed', $data);

                        return [
                            'success' => false,
                            'status' => 'failed',
                            'message' => $data['reason'] ?? 'Payment failed',
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
                Log::error('MTN MoMo Verification Error: ' . $e->getMessage());

                return [
                    'success' => false,
                    'message' => 'Payment verification failed',
                ];
            }
        }

        public function handleWebhook(array $payload): array
        {
            // MTN MoMo webhook handling
            try {
                // Validate webhook signature if provided
                $referenceId = $payload['referenceId'] ?? null;

                if ($referenceId) {
                    $payment = Payment::where('gateway_transaction_id', $referenceId)->first();

                    if ($payment) {
                        return $this->verifyPayment($payment);
                    }
                }

                return ['success' => false, 'message' => 'Payment not found'];

            } catch (\Exception $e) {
                Log::error('MTN MoMo Webhook Error: ' . $e->getMessage());
                return ['success' => false, 'message' => 'Webhook processing failed'];
            }
        }

        public function refundPayment(Payment $payment, float $amount = null): array
        {
            // MTN MoMo refund implementation
            return [
                'success' => false,
                'message' => 'Refunds not supported for MTN Mobile Money',
            ];
        }

        private function getAccessToken(): ?string
        {
            try {
                $response = Http::withHeaders([
                    'Ocp-Apim-Subscription-Key' => $this->subscriptionKey,
                    'Authorization' => 'Basic ' . base64_encode($this->apiKey . ':'),
                ])->post($this->baseUrl . '/token/');

                if ($response->successful()) {
                    $data = $response->json();
                    return $data['access_token'] ?? null;
                }

                Log::error('MTN Token Error: ' . $response->body());
                return null;

            } catch (\Exception $e) {
                Log::error('MTN Token Exception: ' . $e->getMessage());
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
