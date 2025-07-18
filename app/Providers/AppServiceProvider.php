<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // DB settings
        Schema::defaultStringLength(191);

        // Use Bootstrap 5 for pagination
        Paginator::useBootstrapFive();

        // Share cart count with all views
        View::composer('*', function ($view) {
            $cartCount = 0;
            if (session()->has('cart')) {
                $cartCount = collect(session('cart'))->sum('quantity');
            }
            $view->with('cartCount', $cartCount);
        });
    }
}
