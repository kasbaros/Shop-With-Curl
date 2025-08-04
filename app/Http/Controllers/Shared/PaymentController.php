<?php

    namespace App\Http\Controllers\Shared;

    use App\Http\Controllers\Controller;
    use App\Models\Order;
    use App\Models\Payment;
    use App\Services\Payment\PaymentGatewayFactory;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Log;

    class PaymentController extends Controller
    {
        public function initiate(Request $request, Order $order)
        {
            $this->logApiRequest('payment.initiate', 'POST', [
                'order_id' => $order->id,
                'payment_method' => $request->payment_method,
            ]);

            $request->validate([
                'payment_method' => 'required|string',
                'phone' => 'required_if:payment_method,mtn_momo,airtel_money,manual_momo|string',
            ]);

            try {
                // Create payment record
                $payment = Payment::create([
                    'order_id' => $order->id,
                    'payment_method' => $request->payment_method,
                    'gateway' => $this->getGatewayFromMethod($request->payment_method),
                    'amount' => $order->total_amount,
                    'currency' => setting('default_currency', 'UGX'),
                    'metadata' => [
                        'phone' => $request->phone,
                        'user_id' => auth()->id(),
                        'ip_address' => $request->ip(),
                    ],
                ]);

                // Log payment initiation using trait
                $payment->logPaymentInitiation([
                    'initiated_by' => auth()->id(),
                    'source' => 'web_api',
                ]);

                // Handle different payment methods
                if (in_array($request->payment_method, ['mtn_momo', 'airtel_money', 'paypal', 'stripe'])) {
                    $gateway = PaymentGatewayFactory::create($payment->gateway);
                    $result = $gateway->initiatePayment($payment);

                    if ($result['success']) {
                        $this->logApiResponse(200, [
                            'payment_id' => $payment->uuid,
                            'gateway_used' => $payment->gateway,
                        ]);

                        return response()->json([
                            'success' => true,
                            'payment_id' => $payment->uuid,
                            'data' => $result,
                        ]);
                    }

                    $payment->logPaymentError($result['message'], [
                        'gateway_response' => $result,
                    ]);

                    return response()->json([
                        'success' => false,
                        'message' => $result['message'],
                    ], 400);
                }

                // Handle manual/COD payments
                if ($request->payment_method === 'manual_momo') {
                    return $this->handleManualMomo($payment);
                }

                if ($request->payment_method === 'cod') {
                    return $this->handleCOD($payment);
                }

                return response()->json([
                    'success' => false,
                    'message' => 'Unsupported payment method',
                ], 400);

            } catch (\Exception $e) {
                Log::channel('api_logs')->error('Payment Initiation Error', [
                    'error' => $e->getMessage(),
                    'stack_trace' => $e->getTraceAsString(),
                    'order_id' => $order->id,
                    'payment_method' => $request->payment_method,
                    'user_id' => auth()->id(),
                ]);

                $this->logApiResponse(500, [
                    'error' => 'Payment initialization failed',
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Payment initialization failed',
                ], 500);
            }
        }

        public function verify(Request $request, $paymentUuid)
        {
            $payment = Payment::where('uuid', $paymentUuid)->firstOrFail();

            try {
                if (in_array($payment->gateway, ['mtn', 'airtel', 'paypal', 'stripe'])) {
                    $gateway = PaymentGatewayFactory::create($payment->gateway);
                    $result = $gateway->verifyPayment($payment);

                    return response()->json($result);
                }

                return response()->json([
                    'success' => true,
                    'status' => $payment->status,
                    'message' => 'Payment status retrieved',
                ]);

            } catch (\Exception $e) {
                Log::error('Payment Verification Error: ' . $e->getMessage());

                return response()->json([
                    'success' => false,
                    'message' => 'Payment verification failed',
                ], 500);
            }
        }

        public function webhook(Request $request, $gateway)
        {
            try {
                $gatewayService = PaymentGatewayFactory::create($gateway);
                $result = $gatewayService->handleWebhook($request->all());

                return response()->json(['status' => 'success']);

            } catch (\Exception $e) {
                Log::error("Webhook Error ({$gateway}): " . $e->getMessage());
                return response()->json(['status' => 'error'], 500);
            }
        }

        public function success($paymentUuid)
        {
            $payment = Payment::where('uuid', $paymentUuid)->firstOrFail();

            return view('payment.success', compact('payment'));
        }

        public function cancel($paymentUuid)
        {
            $payment = Payment::where('uuid', $paymentUuid)->firstOrFail();
            $payment->update(['status' => 'cancelled']);

            return view('payment.cancel', compact('payment'));
        }

        private function getGatewayFromMethod($method): string
        {
            return match($method) {
                'mtn_momo' => 'mtn',
                'airtel_money' => 'airtel',
                'paypal' => 'paypal',
                'stripe' => 'stripe',
                'manual_momo', 'cod', 'bank_transfer' => 'manual',
                default => 'manual'
            };
        }

        private function handleManualMomo(Payment $payment)
        {
            $payment->update(['status' => 'pending']);

            return response()->json([
                'success' => true,
                'payment_id' => $payment->uuid,
                'data' => [
                    'status' => 'pending',
                    'instructions' => $this->getManualMomoInstructions(),
                    'business_number' => setting('momo_business_number'),
                    'business_name' => setting('momo_business_name'),
                    'amount' => $payment->formatted_amount,
                    'reference' => $payment->transaction_id,
                ],
            ]);
        }

        private function handleCOD(Payment $payment)
        {
            $payment->update(['status' => 'pending']);
            $payment->order->update(['status' => 'confirmed']);

            return response()->json([
                'success' => true,
                'payment_id' => $payment->uuid,
                'data' => [
                    'status' => 'pending',
                    'message' => 'Your order has been confirmed. Pay cash on delivery.',
                    'instructions' => setting('cod_instructions'),
                    'cod_fee' => setting('cod_fee', 0),
                ],
            ]);
        }

        private function getManualMomoInstructions(): string
        {
            return "1. Send {amount} to {number}\n2. Use reference: {reference}\n3. Wait for confirmation\n4. Contact us if you need help";
        }
    }
