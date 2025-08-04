<?php

    namespace App\Providers;

    use Illuminate\Support\ServiceProvider;

    class SettingsServiceProvider extends ServiceProvider
    {
        public function register(): void
        {
            // Load the helpers file
            require_once app_path('Helpers/SettingsHelper.php');
        }

        public function boot(): void
        {
            //
        }
    }
