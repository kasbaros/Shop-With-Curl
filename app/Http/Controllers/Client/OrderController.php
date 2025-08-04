<?php

    namespace App\Http\Controllers\Client;

    use App\Http\Controllers\Controller;
    use App\Models\Order;
    use Illuminate\Support\Facades\Auth;

    class OrderController extends Controller
    {
        public function __construct()
        {
            $this->middleware('auth');
        }

        public function index()
        {
            $orders = Auth::user()->orders()->with(['items.product', 'payments'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return view('user.orders', compact('orders'));
        }

        public function show(Order $order)
        {
            // Ensure user can only view their own orders
            if ($order->user_id !== Auth::id()) {
                abort(403);
            }

            $order->load(['items.product', 'items.productVariant', 'payments']);
            return view('user.order-detail', compact('order'));
        }

        public function cancel(Order $order)
        {
            if ($order->user_id !== Auth::id()) {
                abort(403);
            }

            if (!$order->canBeCancelled()) {
                return back()->with('error', 'This order cannot be cancelled.');
            }

            $order->update(['status' => 'cancelled']);
            return back()->with('success', 'Order cancelled successfully.');
        }

        public function invoice(Order $order)
        {
            if ($order->user_id !== Auth::id()) {
                abort(403);
            }

            return view('user.order-invoice', compact('order'));
        }
    }
