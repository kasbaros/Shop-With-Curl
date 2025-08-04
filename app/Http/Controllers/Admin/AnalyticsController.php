<?php

    namespace App\Http\Controllers\Admin;

    use App\Models\Order;
    use App\Models\Product;
    use App\Models\User;
    use App\Models\Category;
    use App\Models\Review;
    use App\Models\OrderItem;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Carbon\Carbon;

    class AnalyticsController extends AdminController
    {
        public function index(Request $request)
        {
            $dateRange = $this->getDateRange($request);

            // Core metrics
            $metrics = $this->getCoreMetrics($dateRange);

            // Chart data
            $salesChart = $this->getSalesChartData($dateRange);
            $ordersChart = $this->getOrdersChartData($dateRange);
            $topProducts = $this->getTopProducts($dateRange);
            $topCategories = $this->getTopCategories($dateRange);
            $customerMetrics = $this->getCustomerMetrics($dateRange);
            $recentOrders = $this->getRecentOrders();

            return view('admin.analytics.index', array_merge(
                $this->getAdminViewData(),
                compact(
                    'metrics',
                    'salesChart',
                    'ordersChart',
                    'topProducts',
                    'topCategories',
                    'customerMetrics',
                    'recentOrders',
                    'dateRange'
                )
            ));
        }

        public function sales(Request $request)
        {
            $dateRange = $this->getDateRange($request);

            $salesData = $this->getDetailedSalesData($dateRange);
            $salesByCategory = $this->getSalesByCategory($dateRange);
            $salesByProduct = $this->getSalesByProduct($dateRange);
            $monthlyTrends = $this->getMonthlySalesTrends();
            $paymentMethods = $this->getPaymentMethodsData($dateRange);

            return view('admin.analytics.sales', array_merge(
                $this->getAdminViewData(),
                compact(
                    'salesData',
                    'salesByCategory',
                    'salesByProduct',
                    'monthlyTrends',
                    'paymentMethods',
                    'dateRange'
                )
            ));
        }

        public function products(Request $request)
        {
            $dateRange = $this->getDateRange($request);

            $productMetrics = $this->getProductMetrics($dateRange);
            $topPerformers = $this->getTopPerformingProducts($dateRange);
            $lowStock = $this->getLowStockProducts();
            $categoryPerformance = $this->getCategoryPerformance($dateRange);
            $productViews = $this->getProductViewsData($dateRange);

            return view('admin.analytics.products', array_merge(
                $this->getAdminViewData(),
                compact(
                    'productMetrics',
                    'topPerformers',
                    'lowStock',
                    'categoryPerformance',
                    'productViews',
                    'dateRange'
                )
            ));
        }

        public function customers(Request $request)
        {
            $dateRange = $this->getDateRange($request);

            $customerMetrics = $this->getDetailedCustomerMetrics($dateRange);
            $topCustomers = $this->getTopCustomers($dateRange);
            $customerGrowth = $this->getCustomerGrowth();
            $customerSegments = $this->getCustomerSegments();
            $customerRetention = $this->getCustomerRetention();

            return view('admin.analytics.customers', array_merge(
                $this->getAdminViewData(),
                compact(
                    'customerMetrics',
                    'topCustomers',
                    'customerGrowth',
                    'customerSegments',
                    'customerRetention',
                    'dateRange'
                )
            ));
        }

        public function export(Request $request)
        {
            $type = $request->get('type', 'sales');
            $format = $request->get('format', 'csv');
            $dateRange = $this->getDateRange($request);

            switch ($type) {
                case 'sales':
                    return $this->exportSalesReport($dateRange, $format);
                case 'products':
                    return $this->exportProductsReport($dateRange, $format);
                case 'customers':
                    return $this->exportCustomersReport($dateRange, $format);
                case 'orders':
                    return $this->exportOrdersReport($dateRange, $format);
                default:
                    return back()->with('error', 'Invalid export type');
            }
        }

        private function getDateRange(Request $request): array
        {
            $period = $request->get('period', '30days');

            switch ($period) {
                case '7days':
                    return [
                        'start' => Carbon::now()->subDays(7),
                        'end' => Carbon::now(),
                        'label' => 'Last 7 Days'
                    ];
                case '30days':
                    return [
                        'start' => Carbon::now()->subDays(30),
                        'end' => Carbon::now(),
                        'label' => 'Last 30 Days'
                    ];
                case '90days':
                    return [
                        'start' => Carbon::now()->subDays(90),
                        'end' => Carbon::now(),
                        'label' => 'Last 90 Days'
                    ];
                case 'year':
                    return [
                        'start' => Carbon::now()->subYear(),
                        'end' => Carbon::now(),
                        'label' => 'Last Year'
                    ];
                case 'custom':
                    return [
                        'start' => Carbon::parse($request->get('start_date', Carbon::now()->subDays(30))),
                        'end' => Carbon::parse($request->get('end_date', Carbon::now())),
                        'label' => 'Custom Range'
                    ];
                default:
                    return [
                        'start' => Carbon::now()->subDays(30),
                        'end' => Carbon::now(),
                        'label' => 'Last 30 Days'
                    ];
            }
        }

        private function getCoreMetrics(array $dateRange): array
        {
            $currentPeriodOrders = Order::whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);
            $previousPeriodStart = $dateRange['start']->copy()->sub($dateRange['end']->diffInDays($dateRange['start']), 'days');
            $previousPeriodOrders = Order::whereBetween('created_at', [$previousPeriodStart, $dateRange['start']]);

            return [
                'total_revenue' => [
                    'current' => $currentPeriodOrders->sum('total_amount'),
                    'previous' => $previousPeriodOrders->sum('total_amount'),
                ],
                'total_orders' => [
                    'current' => $currentPeriodOrders->count(),
                    'previous' => $previousPeriodOrders->count(),
                ],
                'average_order_value' => [
                    'current' => $currentPeriodOrders->avg('total_amount') ?: 0,
                    'previous' => $previousPeriodOrders->avg('total_amount') ?: 0,
                ],
                'new_customers' => [
                    'current' => User::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])->count(),
                    'previous' => User::whereBetween('created_at', [$previousPeriodStart, $dateRange['start']])->count(),
                ],
                'conversion_rate' => [
                    'current' => $this->calculateConversionRate($dateRange),
                    'previous' => $this->calculateConversionRate(['start' => $previousPeriodStart, 'end' => $dateRange['start']]),
                ],
            ];
        }

        private function getSalesChartData(array $dateRange): array
        {
            $days = $dateRange['start']->diffInDays($dateRange['end']);
            $groupBy = $days > 90 ? 'week' : 'day';

            $query = Order::selectRaw("
            DATE_TRUNC('{$groupBy}', created_at) as period,
            SUM(total_amount) as revenue,
            COUNT(*) as orders
        ")
                ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                ->whereNotIn('status', ['cancelled', 'refunded'])
                ->groupBy('period')
                ->orderBy('period');

            return $query->get()->map(function ($item) use ($groupBy) {
                return [
                    'period' => Carbon::parse($item->period)->format($groupBy === 'week' ? 'M j' : 'M j'),
                    'revenue' => (float) $item->revenue,
                    'orders' => (int) $item->orders,
                ];
            })->toArray();
        }

        private function getOrdersChartData(array $dateRange): array
        {
            return Order::selectRaw('
            status,
            COUNT(*) as count,
            SUM(total_amount) as total
        ')
                ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                ->groupBy('status')
                ->get()
                ->mapWithKeys(function ($item) {
                    return [$item->status => [
                        'count' => (int) $item->count,
                        'total' => (float) $item->total,
                    ]];
                })
                ->toArray();
        }

        private function getTopProducts(array $dateRange, int $limit = 10): array
        {
            return OrderItem::select('product_id')
                ->selectRaw('SUM(quantity) as total_sold')
                ->selectRaw('SUM(total) as revenue')
                ->selectRaw('COUNT(DISTINCT order_id) as orders_count')
                ->whereHas('order', function ($query) use ($dateRange) {
                    $query->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                        ->whereNotIn('status', ['cancelled', 'refunded']);
                })
                ->with('product:id,name,sku,price')
                ->groupBy('product_id')
                ->orderByDesc('revenue')
                ->limit($limit)
                ->get()
                ->map(function ($item) {
                    return [
                        'product' => $item->product,
                        'total_sold' => (int) $item->total_sold,
                        'revenue' => (float) $item->revenue,
                        'orders_count' => (int) $item->orders_count,
                    ];
                })
                ->toArray();
        }

        private function getTopCategories(array $dateRange, int $limit = 5): array
        {
            return OrderItem::join('products', 'order_items.product_id', '=', 'products.id')
                ->join('categories', 'products.category_id', '=', 'categories.id')
                ->select('categories.id', 'categories.name')
                ->selectRaw('SUM(order_items.quantity) as total_sold')
                ->selectRaw('SUM(order_items.total) as revenue')
                ->whereHas('order', function ($query) use ($dateRange) {
                    $query->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                        ->whereNotIn('status', ['cancelled', 'refunded']);
                })
                ->groupBy('categories.id', 'categories.name')
                ->orderByDesc('revenue')
                ->limit($limit)
                ->get()
                ->toArray();
        }

        private function getCustomerMetrics(array $dateRange): array
        {
            $totalCustomers = User::count();
            $newCustomers = User::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])->count();
            $returningCustomers = Order::selectRaw('COUNT(DISTINCT user_id) as count')
                ->whereHas('user.orders', function ($query) use ($dateRange) {
                    $query->where('created_at', '<', $dateRange['start']);
                })
                ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                ->value('count') ?: 0;

            return [
                'total_customers' => $totalCustomers,
                'new_customers' => $newCustomers,
                'returning_customers' => $returningCustomers,
                'customer_retention_rate' => $totalCustomers > 0 ? ($returningCustomers / $totalCustomers) * 100 : 0,
            ];
        }

        private function getRecentOrders(int $limit = 10): array
        {
            return Order::with(['user:id,name,email', 'items.product:id,name'])
                ->latest()
                ->limit($limit)
                ->get()
                ->toArray();
        }

        private function calculateConversionRate(array $dateRange): float
        {
            // This would need proper tracking implementation
            // For now, return a placeholder calculation
            $visitors = 1000; // This should come from analytics tracking
            $orders = Order::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])->count();

            return $visitors > 0 ? ($orders / $visitors) * 100 : 0;
        }

        // Additional helper methods for detailed reports...
        private function getDetailedSalesData(array $dateRange): array
        {
            return [
                'gross_sales' => Order::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                    ->whereNotIn('status', ['cancelled', 'refunded'])
                    ->sum('subtotal'),
                'net_sales' => Order::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                    ->whereNotIn('status', ['cancelled', 'refunded'])
                    ->sum('total_amount'),
                'total_tax' => Order::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                    ->whereNotIn('status', ['cancelled', 'refunded'])
                    ->sum('tax_amount'),
                'total_shipping' => Order::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                    ->whereNotIn('status', ['cancelled', 'refunded'])
                    ->sum('shipping_amount'),
                'total_discounts' => Order::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                    ->whereNotIn('status', ['cancelled', 'refunded'])
                    ->sum('discount_amount'),
            ];
        }

        private function exportSalesReport(array $dateRange, string $format): \Symfony\Component\HttpFoundation\Response
        {
            $orders = Order::with(['user', 'items.product'])
                ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                ->get();

            // Implementation for CSV/Excel export
            // This would use a package like Laravel Excel
            return response()->json(['message' => 'Export functionality would be implemented here']);
        }
    }
