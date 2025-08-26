<?php

namespace App\Providers;

use App\Helpers\ImageStorageHelper;
use App\Helpers\StorageHelper;
use Blade;
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
        $this->app->singleton(\App\Services\CartService::class);
        $this->app->singleton(\App\Services\CompareService::class);
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

        // Register Blade directive for storage URLs
        Blade::directive('storage', function ($expression) {
            return "<?php echo " . StorageHelper::class . "::url($expression); ?>";
        });

//        Blade::directive('storageImage', function ($expression) {
//            $params = explode(',', $expression);
//            $path = trim($params[0]);
//            $width = isset($params[1]) ? trim($params[1]) : 'null';
//            $height = isset($params[2]) ? trim($params[2]) : 'null';
//
/*            return "<?php echo " . StorageHelper::class . "::imageUrl($path, $width, $height); ?>";*/
//        });

        // Replace the old storage directive with the new one
        Blade::directive('imageUrl', function ($expression) {
            return "<?php echo " . ImageStorageHelper::class . "::url($expression); ?>";
        });

        // Keep this simple for now - we can add optimization later
        Blade::directive('imageOptimized', function ($expression) {
            return "<?php echo " . ImageStorageHelper::class . "::url($expression); ?>";
        });
    }
}
