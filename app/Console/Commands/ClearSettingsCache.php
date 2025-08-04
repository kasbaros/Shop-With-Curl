<?php

    namespace App\Console\Commands;

    use Illuminate\Console\Command;
    use Illuminate\Support\Facades\Cache;

    class ClearSettingsCache extends Command
    {
        protected $signature = 'settings:clear-cache {--all : Clear all cache, not just settings}';
        protected $description = 'Clear settings cache for better performance';

        public function handle()
        {
            if ($this->option('all')) {
                Cache::flush();
                $this->info('All cache cleared successfully!');
            } else {
                clear_settings_cache();
                $this->info('Settings cache cleared successfully!');
            }

            return 0;
        }
    }
