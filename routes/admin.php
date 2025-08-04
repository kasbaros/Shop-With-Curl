<?php

    use App\Http\Controllers\Admin\{
        DashboardController,
        ProductController,
        CategoryController,
        OrderController,
        UserController,
        CouponController,
        ReviewController,
        AnalyticsController,
        SettingsController
    };

// Admin Routes Group
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {

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

        // Products Management
        Route::resource('products', ProductController::class);
        Route::patch('products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggle-status');
        Route::patch('products/{product}/toggle-featured', [ProductController::class, 'toggleFeatured'])->name('products.toggle-featured');
        Route::post('products/bulk-action', [ProductController::class, 'bulkAction'])->name('products.bulk-action');

        // Categories Management
        Route::resource('categories', CategoryController::class);
        Route::patch('categories/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('categories.toggle-status');

        // Orders Management
        Route::resource('orders', OrderController::class)->except(['create', 'store']);
        Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');

        // Users/Customers Management
        Route::resource('users', UserController::class);
        Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
        Route::post('users/bulk-action', [UserController::class, 'bulkAction'])->name('users.bulk-action');
        Route::post('users/{user}/impersonate', [UserController::class, 'impersonate'])->name('users.impersonate');
        Route::post('stop-impersonating', [UserController::class, 'stopImpersonating'])->name('stop-impersonating');

        // Coupons Management
        Route::resource('coupons', CouponController::class);
        Route::patch('coupons/{coupon}/toggle', [CouponController::class, 'toggle'])->name('coupons.toggle');
        Route::get('coupons/generate-code', [CouponController::class, 'generateCode'])->name('coupons.generate-code');
        Route::post('coupons/bulk-action', [CouponController::class, 'bulkAction'])->name('coupons.bulk-action');

        // Reviews Management
        Route::resource('reviews', ReviewController::class)->except(['create', 'store', 'edit', 'update']);
        Route::patch('reviews/{review}/approve', [ReviewController::class, 'approve'])->name('reviews.approve');
        Route::patch('reviews/{review}/reject', [ReviewController::class, 'reject'])->name('reviews.reject');
        Route::post('reviews/bulk-action', [ReviewController::class, 'bulkAction'])->name('reviews.bulk-action');
        Route::post('reviews/{review}/reply', [ReviewController::class, 'reply'])->name('reviews.reply');
        Route::get('reviews-analytics', [ReviewController::class, 'analytics'])->name('reviews.analytics');

        // Settings Routes
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [SettingsController::class, 'index'])->name('index');
            Route::get('/general', [SettingsController::class, 'general'])->name('general');
            Route::get('/store', [SettingsController::class, 'store'])->name('store');
            Route::get('/payment', [SettingsController::class, 'payment'])->name('payment');
            Route::get('/shipping', [SettingsController::class, 'shipping'])->name('shipping');
            Route::get('/email', [SettingsController::class, 'email'])->name('email');
            Route::get('/seo', [SettingsController::class, 'seo'])->name('seo');
            Route::get('/social', [SettingsController::class, 'social'])->name('social');
            Route::get('/security', [SettingsController::class, 'security'])->name('security');
            Route::get('/advanced', [SettingsController::class, 'advanced'])->name('advanced');
            Route::get('/integrations', [SettingsController::class, 'integrations'])->name('integrations');
            Route::get('/api', [SettingsController::class, 'api'])->name('api');
            Route::get('/maintenance', [SettingsController::class, 'maintenance'])->name('maintenance');

            // Settings Actions
            Route::post('/update', [SettingsController::class, 'update'])->name('update');
            Route::post('/test-email', [SettingsController::class, 'testEmail'])->name('test-email');
            Route::post('/generate-api-key', [SettingsController::class, 'generateApiKey'])->name('generate-api-key');
            Route::delete('/revoke-api-key/{keyId}', [SettingsController::class, 'revokeApiKey'])->name('revoke-api-key');
            Route::post('/clear-cache', [SettingsController::class, 'clearCache'])->name('clear-cache');
            Route::post('/backup', [SettingsController::class, 'backup'])->name('backup');
            Route::post('/maintenance-mode', [SettingsController::class, 'maintenanceMode'])->name('maintenance-mode');
            Route::get('/export', [SettingsController::class, 'export'])->name('export');
            Route::post('/import', [SettingsController::class, 'import'])->name('import');
        });
    });
