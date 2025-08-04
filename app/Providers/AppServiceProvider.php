<?php

namespace App\Providers;

use DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;
use Log;

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

        if (config('app.env') !== 'production' || config('logging.log_slow_queries', false)) {
            DB::listen(function ($query) {
                if ($query->time > (config('logging.slow_query_threshold', 1000))) {
                    Log::channel('database_logs')->warning('Slow Query Detected', [
                        'sql' => $query->sql,
                        'bindings' => $query->bindings,
                        'time' => $query->time,
                        'connection' => $query->connectionName,
                    ]);
                }

                Log::channel('database_logs')->debug('Database Query', [
                    'sql' => $query->sql,
                    'bindings' => $query->bindings,
                    'time' => $query->time,
                    'connection' => $query->connectionName,
                ]);
            });
        }
    }
}
