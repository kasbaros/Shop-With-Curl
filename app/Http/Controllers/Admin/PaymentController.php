<?php

    namespace App\Http\Controllers\Admin;

    use App\Models\Payment;
    use App\Models\PaymentLog;
    use App\Services\Payment\PaymentGatewayFactory;
    use App\Services\Payment\ManualPaymentService;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Carbon\Carbon;

    class PaymentController extends AdminController
    {
        public function index(Request $request)
        {
            $query = Payment::with(['order.user', 'logs'])
                ->orderBy('created_at', 'desc');

            // Apply filters
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('method')) {
                $query->where('payment_method', $request->method);
            }

            if ($request->filled('gateway')) {
                $query->where('gateway', $request->gateway);
            }

            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('transaction_id', 'like', "%{$search}%")
                        ->orWhere('gateway_transaction_id', 'like', "%{$search}%")
                        ->orWhereHas('order', function ($orderQuery) use ($search) {
                            $orderQuery->where('order_number', 'like', "%{$search}%");
                        });
                });
            }

            $payments = $query->paginate(20);

            // Payment statistics
            $stats = $this->getPaymentStats();

            // Filter options
            $statuses = Payment::distinct('status')->pluck('status');
            $methods = Payment::distinct('payment_method')->pluck('payment_method');
            $gateways = Payment::distinct('gateway')->pluck('gateway');

            return view('admin.payments.index', array_merge(
                $this->getAdminViewData(),
                compact('payments', 'stats', 'statuses', 'methods', 'gateways')
            ));
        }

        public function show(Payment $payment)
        {
            $payment->load(['order.user', 'order.items.product', 'logs']);

            return view('admin.payments.show', array_merge(
                $this->getAdminViewData(),
                compact('payment')
            ));
        }

        public function confirm(Request $request, Payment $payment)
        {
            $request->validate([
                'transaction_reference' => 'nullable|string|max:255',
                'notes' => 'nullable|string|max:1000',
                'confirmation_method' => 'required|string|in:admin_panel,phone_verification,receipt_upload',
            ]);

            try {
                $manualService = new ManualPaymentService();

                $result = $manualService->confirmPayment($payment, [
                    'admin_id' => auth()->id(),
                    'transaction_reference' => $request->transaction_reference,
                    'notes' => $request->notes,
                    'confirmation_method' => $request->confirmation_method,
                ]);

                if ($result['success']) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Payment confirmed successfully',
                        'status' => 'completed',
                    ]);
                }

                return response()->json([
                    'success' => false,
                    'message' => $result['message'],
                ], 400);

            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to confirm payment: ' . $e->getMessage(),
                ], 500);
            }
        }

        public function reject(Request $request, Payment $payment)
        {
            $request->validate([
                'reason' => 'required|string|max:1000',
            ]);

            try {
                $payment->markAsFailed($request->reason, [
                    'rejected_by' => auth()->id(),
                    'rejection_reason' => $request->reason,
                    'rejected_at' => now()->toISOString(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Payment rejected successfully',
                    'status' => 'failed',
                ]);

            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to reject payment: ' . $e->getMessage(),
                ], 500);
            }
        }

        public function retry(Payment $payment)
        {
            try {
                if (!in_array($payment->gateway, ['mtn', 'airtel', 'paypal', 'stripe'])) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Payment retry not supported for this gateway',
                    ], 400);
                }

                $gateway = PaymentGatewayFactory::create($payment->gateway);
                $result = $gateway->verifyPayment($payment);

                return response()->json($result);

            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment retry failed: ' . $e->getMessage(),
                ], 500);
            }
        }

        public function refund(Request $request, Payment $payment)
        {
            $request->validate([
                'amount' => 'nullable|numeric|min:0.01|max:' . $payment->amount,
                'reason' => 'required|string|max:1000',
            ]);

            try {
                $gateway = PaymentGatewayFactory::create($payment->gateway);
                $result = $gateway->refundPayment($payment, $request->amount);

                if ($result['success']) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Refund processed successfully',
                        'refund_amount' => $request->amount ?? $payment->amount,
                    ]);
                }

                return response()->json([
                    'success' => false,
                    'message' => $result['message'],
                ], 400);

            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Refund failed: ' . $e->getMessage(),
                ], 500);
            }
        }

        public function bulkAction(Request $request)
        {
            $request->validate([
                'action' => 'required|string|in:confirm,reject,export',
                'payment_ids' => 'required|array|min:1',
                'payment_ids.*' => 'exists:payments,id',
                'reason' => 'required_if:action,reject|string|max:1000',
            ]);

            try {
                $payments = Payment::whereIn('id', $request->payment_ids)->get();
                $processed = 0;

                foreach ($payments as $payment) {
                    switch ($request->action) {
                        case 'confirm':
                            if ($payment->status === 'pending') {
                                $manualService = new ManualPaymentService();
                                $result = $manualService->confirmPayment($payment, [
                                    'admin_id' => auth()->id(),
                                    'confirmation_method' => 'bulk_action',
                                    'notes' => 'Bulk confirmed by admin',
                                ]);
                                if ($result['success']) $processed++;
                            }
                            break;

                        case 'reject':
                            if (in_array($payment->status, ['pending', 'processing'])) {
                                $payment->markAsFailed($request->reason, [
                                    'rejected_by' => auth()->id(),
                                    'bulk_action' => true,
                                ]);
                                $processed++;
                            }
                            break;

                        case 'export':
                            // Export will be handled separately
                            $processed++;
                            break;
                    }
                }

                if ($request->action === 'export') {
                    return $this->exportPayments($payments);
                }

                return response()->json([
                    'success' => true,
                    'message' => "Bulk action completed. {$processed} payments processed.",
                    'processed' => $processed,
                ]);

            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bulk action failed: ' . $e->getMessage(),
                ], 500);
            }
        }

        public function analytics(Request $request)
        {
            $period = $request->get('period', '30d');
            $startDate = $this->getStartDate($period);

            $analytics = [
                'revenue' => $this->getRevenueAnalytics($startDate),
                'methods' => $this->getPaymentMethodAnalytics($startDate),
                'success_rates' => $this->getSuccessRateAnalytics($startDate),
                'trends' => $this->getPaymentTrends($startDate),
            ];

            return view('admin.payments.analytics', array_merge(
                $this->getAdminViewData(),
                compact('analytics', 'period')
            ));
        }

        public function logs(Payment $payment)
        {
            $logs = $payment->logs()->orderBy('created_at', 'desc')->paginate(10);

            return view('admin.payments.logs', array_merge(
                $this->getAdminViewData(),
                compact('payment', 'logs')
            ));
        }

        private function getPaymentStats()
        {
            $today = Carbon::today();
            $thisMonth = Carbon::now()->startOfMonth();

            return [
                'total_payments' => Payment::count(),
                'completed_payments' => Payment::where('status', 'completed')->count(),
                'pending_payments' => Payment::where('status', 'pending')->count(),
                'failed_payments' => Payment::where('status', 'failed')->count(),
                'today_revenue' => Payment::where('status', 'completed')
                    ->whereDate('created_at', $today)
                    ->sum('amount'),
                'month_revenue' => Payment::where('status', 'completed')
                    ->where('created_at', '>=', $thisMonth)
                    ->sum('amount'),
                'success_rate' => $this->getSuccessRate(),
                'avg_transaction_value' => Payment::where('status', 'completed')
                    ->avg('amount'),
            ];
        }

        private function getSuccessRate()
        {
            $total = Payment::count();
            if ($total === 0) return 0;

            $completed = Payment::where('status', 'completed')->count();
            return round(($completed / $total) * 100, 2);
        }

        private function getStartDate($period)
        {
            return match($period) {
                '7d' => Carbon::now()->subDays(7),
                '30d' => Carbon::now()->subDays(30),
                '90d' => Carbon::now()->subDays(90),
                '1y' => Carbon::now()->subYear(),
                default => Carbon::now()->subDays(30)
            };
        }

        private function getRevenueAnalytics($startDate)
        {
            return [
                'total' => Payment::where('status', 'completed')
                    ->where('created_at', '>=', $startDate)
                    ->sum('amount'),
                'count' => Payment::where('status', 'completed')
                    ->where('created_at', '>=', $startDate)
                    ->count(),
                'average' => Payment::where('status', 'completed')
                    ->where('created_at', '>=', $startDate)
                    ->avg('amount'),
            ];
        }

        private function getPaymentMethodAnalytics($startDate)
        {
            return Payment::where('created_at', '>=', $startDate)
                ->select('payment_method', DB::raw('count(*) as count'), DB::raw('sum(amount) as total'))
                ->groupBy('payment_method')
                ->get();
        }

        private function getSuccessRateAnalytics($startDate)
        {
            return Payment::where('created_at', '>=', $startDate)
                ->select('gateway',
                    DB::raw('count(*) as total'),
                    DB::raw('sum(case when status = "completed" then 1 else 0 end) as completed'))
                ->groupBy('gateway')
                ->get()
                ->map(function ($item) {
                    $item->success_rate = $item->total > 0 ? round(($item->completed / $item->total) * 100, 2) : 0;
                    return $item;
                });
        }

        private function getPaymentTrends($startDate)
        {
            return Payment::where('created_at', '>=', $startDate)
                ->select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('count(*) as count'),
                    DB::raw('sum(case when status = "completed" then amount else 0 end) as revenue')
                )
                ->groupBy('date')
                ->orderBy('date')
                ->get();
        }

        private function exportPayments($payments)
        {
            $filename = 'payments_export_' . now()->format('Y_m_d_H_i_s') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($payments) {
                $file = fopen('php://output', 'w');

                // Add CSV headers
                fputcsv($file, [
                    'Payment ID',
                    'Order Number',
                    'Customer',
                    'Payment Method',
                    'Gateway',
                    'Amount',
                    'Currency',
                    'Status',
                    'Transaction ID',
                    'Gateway Transaction ID',
                    'Created At',
                    'Paid At',
                ]);

                // Add data rows
                foreach ($payments as $payment) {
                    fputcsv($file, [
                        $payment->transaction_id,
                        $payment->order->order_number,
                        $payment->order->user->name ?? 'Guest',
                        ucwords(str_replace('_', ' ', $payment->payment_method)),
                        ucfirst($payment->gateway),
                        $payment->amount,
                        $payment->currency,
                        ucfirst($payment->status),
                        $payment->transaction_id,
                        $payment->gateway_transaction_id,
                        $payment->created_at->format('Y-m-d H:i:s'),
                        $payment->paid_at?->format('Y-m-d H:i:s'),
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }
    }
