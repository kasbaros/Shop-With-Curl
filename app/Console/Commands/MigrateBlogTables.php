<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class MigrateBlogTables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blog:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run migrations for blog tables';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Running blog-related migrations...');

        $migrations = [
            '2025_08_04_create_posts_table.php',
            '2025_08_04_create_tags_table.php',
            '2025_08_04_create_post_tags_table.php',
            '2025_08_04_create_post_categories_table.php',
        ];

        foreach ($migrations as $migration) {
            $this->info("Running migration: {$migration}");
            $path = database_path('migrations/' . $migration);

            if (file_exists($path)) {
                Artisan::call('migrate', [
                    '--path' => 'database/migrations/' . $migration,
                    '--force' => true
                ]);
                $this->info("Migration {$migration} completed.");
            } else {
                $this->error("Migration file {$migration} not found!");
            }
        }

        $this->info('All blog migrations completed!');
        $this->info('Now you can use the blog system.');
        $this->info('\nNote: If you see errors related to duplicate migrations, it means some migrations have already been run.');
        $this->info('In that case, you can use regular migrate command: php artisan migrate');
    }
}
