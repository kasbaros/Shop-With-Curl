<?php


    use App\Helpers\ImageStorageHelper;
    use App\Http\Controllers\Client\CheckoutController;
use App\Livewire\Client\Profile\AccountPage;
    use App\Livewire\Guest\CategoryDetail;
    use App\Livewire\Guest\CategoryGrid;
    use App\Livewire\Guest\ProductGrid;
    use App\Livewire\Guest\ShopGrid;
    use App\Livewire\Guest\ProductSearch;
    use Illuminate\Support\Facades\Route;

// Import admin controllers
    use App\Http\Controllers\Admin\{BannerController,
        DashboardController,
        GalleryController,
        ProductController as AdminProductController,
        CategoryController as AdminCategoryController,
        OrderController as AdminOrderController,
        UserController,
        CouponController,
        ReviewController,
        AnalyticsController,
        SettingsController
    };

    /*
    |--------------------------------------------------------------------------
    | Web Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register web routes for your application. These
    | routes are loaded by the RouteServiceProvider and all of them will
    | be assigned to the "web" middleware group. Make something great!
    |
    */



// ==================================================
// GUEST ROUTES (Public Access) - HYBRID APPROACH
// ==================================================

// Home & Static Pages (Keep Controllers - Complex logic)
    Route::get('/', [\App\Http\Controllers\Guest\HomeController::class, 'index'])->name('home');

// Categories (Direct Livewire - Interactive listings)
    Route::middleware('block.privileged')->group(function () {
        Route::get('/categories', CategoryGrid::class)->name('categories.index');
        Route::get('/category/{category:slug}', CategoryDetail::class)->name('categories.show');
    });

// Shop & Products (Hybrid Approach)
    Route::prefix('shop')->name('shop.')->middleware('block.privileged')->group(function () {
        // Direct Livewire - Interactive grid
        Route::get('/', ShopGrid::class)->name('index');

        // Direct Livewire - Search functionality
        Route::get('/search', ProductSearch::class)->name('search');

        // Keep Controller - Complex category logic if needed
        Route::get('/category/{category:slug}', [\App\Http\Controllers\Guest\ShopController::class, 'category'])->name('category');
    });

    Route::prefix('products')->name('products.')->middleware('block.privileged')->group(function () {
        // Direct Livewire - Interactive product grid
        Route::get('/', ProductGrid::class)->name('index');

        // Keep Controller - Complex product detail with relationships, reviews, etc.
        Route::get('/{product:slug}', [\App\Http\Controllers\Guest\ProductController::class, 'show'])->name('show');

        // Keep Controller - Complex category filtering logic
        Route::get('/category/{category:slug}', [\App\Http\Controllers\Guest\ProductController::class, 'category'])->name('category');
    });

// Blog routes - Temporarily disabled due to missing Livewire components
    // Route::prefix('blog')->name('blog.')->group(function () {
    //     Route::get('/', \App\Livewire\Guest\BlogIndex::class)->name('index');
    //     Route::get('/grid', \App\Livewire\Guest\BlogGrid::class)->name('grid');
    //     Route::get('/list', \App\Livewire\Guest\BlogList::class)->name('list');
    //     Route::get('/sidebar-left', \App\Livewire\Guest\BlogSidebarLeft::class)->name('sidebar-left');
    //     Route::get('/sidebar-right', \App\Livewire\Guest\BlogSidebarRight::class)->name('sidebar-right');
    //     Route::get('/{post:slug}', \App\Livewire\Guest\BlogShow::class)->name('show');
    // });

// Static Pages (Keep Controllers - Content processing, form handling)
    Route::prefix('pages')->name('pages.')->group(function () {
        Route::get('/about', [\App\Http\Controllers\Guest\PagesController::class, 'about'])->name('about');
        Route::get('/contact', [\App\Http\Controllers\Guest\PagesController::class, 'contact'])->name('contact');
        Route::post('/contact', [\App\Http\Controllers\Guest\PagesController::class, 'contactSubmit'])->name('contact.submit');
        Route::get('/faq', [\App\Http\Controllers\Guest\PagesController::class, 'faq'])->name('faq');
        Route::get('/privacy', [\App\Http\Controllers\Guest\PagesController::class, 'privacy'])->name('privacy');
        Route::get('/terms', [\App\Http\Controllers\Guest\PagesController::class, 'terms'])->name('terms');
    });

