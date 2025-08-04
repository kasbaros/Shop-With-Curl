<?php

    namespace App\Http\Controllers\Admin;

    use App\Models\{Product, Order, User, ProductReview};
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Carbon\Carbon;

    class DashboardController extends AdminController
    {
        public function index()
        {
            // Get basic statistics
            $stats = $this->getStats();

            // Get recent orders
            $recentOrders = collect(); // Empty collection for now
            if (class_exists('App\Models\Order')) {
                $recentOrders = Order::with('user')
                    ->latest()
                    ->limit(10)
                    ->get();
            }

            // Get low stock products
            $lowStockProducts = collect(); // Empty collection for now
            if (class_exists('App\Models\Product')) {
                $lowStockProducts = Product::where('manage_stock', true)
                    ->where('stock_quantity', '<=', DB::raw('COALESCE(min_stock_level, 5)'))
                    ->orderBy('stock_quantity', 'asc')
                    ->limit(10)
                    ->get();
            }

            // Get top selling products (last 30 days)
            $topProducts = collect(); // Empty collection for now

            // Get sales data for chart (last 30 days)
            $salesData = $this->getSalesData();

            return view('admin.dashboard.index', array_merge(
                $this->getAdminViewData(),
                compact('stats', 'recentOrders', 'lowStockProducts', 'topProducts', 'salesData')
            ));
        }

        private function getStats(): array
        {
            $today = Carbon::today();

            return [
                'total_orders' => 0, // Will be: Order::count(),
                'today_orders' => 0, // Will be: Order::whereDate('created_at', $today)->count(),
                'total_revenue' => 0, // Will be: Order::where('status', '!=', 'cancelled')->sum('total_amount'),
                'today_revenue' => 0, // Will be: Order::whereDate('created_at', $today)->where('status', '!=', 'cancelled')->sum('total_amount'),
                'total_products' => 0, // Will be: Product::count(),
                'active_products' => 0, // Will be: Product::where('is_active', true)->count(),
                'out_of_stock' => 0, // Will be: Product::where('manage_stock', true)->where('stock_quantity', '<=', 0)->count(),
                'total_customers' => User::where('role', 'customer')->count(),
                'new_customers_today' => User::where('role', 'customer')
                    ->whereDate('created_at', $today)
                    ->count(),
                'pending_reviews' => 0, // Will be: ProductReview::where('is_approved', false)->count(),
            ];
        }

        private function getSalesData(): array
        {
            $days = collect(range(29, 0))->map(function ($daysBack) {
                $date = Carbon::now()->subDays($daysBack);

                return [
                    'date' => $date->format('M j'),
                    'orders' => 0, // Will be actual data
                    'revenue' => 0, // Will be actual data
                ];
            });

            return [
                'labels' => $days->pluck('date')->toArray(),
                'orders' => $days->pluck('orders')->toArray(),
                'revenue' => $days->pluck('revenue')->toArray(),
            ];
        }
    }
