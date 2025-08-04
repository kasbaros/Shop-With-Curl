<?php

    namespace App\Http\Controllers\Admin;

    use App\Models\Order;
    use Illuminate\Http\Request;

    class OrderController extends AdminController
    {
        public function index(Request $request)
        {
            $query = Order::with(['user', 'items.product']);

            // Search
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('order_number', 'ILIKE', "%{$search}%")
                        ->orWhere('email', 'ILIKE', "%{$search}%")
                        ->orWhereHas('user', function($userQuery) use ($search) {
                            $userQuery->where('name', 'ILIKE', "%{$search}%")
                                ->orWhere('email', 'ILIKE', "%{$search}%");
                        });
                });
            }

            // Status filter
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Date range filter
            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            // Amount filter
            if ($request->filled('min_amount')) {
                $query->where('total_amount', '>=', $request->min_amount);
            }
            if ($request->filled('max_amount')) {
                $query->where('total_amount', '<=', $request->max_amount);
            }

            // Sort
            $sortField = $request->get('sort', 'created_at');
            $sortDirection = $request->get('direction', 'desc');
            $query->orderBy($sortField, $sortDirection);

            $orders = $query->paginate(20)->withQueryString();

            // Get statistics for dashboard cards
            $stats = $this->getOrderStats();

            return view('admin.orders.index', array_merge(
                $this->getAdminViewData(),
                compact('orders', 'stats')
            ));
        }

        public function show(Order $order)
        {
            $order->load([
                'user',
                'items.product.images',
                'shippingAddress',
                'billingAddress'
            ]);

            return view('admin.orders.show', array_merge(
                $this->getAdminViewData(),
                compact('order')
            ));
        }

        public function edit(Order $order)
        {
            $order->load(['items.product', 'shippingAddress', 'billingAddress']);

            return view('admin.orders.edit', array_merge(
                $this->getAdminViewData(),
                compact('order')
            ));
        }

        public function update(Request $request, Order $order)
        {
            $validated = $request->validate([
                'status' => 'required|in:pending,processing,shipped,delivered,cancelled,refunded',
                'notes' => 'nullable|string',
                'tracking_number' => 'nullable|string|max:100',
                'shipping_method' => 'nullable|string|max:100',
                'shipping_cost' => 'nullable|numeric|min:0',
            ]);

            $order->update($validated);

            // Log status change if status was updated
            if ($order->wasChanged('status')) {
                $this->logStatusChange($order, $request->user());
            }

            return redirect()
                ->route('admin.orders.show', $order)
                ->with('success', 'Order updated successfully!');
        }

        public function destroy(Order $order)
        {
            // Only allow deletion of cancelled orders
            if (!in_array($order->status, ['cancelled', 'refunded'])) {
                return redirect()
                    ->route('admin.orders.index')
                    ->with('error', 'Only cancelled or refunded orders can be deleted.');
            }

            $order->delete();

            return redirect()
                ->route('admin.orders.index')
                ->with('success', 'Order deleted successfully!');
        }

        public function updateStatus(Request $request, Order $order)
        {
            $request->validate([
                'status' => 'required|in:pending,processing,shipped,delivered,cancelled,refunded',
                'notes' => 'nullable|string',
            ]);

            $oldStatus = $order->status;

            $order->update([
                'status' => $request->status,
                'notes' => $request->notes,
            ]);

            // Log status change
            $this->logStatusChange($order, $request->user(), $oldStatus);

            // Handle inventory based on status change
            $this->handleInventoryUpdate($order, $oldStatus, $request->status);

            return response()->json([
                'success' => true,
                'message' => 'Order status updated successfully!',
                'status' => $order->status,
                'status_badge' => $this->getStatusBadge($order->status)
            ]);
        }

        private function getOrderStats(): array
        {
            return [
                'total_orders' => Order::count(),
                'pending_orders' => Order::where('status', 'pending')->count(),
                'processing_orders' => Order::where('status', 'processing')->count(),
                'shipped_orders' => Order::where('status', 'shipped')->count(),
                'delivered_orders' => Order::where('status', 'delivered')->count(),
                'cancelled_orders' => Order::where('status', 'cancelled')->count(),
                'total_revenue' => Order::whereNotIn('status', ['cancelled', 'refunded'])->sum('total_amount'),
                'today_orders' => Order::whereDate('created_at', today())->count(),
                'today_revenue' => Order::whereDate('created_at', today())
                    ->whereNotIn('status', ['cancelled', 'refunded'])
                    ->sum('total_amount'),
            ];
        }

        private function logStatusChange(Order $order, $user, $oldStatus = null)
        {
            $oldStatus = $oldStatus ?? $order->getOriginal('status');

            // Here you could log to a separate order_status_changes table
            // For now, we'll just log to Laravel's log
            \Log::info('Order status changed', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'old_status' => $oldStatus,
                'new_status' => $order->status,
                'changed_by' => $user->id,
                'changed_at' => now(),
            ]);
        }

        private function handleInventoryUpdate(Order $order, string $oldStatus, string $newStatus)
        {
            // If order is cancelled or refunded, restore inventory
            if (in_array($newStatus, ['cancelled', 'refunded']) && !in_array($oldStatus, ['cancelled', 'refunded'])) {
                foreach ($order->items as $item) {
                    if ($item->product && $item->product->manage_stock) {
                        $item->product->increment('stock_quantity', $item->quantity);
                    }
                }
            }

            // If order was cancelled/refunded and now is not, reduce inventory
            if (!in_array($newStatus, ['cancelled', 'refunded']) && in_array($oldStatus, ['cancelled', 'refunded'])) {
                foreach ($order->items as $item) {
                    if ($item->product && $item->product->manage_stock) {
                        $item->product->decrement('stock_quantity', $item->quantity);
                    }
                }
            }
        }

        private function getStatusBadge(string $status): string
        {
            $badges = [
                'pending' => '<span class="badge bg-warning">Pending</span>',
                'processing' => '<span class="badge bg-info">Processing</span>',
                'shipped' => '<span class="badge bg-primary">Shipped</span>',
                'delivered' => '<span class="badge bg-success">Delivered</span>',
                'cancelled' => '<span class="badge bg-danger">Cancelled</span>',
                'refunded' => '<span class="badge bg-secondary">Refunded</span>',
            ];

            return $badges[$status] ?? '<span class="badge bg-secondary">Unknown</span>';
        }
    }