// Newsletter (Keep Controller - Email processing, validation, external APIs)
    Route::post('/newsletter/subscribe', [\App\Http\Controllers\Guest\NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');


// ==================================================
// CLIENT ROUTES (Authenticated) - HYBRID APPROACH
// ==================================================

    Route::middleware(['auth', 'client'])->group(function () {
        // Add general dashboard route for all authenticated users
        Route::get('/dashboard', function () {
            $user = auth()->user();

            if ($user->isAdmin() || $user->isDeveloper()) {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('account.dashboard');
        })->name('dashboard');

        // Profile & Account (Refactored to a single Livewire component)
        Route::get('/account/{section?}', AccountPage::class)
            ->name('account.page')
            ->whereIn('section', ['dashboard', 'orders', 'address', 'details', 'wishlist', 'cart']);

        // Backward-compatible alias for legacy references like route('account.dashboard')
        Route::get('/account', function () {
            return redirect()->route('account.page', ['section' => 'dashboard']);
        })->name('account.dashboard');

        // Backward-compatible alias for legacy profile edit route name
        Route::get('/account/profile/edit', function () {
            return redirect()->route('account.page', ['section' => 'details']);
        })->name('account.profile.edit');


        // Orders (Keep Controllers - Complex business logic, financial data)
        Route::prefix('my-orders')->name('orders.')->group(function () {
            Route::get('/', function() {
                return redirect()->route('account.page', ['section' => 'orders']);
            })->name('index');

            // Keep these detail and action routes
            Route::get('/{order}', [\App\Http\Controllers\Client\OrderController::class, 'show'])->name('show')
                ->where('order', '[0-9]+');
            Route::post('/{order}/cancel', [\App\Http\Controllers\Client\OrderController::class, 'cancel'])->name('cancel');
            Route::get('/{order}/invoice', [\App\Http\Controllers\Client\OrderController::class, 'invoice'])->name('invoice');
            Route::post('/{order}/review', [\App\Http\Controllers\Client\OrderController::class, 'review'])->name('review');
        });

        // Shopping Cart (Keep Controllers - Session management, calculations)
        Route::prefix('cart')->name('cart.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Client\CartController::class, 'index'])->name('index');
            Route::post('/add', [\App\Http\Controllers\Client\CartController::class, 'add'])->name('add');
            Route::patch('/{key}', [\App\Http\Controllers\Client\CartController::class, 'update'])->name('update');
            Route::delete('/{key}', [\App\Http\Controllers\Client\CartController::class, 'remove'])->name('remove');
            Route::delete('/', [\App\Http\Controllers\Client\CartController::class, 'clear'])->name('clear');
            Route::post('/coupon', [\App\Http\Controllers\Client\CartController::class, 'applyCoupon'])->name('coupon.apply');
            Route::delete('/coupon', [\App\Http\Controllers\Client\CartController::class, 'removeCoupon'])->name('coupon.remove');
        });

        // Checkout (Keep Controllers - Payment processing, validation)
        Route::prefix('checkout')->name('checkout.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Client\CheckoutController::class, 'index'])->name('index');
            Route::post('/', [\App\Http\Controllers\Client\CheckoutController::class, 'store'])->name('store');
            Route::get('/success/{order}', [CheckoutController::class, 'orderSuccess'])->name('success');
        });

        // Payment (Keep Controllers - Financial processing, security)
        Route::prefix('payment')->name('payment.')->group(function () {
            Route::get('/{order}/select', [\App\Http\Controllers\Shared\PaymentController::class, 'selectMethod'])->name('select');
            Route::post('/{order}/initiate', [\App\Http\Controllers\Shared\PaymentController::class, 'initiate'])->name('initiate');
            Route::get('/process/{payment}', [\App\Http\Controllers\Shared\PaymentController::class, 'process'])->name('process');
            Route::get('/verify/{payment}', [\App\Http\Controllers\Shared\PaymentController::class, 'verify'])->name('verify');
            Route::get('/success/{payment}', [\App\Http\Controllers\Shared\PaymentController::class, 'success'])->name('success');
            Route::get('/cancel/{payment}', [\App\Http\Controllers\Shared\PaymentController::class, 'cancel'])->name('cancel');
        });

        // Wishlist & Compare (Could be Livewire components for better UX)
        // Wishlist actions only (standalone wishlist view removed)
        Route::prefix('wishlist')->name('wishlist.')->group(function () {
            Route::post('/add', [\App\Http\Controllers\Client\CompareController::class, 'addToWishlist'])->name('add');
            Route::delete('/{product}', [\App\Http\Controllers\Client\CompareController::class, 'removeFromWishlist'])->name('remove');
        });

        // Backward compatibility: redirect legacy /wishlist page to account wishlist section
        Route::get('/wishlist', function() {
            return redirect()->route('account.page', ['section' => 'wishlist']);
        });

        Route::prefix('compare')->name('compare.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Client\CompareController::class, 'index'])->name('index');
            Route::post('/add', [\App\Http\Controllers\Client\CompareController::class, 'add'])->name('add');
            Route::delete('/{product}', [\App\Http\Controllers\Client\CompareController::class, 'remove'])->name('remove');
        });
        // Addresses (Legacy support for modal-based add/edit forms)
        Route::prefix('user')->name('user.')->group(function () {
            Route::get('/addresses', [\App\Http\Controllers\Client\ProfileController::class, 'addresses'])->name('addresses');
            Route::post('/addresses', [\App\Http\Controllers\Client\ProfileController::class, 'storeAddress'])->name('addresses.store');
            Route::patch('/addresses/{address}', [\App\Http\Controllers\Client\ProfileController::class, 'updateAddress'])->name('addresses.update');
            Route::delete('/addresses/{address}', [\App\Http\Controllers\Client\ProfileController::class, 'deleteAddress'])->name('addresses.delete');
        });

    });

