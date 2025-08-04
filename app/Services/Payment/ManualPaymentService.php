<?php

    namespace App\Services\Payment;

    use App\Models\Payment;
    use App\Models\PaymentLog;
    use App\Notifications\ManualPaymentReceived;
    use App\Notifications\PaymentConfirmationRequested;
    use Illuminate\Support\Facades\Log;
    use Illuminate\Support\Facades\Notification;

    class ManualPaymentService implements PaymentGatewayInterface
    {
        public function initiatePayment(Payment $payment): array
        {
            try {
                $paymentMethod = $payment->payment_method;

                // Log the initiation
                PaymentLog::create([
                    'payment_id' => $payment->id,
                    'type' => 'request',
                    'gateway' => 'manual',
                    'data' => [
                        'payment_method' => $paymentMethod,
                        'amount' => $payment->amount,
                        'currency' => $payment->currency,
                        'initiated_at' => now()->toISOString(),
                    ],
                    'status' => 200,
                ]);

                switch ($paymentMethod) {
                    case 'manual_momo':
                        return $this->initiateManualMomo($payment);

                    case 'bank_transfer':
                        return $this->initiateBankTransfer($payment);

                    case 'cod':
                        return $this->initiateCOD($payment);

                    default:
                        throw new \Exception("Unsupported manual payment method: {$paymentMethod}");
                }

            } catch (\Exception $e) {
                Log::error('Manual Payment Initiation Error: ' . $e->getMessage());

                PaymentLog::create([
                    'payment_id' => $payment->id,
                    'type' => 'error',
                    'gateway' => 'manual',
                    'data' => ['error' => $e->getMessage()],
                ]);

                return [
                    'success' => false,
                    'message' => 'Payment initialization failed: ' . $e->getMessage(),
                ];
            }
        }

        public function verifyPayment(Payment $payment): array
        {
            try {
                // For manual payments, verification is typically done by admin
                $status = $payment->status;

                PaymentLog::create([
                    'payment_id' => $payment->id,
                    'type' => 'response',
                    'gateway' => 'manual',
                    'data' => [
                        'status' => $status,
                        'verified_at' => now()->toISOString(),
                    ],
                    'status' => 200,
                ]);

                return [
                    'success' => true,
                    'status' => $status,
                    'message' => $this->getStatusMessage($status, $payment->payment_method),
                    'requires_admin_approval' => in_array($status, ['pending', 'processing']),
                ];

            } catch (\Exception $e) {
                Log::error('Manual Payment Verification Error: ' . $e->getMessage());

                return [
                    'success' => false,
                    'message' => 'Payment verification failed',
                ];
            }
        }

        public function handleWebhook(array $payload): array
        {
            // Manual payments don't typically have webhooks
            // But we can handle admin confirmations here
            try {
                $action = $payload['action'] ?? '';
                $paymentId = $payload['payment_id'] ?? null;

                if ($action === 'confirm' && $paymentId) {
                    $payment = Payment::find($paymentId);

                    if ($payment) {
                        return $this->confirmPayment($payment, $payload);
                    }
                }

                return ['success' => true, 'message' => 'Webhook processed'];

            } catch (\Exception $e) {
                Log::error('Manual Payment Webhook Error: ' . $e->getMessage());
                return ['success' => false, 'message' => 'Webhook processing failed'];
            }
        }

        public function refundPayment(Payment $payment, float $amount = null): array
        {
            try {
                $refundAmount = $amount ?? $payment->amount;

                // For manual payments, refunds need to be processed manually
                $payment->update([
                    'status' => 'refund_requested',
                    'metadata' => array_merge($payment->metadata ?? [], [
                        'refund_amount' => $refundAmount,
                        'refund_requested_at' => now()->toISOString(),
                    ]),
                ]);

                PaymentLog::create([
                    'payment_id' => $payment->id,
                    'type' => 'refund_request',
                    'gateway' => 'manual',
                    'data' => [
                        'refund_amount' => $refundAmount,
                        'original_amount' => $payment->amount,
                        'requested_at' => now()->toISOString(),
                    ],
                ]);

                // Notify admin about refund request
                $this->notifyAdminAboutRefund($payment, $refundAmount);

                return [
                    'success' => true,
                    'message' => 'Refund request submitted. Admin will process manually.',
                    'refund_amount' => $refundAmount,
                    'status' => 'refund_requested',
                ];

            } catch (\Exception $e) {
                Log::error('Manual Payment Refund Error: ' . $e->getMessage());

                return [
                    'success' => false,
                    'message' => 'Refund request failed',
                ];
            }
        }

        // Admin method to confirm payment
        public function confirmPayment(Payment $payment, array $data = []): array
        {
            try {
                $transactionReference = $data['transaction_reference'] ?? 'MANUAL_' . strtoupper(\Str::random(8));
                $notes = $data['notes'] ?? 'Payment confirmed by admin';

                $payment->markAsCompleted($transactionReference, [
                    'confirmed_by' => $data['admin_id'] ?? auth()->id(),
                    'confirmation_method' => $data['confirmation_method'] ?? 'admin_panel',
                    'transaction_reference' => $transactionReference,
                    'notes' => $notes,
                    'confirmed_at' => now()->toISOString(),
                ]);

                PaymentLog::create([
                    'payment_id' => $payment->id,
                    'type' => 'confirmation',
                    'gateway' => 'manual',
                    'data' => [
                        'confirmed_by' => $data['admin_id'] ?? auth()->id(),
                        'transaction_reference' => $transactionReference,
                        'notes' => $notes,
                        'confirmed_at' => now()->toISOString(),
                    ],
                ]);

                // Notify customer about payment confirmation
                $this->notifyCustomerPaymentConfirmed($payment);

                return [
                    'success' => true,
                    'message' => 'Payment confirmed successfully',
                    'transaction_reference' => $transactionReference,
                    'status' => 'completed',
                ];

            } catch (\Exception $e) {
                Log::error('Manual Payment Confirmation Error: ' . $e->getMessage());

                return [
                    'success' => false,
                    'message' => 'Payment confirmation failed',
                ];
            }
        }

        private function initiateManualMomo(Payment $payment): array
        {
            $payment->update([
                'status' => 'pending',
                'metadata' => array_merge($payment->metadata ?? [], [
                    'instructions_sent' => true,
                    'business_number' => setting('momo_business_number'),
                    'business_name' => setting('momo_business_name'),
                    'initiated_at' => now()->toISOString(),
                ]),
            ]);

            // Notify admin about new manual payment
            $this->notifyAdminAboutNewPayment($payment);

            return [
                'success' => true,
                'status' => 'pending',
                'instructions' => $this->getManualMomoInstructions($payment),
                'business_number' => setting('momo_business_number'),
                'business_name' => setting('momo_business_name'),
                'amount' => format_currency($payment->amount, $payment->currency),
                'reference' => $payment->transaction_id,
                'message' => 'Please send money to the provided number and wait for confirmation.',
            ];
        }

        private function initiateBankTransfer(Payment $payment): array
        {
            $payment->update([
                'status' => 'pending',
                'metadata' => array_merge($payment->metadata ?? [], [
                    'bank_details_provided' => true,
                    'bank_name' => setting('bank_name'),
                    'account_number' => setting('bank_account_number'),
                    'account_name' => setting('bank_account_name'),
                    'initiated_at' => now()->toISOString(),
                ]),
            ]);

            $this->notifyAdminAboutNewPayment($payment);

            return [
                'success' => true,
                'status' => 'pending',
                'instructions' => $this->getBankTransferInstructions($payment),
                'bank_details' => [
                    'bank_name' => setting('bank_name'),
                    'account_number' => setting('bank_account_number'),
                    'account_name' => setting('bank_account_name'),
                    'branch' => setting('bank_branch'),
                ],
                'amount' => format_currency($payment->amount, $payment->currency),
                'reference' => $payment->transaction_id,
                'message' => 'Please transfer money to the provided bank account and wait for confirmation.',
            ];
        }

        private function initiateCOD(Payment $payment): array
        {
            $codFee = (float) setting('cod_fee', 0);
            $totalAmount = $payment->amount + $codFee;

            $payment->update([
                'status' => 'pending',
                'metadata' => array_merge($payment->metadata ?? [], [
                    'cod_fee' => $codFee,
                    'total_amount_with_fee' => $totalAmount,
                    'delivery_confirmed' => false,
                    'initiated_at' => now()->toISOString(),
                ]),
            ]);

            // For COD, we can immediately confirm the order for processing
            $payment->order->update(['status' => 'confirmed']);

            return [
                'success' => true,
                'status' => 'pending',
                'instructions' => setting('cod_instructions', 'Please have exact amount ready. Our delivery agent will collect payment upon delivery.'),
                'cod_fee' => $codFee,
                'order_amount' => format_currency($payment->amount, $payment->currency),
                'cod_fee_formatted' => format_currency($codFee, $payment->currency),
                'total_amount' => format_currency($totalAmount, $payment->currency),
                'message' => 'Your order has been confirmed for Cash on Delivery.',
            ];
        }

        private function getManualMomoInstructions(Payment $payment): string
        {
            $businessNumber = setting('momo_business_number');
            $businessName = setting('momo_business_name');
            $amount = format_currency($payment->amount, $payment->currency);
            $reference = $payment->transaction_id;

            return "MOBILE MONEY PAYMENT INSTRUCTIONS:\n\n" .
                "1. Send {$amount} to: {$businessNumber}\n" .
                "2. Account Name: {$businessName}\n" .
                "3. Use Reference: {$reference}\n" .
                "4. Take screenshot of confirmation\n" .
                "5. Wait for our confirmation (usually within 30 minutes)\n" .
                "6. Contact us at " . setting('store_phone') . " if you need help";
        }

        private function getBankTransferInstructions(Payment $payment): string
        {
            $bankName = setting('bank_name');
            $accountNumber = setting('bank_account_number');
            $accountName = setting('bank_account_name');
            $branch = setting('bank_branch');
            $amount = format_currency($payment->amount, $payment->currency);
            $reference = $payment->transaction_id;

            return "BANK TRANSFER INSTRUCTIONS:\n\n" .
                "Bank: {$bankName}\n" .
                "Account Number: {$accountNumber}\n" .
                "Account Name: {$accountName}\n" .
                "Branch: {$branch}\n" .
                "Amount: {$amount}\n" .
                "Reference: {$reference}\n\n" .
                "Please include the reference number in your transfer description.\n" .
                "Allow 2-4 hours for verification during business hours.";
        }

        private function getStatusMessage(string $status, string $paymentMethod): string
        {
            $messages = [
                'pending' => [
                    'manual_momo' => 'Waiting for mobile money payment confirmation',
                    'bank_transfer' => 'Waiting for bank transfer confirmation',
                    'cod' => 'Order confirmed - will be delivered for cash payment',
                ],
                'processing' => [
                    'manual_momo' => 'Mobile money payment is being verified',
                    'bank_transfer' => 'Bank transfer is being verified',
                    'cod' => 'Order is being prepared for delivery',
                ],
                'completed' => [
                    'manual_momo' => 'Mobile money payment confirmed',
                    'bank_transfer' => 'Bank transfer confirmed',
                    'cod' => 'Cash payment received on delivery',
                ],
                'failed' => [
                    'manual_momo' => 'Mobile money payment failed or cancelled',
                    'bank_transfer' => 'Bank transfer failed or cancelled',
                    'cod' => 'Cash on delivery cancelled',
                ],
            ];

            return $messages[$status][$paymentMethod] ?? 'Payment status: ' . ucfirst($status);
        }

        private function notifyAdminAboutNewPayment(Payment $payment): void
        {
            try {
                // You can implement admin notification here
                // For now, we'll just log it
                Log::info("New manual payment initiated", [
                    'payment_id' => $payment->id,
                    'method' => $payment->payment_method,
                    'amount' => $payment->amount,
                    'order_id' => $payment->order_id,
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to notify admin about new payment: ' . $e->getMessage());
            }
        }

        private function notifyCustomerPaymentConfirmed(Payment $payment): void
        {
            try {
                // You can implement customer notification here
                Log::info("Payment confirmed for customer", [
                    'payment_id' => $payment->id,
                    'order_id' => $payment->order_id,
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to notify customer about payment confirmation: ' . $e->getMessage());
            }
        }

        private function notifyAdminAboutRefund(Payment $payment, float $amount): void
        {
            try {
                Log::info("Refund requested", [
                    'payment_id' => $payment->id,
                    'refund_amount' => $amount,
                    'original_amount' => $payment->amount,
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to notify admin about refund request: ' . $e->getMessage());
            }
        }
    }
