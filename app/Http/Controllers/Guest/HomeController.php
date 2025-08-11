<?php

    namespace App\Http\Controllers\Guest;

    use App\Http\Controllers\Controller;
    use App\Models\Banner;
    use App\Models\Category;
    use App\Models\GalleryItem;
    use App\Models\Lookbook;
    use App\Models\Product;
    use App\Models\PromoBanner;
    use App\Traits\SharedLayoutData;

    class HomeController extends Controller
    {
        use SharedLayoutData;

        public function index()
        {
            $layoutData = $this->getLayoutData();

            // Get active banners for hero slider
            $banners = Banner::active()
                ->ordered()
                ->get();

            // Get gallery items for social proof
            $galleryItems = GalleryItem::active()
                ->with('product')
                ->ordered()
                ->limit(6)
                ->get();

            // Get featured products
            $featuredProducts = Product::active()
                ->featured()
                ->with(['categories', 'media'])
                ->limit(8)
                ->get();

            // Get latest products
            $latestProducts = Product::active()
                ->with(['categories', 'media'])
                ->latest()
                ->limit(8)
                ->get();

            // Get on sale products
            $onSaleProducts = Product::active()
                ->onSale()
                ->with(['categories', 'media'])
                ->limit(8)
                ->get();

            // Get main categories
            $categories = Category::active()
                ->parent()
                ->withCount('products')
                ->orderBy('sort_order')
                ->limit(10)
                ->get();

            // Get promo banner
            $promoBanner = PromoBanner::active()
                ->current()
                ->orderBy('priority')
                ->first();

            // Get lookbook
            $lookbook = Lookbook::active()
                ->with(['items.product.media'])
                ->current()
                ->first();

            // Modern trust indicators data
            $trustData = $this->getTrustIndicators();

            // Prepare view data
            $viewData = [
                'isAuthPage' => false,
                'cartCount' => $layoutData['cartCount'] ?? 0,
                'wishlistCount' => $layoutData['wishlistCount'] ?? 0,
                'languages' => $layoutData['languages'] ?? config('app.languages', ['English']),
                'selectedLanguage' => $layoutData['selectedLanguage'] ?? config('app.default_language', 'English'),
                'selectedCurrency' => $layoutData['selectedCurrency'] ?? config('currencies.default_currency', 'UGX'),
                'title' => 'Home'
            ];

            // Merge layout data with view data
            $layoutData = array_merge($layoutData, $viewData);

            return view('guest.home', compact(
                'banners',
                'galleryItems',
                'featuredProducts',
                'latestProducts',
                'onSaleProducts',
                'categories',
                'promoBanner',
                'lookbook',
                'trustData',
                'layoutData'
            ));
        }

        /**
         * Get trust indicators data
         */
        private function getTrustIndicators(): array
        {
            // These could be calculated from actual data or cached
            return [
                'total_customers' => $this->getTotalCustomers(),
                'products_sold' => $this->getProductsSold(),
                'average_rating' => $this->getAverageRating(),
                'satisfaction_rate' => $this->getSatisfactionRate(),
                'recent_purchases' => $this->getRecentPurchases(),
            ];
        }

        private function getTotalCustomers(): int
        {
            // Return actual count or a growing number based on your business
            return \App\Models\User::where('role', 'customer')->count() ?: 2847;
        }

        private function getProductsSold(): int
        {
            // Return actual count or a realistic number
            return \App\Models\OrderItem::sum('quantity') ?: 15230;
        }

        private function getAverageRating(): float
        {
            // Return actual average or a good rating
            return \App\Models\Review::avg('rating') ?: 4.8;
        }

        private function getSatisfactionRate(): int
        {
            // Return actual percentage or a high satisfaction rate
            return 98;
        }

        private function getRecentPurchases(): array
        {
            // Return recent purchases for live activity feed
            $recentOrders = \App\Models\Order::with(['user', 'items.product'])
                ->where('status', '!=', 'cancelled')
                ->where('created_at', '>=', now()->subHours(24))
                ->latest()
                ->limit(5)
                ->get();

            return $recentOrders->map(function ($order) {
                return [
                    'customer_name' => $this->maskCustomerName($order->user->name ?? 'Customer'),
                    'location' => $this->getLocationFromOrder($order),
                    'product' => $order->items->first()?->product->name ?? 'Product',
                    'time_ago' => $order->created_at->diffForHumans(),
                ];
            })->toArray();
        }

        private function maskCustomerName(string $name): string
        {
            $parts = explode(' ', $name);
            if (count($parts) >= 2) {
                return $parts[0] . ' ' . substr($parts[1], 0, 1) . '.';
            }
            return substr($name, 0, 1) . str_repeat('*', strlen($name) - 2) . substr($name, -1);
        }

        private function getLocationFromOrder($order): string
        {
            return $order->shipping_city ?? $order->billing_city ?? 'Uganda';
        }
    }