// ==================================================
// ADMIN ROUTES (Admin Panel) - ALL IN ONE PLACE
// ==================================================

    Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Analytics & Reports
        Route::prefix('analytics')->name('analytics.')->group(function () {
            Route::get('/', [AnalyticsController::class, 'index'])->name('index');
            Route::get('/sales', [AnalyticsController::class, 'sales'])->name('sales');
            Route::get('/products', [AnalyticsController::class, 'products'])->name('products');
            Route::get('/customers', [AnalyticsController::class, 'customers'])->name('customers');
            Route::get('/export', [AnalyticsController::class, 'export'])->name('export');
        });

        // Banners Management
        Route::resource('banners', BannerController::class);
        Route::patch('banners/{banner}/toggle-status', [BannerController::class, 'toggleStatus'])
            ->name('banners.toggle-status');

        // Gallery Management
        Route::resource('gallery', App\Http\Controllers\Admin\GalleryController::class)->parameters([
            'gallery' => 'galleryItem'
        ]);
        Route::post('gallery/{galleryItem}/toggle-status', [App\Http\Controllers\Admin\GalleryController::class, 'toggleStatus'])->name('gallery.toggle-status');
        Route::post('gallery/{galleryItem}/toggle-featured', [App\Http\Controllers\Admin\GalleryController::class, 'toggleFeatured'])->name('gallery.toggle-featured');


        // Products Management
        Route::resource('products', AdminProductController::class);
        Route::patch('products/{product}/toggle-status', [AdminProductController::class, 'toggleStatus'])->name('products.toggle-status');
        Route::patch('products/{product}/toggle-featured', [AdminProductController::class, 'toggleFeatured'])->name('products.toggle-featured');
        Route::post('products/bulk-action', [AdminProductController::class, 'bulkAction'])->name('products.bulk-action');
        Route::post('products/{product}/duplicate', [AdminProductController::class, 'duplicate'])->name('products.duplicate');
        Route::post('products/import', [AdminProductController::class, 'import'])->name('products.import');
        Route::get('products/export', [AdminProductController::class, 'export'])->name('products.export');

        // Promo Banners Management
        Route::resource('promo-banners', App\Http\Controllers\Admin\PromoBannerController::class);
        Route::post('promo-banners/{promoBanner}/toggle-status', [App\Http\Controllers\Admin\PromoBannerController::class, 'toggleStatus'])->name('promo-banners.toggle-status');

        // Lookbooks Management
        Route::resource('lookbooks', App\Http\Controllers\Admin\LookBookController::class);
        Route::post('lookbooks/{lookbook}/toggle-status', [App\Http\Controllers\Admin\LookBookController::class, 'toggleStatus'])->name('lookbooks.toggle-status');

        // Categories Management
        Route::resource('categories', AdminCategoryController::class);
        Route::patch('categories/{category}/toggle-status', [AdminCategoryController::class, 'toggleStatus'])->name('categories.toggle-status');
        Route::post('categories/reorder', [AdminCategoryController::class, 'reorder'])->name('categories.reorder');

        // Orders Management
        // Minimal explicit routes to match admin.orders.* used in views
        Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::get('orders/{order}/edit', [AdminOrderController::class, 'edit'])->name('orders.edit');
        Route::match(['put','patch'],'orders/{order}', [AdminOrderController::class, 'update'])->name('orders.update');
        Route::delete('orders/{order}', [AdminOrderController::class, 'destroy'])->name('orders.destroy');
        Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');

        // Backward-compatible alias group for previous /admin/my-orders URLs
        Route::prefix('my-orders')->name('orders.')->group(function () {
            Route::get('/', [AdminOrderController::class, 'index'])->name('index');
        });


        // Users/Customers Management
        Route::resource('users', UserController::class);
        Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
        Route::post('users/bulk-action', [UserController::class, 'bulkAction'])->name('users.bulk-action');
        Route::post('users/{user}/impersonate', [UserController::class, 'impersonate'])->name('users.impersonate');
        Route::post('stop-impersonating', [UserController::class, 'stopImpersonating'])->name('stop-impersonating');

        // Coupons Management
        // Place custom coupon routes before resource to avoid route-model binding conflicts
        Route::get('coupons/generate-code', [CouponController::class, 'generateCode'])->name('coupons.generate-code');
        Route::post('coupons/bulk-action', [CouponController::class, 'bulkAction'])->name('coupons.bulk-action');
        Route::patch('coupons/{coupon}/toggle', [CouponController::class, 'toggle'])->name('coupons.toggle');
        Route::resource('coupons', CouponController::class);

        // Reviews Management
        Route::resource('reviews', ReviewController::class)->except(['create', 'store', 'edit', 'update']);
        Route::patch('reviews/{review}/approve', [ReviewController::class, 'approve'])->name('reviews.approve');
        Route::patch('reviews/{review}/reject', [ReviewController::class, 'reject'])->name('reviews.reject');
        Route::post('reviews/bulk-action', [ReviewController::class, 'bulkAction'])->name('reviews.bulk-action');
        Route::post('reviews/{review}/reply', [ReviewController::class, 'reply'])->name('reviews.reply');
        Route::get('reviews-analytics', [ReviewController::class, 'analytics'])->name('reviews.analytics');

        // Settings Routes (accessible by both admin and developer)
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [SettingsController::class, 'index'])->name('index');
            Route::get('/general', [SettingsController::class, 'general'])->name('general');
            Route::get('/store', [SettingsController::class, 'store'])->name('store');
            Route::get('/payment', [SettingsController::class, 'payment'])->name('payment');
            Route::get('/shipping', [SettingsController::class, 'shipping'])->name('shipping');
            Route::get('/email', [SettingsController::class, 'email'])->name('email');
            Route::get('/seo', [SettingsController::class, 'seo'])->name('seo');
            Route::get('/social', [SettingsController::class, 'social'])->name('social');
            Route::post('/update', [SettingsController::class, 'update'])->name('update');
            Route::post('/test-email', [SettingsController::class, 'testEmail'])->name('test-email');

            // Developer-only settings (add developer middleware to specific routes)
            Route::middleware('developer')->group(function () {
                Route::get('/security', [SettingsController::class, 'security'])->name('security');
                Route::get('/advanced', [SettingsController::class, 'advanced'])->name('advanced');
                Route::get('/integrations', [SettingsController::class, 'integrations'])->name('integrations');
                Route::get('/api', [SettingsController::class, 'api'])->name('api');
                Route::get('/maintenance', [SettingsController::class, 'maintenance'])->name('maintenance');
                Route::post('/generate-api-key', [SettingsController::class, 'generateApiKey'])->name('generate-api-key');
                Route::delete('/revoke-api-key/{keyId}', [SettingsController::class, 'revokeApiKey'])->name('revoke-api-key');
                Route::post('/clear-cache', [SettingsController::class, 'clearCache'])->name('clear-cache');
                Route::post('/backup', [SettingsController::class, 'backup'])->name('backup');
                Route::post('/maintenance-mode', [SettingsController::class, 'maintenanceMode'])->name('maintenance-mode');
                Route::get('/export', [SettingsController::class, 'export'])->name('export');
                Route::post('/import', [SettingsController::class, 'import'])->name('import');
            });
        });

        // Blog post management routes
        Route::resource('posts', \App\Http\Controllers\Admin\PostController::class);
    });

// ==================================================
// AUTH ROUTES (Laravel Breeze/Sanctum)
// ==================================================
    require __DIR__ . '/auth.php';
