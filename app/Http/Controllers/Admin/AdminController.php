<?php

    namespace App\Http\Controllers\Admin;

    use App\Http\Controllers\Controller;
    use App\Models\User;
    use App\Models\Product;
    use App\Models\Order;
    use App\Models\Category;
    use App\Models\Coupon;
    use App\Models\Review;

    abstract class AdminController extends Controller
    {
        public function __construct()
        {
            $this->middleware(['auth', 'admin']);
        }

        /**
         * Get common data for admin views
         */
        protected function getAdminViewData(): array
        {
            return [
                'isDeveloper' => auth()->user()?->isDeveloper() ?? false,
                'isAdmin' => auth()->user()?->isAdmin() ?? false,
                'currentUser' => auth()->user(),
                // Sidebar counters
                'pendingOrders' => Order::where('status', 'pending')->count(),
                'pendingProducts' => Product::where('status', 'draft')->count(),
                'unverifiedUsers' => User::whereNull('email_verified_at')->count(),
                'pendingReviews' => Review::where('is_approved', false)->count(),
                'activeCoupons' => Coupon::where('is_active', true)
                    ->where('starts_at', '<=', now())
                    ->where('expires_at', '>=', now())
                    ->count(),
            ];
        }

        /**
         * Require developer role
         */
        protected function requireDeveloper(): void
        {
            if (!auth()->user()?->isDeveloper()) {
                abort(403, 'Developer access required');
            }
        }

        /**
         * Require admin or developer role
         */
        protected function requireAdminOrDeveloper(): void
        {
            $user = auth()->user();
            if (!$user || (!$user->isAdmin() && !$user->isDeveloper())) {
                abort(403, 'Admin access required');
            }
        }
    }
