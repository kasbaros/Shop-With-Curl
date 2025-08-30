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

            // Compute real stats
            $totalOrders = Order::count();
            $todayOrders = Order::whereDate('created_at', $today)->count();
            $totalRevenue = Order::whereNotIn('status', ['cancelled', 'refunded'])->sum('total_amount');
            $todayRevenue = Order::whereDate('created_at', $today)
                ->whereNotIn('status', ['cancelled', 'refunded'])
                ->sum('total_amount');

            $totalProducts = Product::count();
            $activeProducts = Product::where('is_active', true)->count();
            $outOfStock = Product::where('manage_stock', true)->where('stock_quantity', '<=', 0)->count();

            $totalCustomers = User::where('role', 'customer')->count();
            $newCustomersToday = User::where('role', 'customer')->whereDate('created_at', $today)->count();

            $pendingReviews = ProductReview::where('is_approved', false)->count();

            return [
                'total_orders' => $totalOrders,
                'today_orders' => $todayOrders,
                'total_revenue' => (float) $totalRevenue,
                'today_revenue' => (float) $todayRevenue,
                'total_products' => $totalProducts,
                'active_products' => $activeProducts,
                'out_of_stock' => $outOfStock,
                'total_customers' => $totalCustomers,
                'new_customers_today' => $newCustomersToday,
                'pending_reviews' => $pendingReviews,
            ];
        }

        private function getSalesData(): array
        {
            // Build a 30-day window with default zeros
            $startDate = Carbon::now()->subDays(29)->startOfDay();
            $endDate = Carbon::now()->endOfDay();

            $map = [];
            $labels = [];
            for ($d = 0; $d < 30; $d++) {
                $date = (clone $startDate)->copy()->addDays($d);
                $key = $date->format('Y-m-d');
                $map[$key] = ['orders' => 0, 'revenue' => 0.0];
                $labels[] = $date->format('M j');
            }

            // Aggregate orders per day from DB
            $daily = Order::select([
                DB::raw("DATE(created_at) as day"),
                DB::raw("COUNT(*) as orders"),
                DB::raw("SUM(total_amount) as revenue")
            ])
                ->whereBetween('created_at', [$startDate, $endDate])
                ->whereNotIn('status', ['cancelled', 'refunded'])
                ->groupBy(DB::raw('DATE(created_at)'))
                ->orderBy('day')
                ->get();

            foreach ($daily as $row) {
                $key = Carbon::parse($row->day)->format('Y-m-d');
                if (isset($map[$key])) {
                    $map[$key]['orders'] = (int) $row->orders;
                    $map[$key]['revenue'] = (float) $row->revenue;
                }
            }

            return [
                'labels' => $labels,
                'orders' => array_values(array_map(fn($v) => $v['orders'], $map)),
                'revenue' => array_values(array_map(fn($v) => round($v['revenue'], 2), $map)),
            ];
        }
    }
