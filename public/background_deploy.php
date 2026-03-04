<?php
/**
 * Enhanced Background Deployment Script for cPanel
 * With improved forced fresh migration handling
 */

// Get deployment ID from command line argument
$deployment_id = $argv[1] ?? uniqid('deploy_', true);

// Add a force fresh migration flag
$force_fresh_migration = $argv[2] ?? false; // Pass 'true' as second argument to force fresh migration

$config = [
    'git_repo_url' => 'https://github.com/kasbaros/Shop-With-Curl.git',
    'branch' => 'master',
    'repo_path' => '/home/shopwithcaug/repositories/shopwithcarl',
    'laravel_path' => '/home/shopwithcaug/Laravel',
    'public_html_path' => '/home/shopwithcaug/public_html',
    'php_path' => '/opt/alt/php84/usr/bin/php',
    'composer_phar' => '/home/shopwithcaug/Laravel/composer.phar',
    'lock_file' => '/home/shopwithcaug/public_html/.deployment.lock',
    'status_file' => '/home/shopwithcaug/public_html/.deployment.status',
];

// Enhanced logging function
function log_message($message, $level = 'INFO')
{
    global $config, $deployment_id;
    $log_file = rtrim($config['public_html_path'], '/') . '/deployment.log';
    $deployment_log = rtrim($config['public_html_path'], '/') . "/deployment_{$deployment_id}.log";
    $timestamp = date('Y-m-d H:i:s');
    $formatted_message = "[$timestamp] [$level] [$deployment_id] $message";

    // Write to both general and deployment-specific logs
    foreach ([$log_file, $deployment_log] as $file) {
        if (is_writable(dirname($file))) {
            file_put_contents($file, $formatted_message . "\n", FILE_APPEND | LOCK_EX);
        }
    }

    if ($level === 'ERROR') {
        error_log($formatted_message);
    }
}

// Update deployment status
function update_status($status, $message = '')
{
    global $config, $deployment_id;
    $status_data = [
        'status' => $status,
        'message' => $message,
        'deployment_id' => $deployment_id,
        'timestamp' => time(),
        'human_time' => date('Y-m-d H:i:s')
    ];

    file_put_contents($config['status_file'], json_encode($status_data), LOCK_EX);
}

// Release deployment lock
function release_lock()
{
    global $config;
    if (file_exists($config['lock_file'])) {
        @unlink($config['lock_file']);
    }
}

// Execute command with proper error handling
function execute_command($command, $description, $allow_failure = false)
{
    log_message("Executing: $description");
    log_message("Command: $command");

    exec($command . ' 2>&1', $output, $return_code);

    if ($return_code !== 0) {
        $error_message = "Failed: $description (Exit code: $return_code)";
        log_message($error_message, $allow_failure ? 'WARN' : 'ERROR');
        log_message("Output: " . implode("\n", $output), $allow_failure ? 'WARN' : 'ERROR');

        if (!$allow_failure) {
            throw new Exception($error_message);
        }
    } else {
        log_message("Success: $description");
        if (!empty($output)) {
            log_message("Output: " . implode("\n", $output));
        }
    }

    return $output;
}

// Check if database needs fresh migration
function needs_fresh_migration()
{
    global $config;
    $artisanPath = $config['laravel_path'] . '/artisan';

    try {
        // Check if migrations table exists and get migration count
        $checkCommand = "{$config['php_path']} " . escapeshellarg($artisanPath) . " tinker --execute=\"
            try {
                \$count = DB::table('migrations')->count();
                echo 'migrations_exist:' . \$count;
            } catch (Exception \$e) {
                echo 'no_migrations_table';
            }
        \"";

        $output = execute_command($checkCommand, "Check database migration status", true);
        $result = trim(implode('', $output));

        if (strpos($result, 'no_migrations_table') !== false) {
            log_message("No migrations table found - fresh migration needed");
            return true;
        }

        if (strpos($result, 'migrations_exist:0') !== false) {
            log_message("Migrations table exists but no migrations found - fresh migration needed");
            return true;
        }

        // Check for database integrity issues
        $integrityCommand = "{$config['php_path']} " . escapeshellarg($artisanPath) . " tinker --execute=\"
            try {
                \$userCount = App\\Models\\User::count();
                echo 'user_count:' . \$userCount;
            } catch (Exception \$e) {
                echo 'database_error:' . \$e->getMessage();
            }
        \"";

        $integrityOutput = execute_command($integrityCommand, "Check database integrity", true);
        $integrityResult = trim(implode('', $integrityOutput));

        if (strpos($integrityResult, 'database_error:') !== false) {
            log_message("Database integrity issues detected - fresh migration needed");
            return true;
        }

        return false;
    } catch (Exception $e) {
        log_message("Could not check migration status: " . $e->getMessage(), 'WARN');
        return true; // Assume fresh migration needed if we can't check
    }
}

// Perform fresh migration with comprehensive backup
function perform_fresh_migration()
{
    global $config;
    $artisanPath = $config['laravel_path'] . '/artisan';

    try {
        log_message("Starting fresh migration process...");

        // Create database backup before fresh migration
        $backupFile = $config['laravel_path'] . '/storage/backup_' . date('Y_m_d_H_i_s') . '.sql';
        try {
            $backupCommand = "{$config['php_path']} " . escapeshellarg($artisanPath) . " tinker --execute=\"
                \$connection = config('database.default');
                \$database = config('database.connections.'.\$connection.'.database');
                echo 'database:' . \$database;
            \"";

            $dbOutput = execute_command($backupCommand, "Get database name", true);
            $dbResult = trim(implode('', $dbOutput));

            if (strpos($dbResult, 'database:') !== false) {
                $dbName = str_replace('database:', '', $dbResult);
                log_message("Attempting to backup database: $dbName");

                // Note: This backup command might need adjustment based on your hosting environment
                execute_command(
                    "mysqldump --single-transaction --routines --triggers $dbName > " . escapeshellarg($backupFile),
                    "Backup database before fresh migration",
                    true // Allow failure as some hosts don't allow direct mysqldump
                );
            }
        } catch (Exception $e) {
            log_message("Database backup failed (continuing anyway): " . $e->getMessage(), 'WARN');
        }

        // Clear all caches before migration
        $clearCommands = [
            'config:clear' => 'Clear config cache',
            'cache:clear' => 'Clear application cache',
            'route:clear' => 'Clear route cache',
            'view:clear' => 'Clear view cache',
        ];

        foreach ($clearCommands as $command => $description) {
            try {
                execute_command("{$config['php_path']} " . escapeshellarg($artisanPath) . " $command", $description, true);
            } catch (Exception $e) {
                log_message("Warning: $description failed: " . $e->getMessage(), 'WARN');
            }
        }

        // Perform fresh migration with seeding
        log_message("Running migrate:fresh with seeding...");
        execute_command(
            "{$config['php_path']} " . escapeshellarg($artisanPath) . " migrate:fresh --seed --force",
            "Fresh migration with seeding"
        );

        // Verify migration success
        $verifyCommand = "{$config['php_path']} " . escapeshellarg($artisanPath) . " tinker --execute=\"
            \$migrationCount = DB::table('migrations')->count();
            \$userCount = App\\Models\\User::count();
            \$adminCount = App\\Models\\User::where('is_admin', true)->count();
            echo 'migrations:' . \$migrationCount . '|users:' . \$userCount . '|admins:' . \$adminCount;
        \"";

        $verifyOutput = execute_command($verifyCommand, "Verify migration success", true);
        $verifyResult = trim(implode('', $verifyOutput));
        log_message("Migration verification: $verifyResult");

        return true;
    } catch (Exception $e) {
        log_message("Fresh migration failed: " . $e->getMessage(), 'ERROR');
        return false;
    }
}

// Check if seeder files have changed
function seeder_files_changed($current_commit, $new_commit)
{
    global $config;
    chdir($config['repo_path']);

    try {
        $command = "git diff --name-only {$current_commit} {$new_commit} | grep -E '(Seeder|Migration|config/app|config/database)' || true";
        $output = execute_command($command, "Check for seeder/migration changes", true);
        $changed_files = array_filter($output);

        if (!empty($changed_files)) {
            log_message("Database-related files changed: " . implode(', ', $changed_files));
            return true;
        }

        return false;
    } catch (Exception $e) {
        log_message("Could not check for seeder changes: " . $e->getMessage(), 'WARN');
        return true; // Assume changes if we can't check
    }
}

// Force re-seed admin users (keeping your existing function)
function force_reseed_admin_users()
{
    global $config;
    $artisanPath = $config['laravel_path'] . '/artisan';

    try {
        // Delete existing admin users that might have incorrect password hashing
        log_message("Removing existing admin users to force re-creation...");
        $deleteCommand = "{$config['php_path']} " . escapeshellarg($artisanPath) . " tinker --execute=\"App\\Models\\User::where('is_admin', true)->delete();\"";
        execute_command($deleteCommand, "Delete existing admin users", true);

        // Run the admin seeder
        log_message("Running AdminUserSeeder to create fresh admin users...");
        execute_command(
            "{$config['php_path']} " . escapeshellarg($artisanPath) . " db:seed --class=AdminUserSeeder --force",
            "Force seed admin users"
        );

        log_message("Admin users re-seeded successfully");
        return true;
    } catch (Exception $e) {
        log_message("Error during admin user re-seeding: " . $e->getMessage(), 'ERROR');
        return false;
    }
}

// Check if admin users exist
function check_admin_users_exist()
{
    global $config;
    $artisanPath = $config['laravel_path'] . '/artisan';
    $command = "{$config['php_path']} " . escapeshellarg($artisanPath) . " tinker --execute=\"echo App\\Models\\User::where('is_admin', true)->count();\"";

    try {
        $output = execute_command($command, "Check admin users count", true);
        $count = intval(trim(implode('', $output)));
        log_message("Found $count admin users in database");
        return $count > 0;
    } catch (Exception $e) {
        log_message("Could not check admin users: " . $e->getMessage(), 'WARN');
        return false;
    }
}

// Main deployment process
try {
    log_message("Starting enhanced background deployment process...");
    log_message("Force fresh migration flag: " . ($force_fresh_migration ? 'true' : 'false'));
    update_status('running', 'Deployment in progress');

    // Verify directories
    $required_dirs = [
        $config['repo_path'] => 'Repository directory',
        $config['laravel_path'] => 'Laravel directory',
        $config['public_html_path'] => 'Public HTML directory',
    ];

    foreach ($required_dirs as $dir => $desc) {
        if (!is_dir($dir)) {
            throw new Exception("$desc not found at $dir");
        }
        if (!is_writable($dir)) {
            throw new Exception("$desc at $dir is not writable");
        }
        log_message("$desc exists and is writable: $dir");
    }

    // Create Laravel/public/ if it doesn't exist
    $laravelPublicPath = $config['laravel_path'] . '/public';
    if (!is_dir($laravelPublicPath)) {
        if (!mkdir($laravelPublicPath, 0755, true) && !is_dir($laravelPublicPath)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $laravelPublicPath));
        }
        log_message("Created Laravel public directory: $laravelPublicPath");
    }

    // Update Git repository
    update_status('git_update', 'Updating Git repository');
    chdir($config['repo_path']);

    // Verify .git directory
    if (!is_dir($config['repo_path'] . '/.git')) {
        throw new Exception("Git repository not found at {$config['repo_path']}/.git");
    }

    // Log current commit
    $current_commit = execute_command("git rev-parse HEAD", "Get current commit");
    $current_commit = trim(implode("", $current_commit));
    log_message("Current commit: $current_commit");

    // Pull latest changes
    log_message("Pulling latest changes from Git...");
    execute_command("git fetch origin", "Fetch from origin");
    execute_command("git reset --hard origin/{$config['branch']}", "Reset to origin/{$config['branch']}");

    // Log new commit
    $new_commit = execute_command("git rev-parse HEAD", "Get new commit");
    $new_commit = trim(implode("", $new_commit));
    log_message("New commit: $new_commit");

    // Check if database-related files changed
    $database_files_changed = seeder_files_changed($current_commit, $new_commit);

    // Sync files (keeping your existing file sync logic)
    update_status('file_sync', 'Syncing application files');

    // Clear existing Laravel directory contents (except .env and public/)
    log_message("Clearing old files in {$config['laravel_path']}...");
    $protectedFiles = ['.env', 'public'];
    $laravelFiles = scandir($config['laravel_path']);
    foreach ($laravelFiles as $file) {
        if ($file !== '.' && $file !== '..' && !in_array($file, $protectedFiles)) {
            $path = "{$config['laravel_path']}/$file";
            if (is_dir($path)) {
                execute_command("rm -rf " . escapeshellarg($path), "Remove directory $file");
            } else {
                if (unlink($path)) {
                    log_message("Deleted $path");
                }
            }
        }
    }

    // Copy Laravel files (excluding public/, .env, .git) to Laravel directory
    log_message("Copying Laravel files to {$config['laravel_path']}...");
    $excludeFiles = ['public', '.env', '.git', '.gitattributes', '.gitignore'];
    $rsyncExcludes = implode(' ', array_map(function ($file) {
        return '--exclude=' . escapeshellarg($file);
    }, $excludeFiles));

    execute_command(
        "rsync -a {$rsyncExcludes} {$config['repo_path']}/ {$config['laravel_path']}/",
        "Copy Laravel files"
    );

    // Copy public/ to Laravel/public/
    log_message("Copying public/ to {$config['laravel_path']}/public/...");
    $publicSource = $config['repo_path'] . '/public';
    if (!is_dir($publicSource)) {
        throw new Exception("Public directory not found: $publicSource");
    }

    execute_command("rsync -a {$publicSource}/ {$config['laravel_path']}/public/", "Copy public/ to Laravel/public/");

    // Copy public/ to public_html, excluding index.php and protected files
    log_message("Copying public/ contents to {$config['public_html_path']}...");
//    $protectedFiles = ['.htaccess', 'deploy.php', 'background_deploy.php', 'deployment.log', 'laravel_error.log', 'php_error_log', 'index.php', 'storage'];
//    $publicHtmlFiles = scandir($config['public_html_path']);
//    foreach ($publicHtmlFiles as $file) {
//        if ($file !== '.' && $file !== '..' && !in_array($file, $protectedFiles) && !preg_match('/^deployment_.*\.log$/', $file)) {
//            $path = "{$config['public_html_path']}/$file";
//            if (is_dir($path)) {
//                execute_command("rm -rf " . escapeshellarg($path), "Remove directory $file");
//            } else {
//                if (unlink($path)) {
//                    log_message("Removed file: $file");
//                }
//            }
//        }
//    }
//
//    execute_command("rsync -a --exclude=index.php {$publicSource}/ {$config['public_html_path']}/", "Copy public/ contents to public_html");


    // IMPORTANT: Add storage to protected files AND exclude from rsync
$protectedFiles = [
    '.htaccess',
    'deploy.php',
    'background_deploy.php',
    'deployment.log',
    'laravel_error.log',
    'php_error_log',
    'index.php',
    'storage'  // This protects existing storage folder
];

// Clean up public_html but preserve protected files
$publicHtmlFiles = scandir($config['public_html_path']);
foreach ($publicHtmlFiles as $file) {
    if ($file !== '.' && $file !== '..' && !in_array($file, $protectedFiles) && !preg_match('/^deployment_.*\.log$/', $file)) {
        $path = "{$config['public_html_path']}/$file";
        if (is_dir($path)) {
            execute_command("rm -rf " . escapeshellarg($path), "Remove directory $file");
        } else {
            if (unlink($path)) {
                log_message("Removed file: $file");
            }
        }
    }
}

// Build rsync exclude parameters for protected files
$rsyncExcludes = implode(' ', array_map(function ($file) {
    return '--exclude=' . escapeshellarg($file);
}, $protectedFiles));

// Use rsync with proper exclusions to avoid overwriting storage
execute_command(
    "rsync -a {$rsyncExcludes} {$publicSource}/ {$config['public_html_path']}/",
    "Copy public/ contents to public_html"
);

// ADDITIONAL SAFETY: Ensure storage folder exists and has correct structure
$storagePublicPath = $config['public_html_path'] . '/storage';
if (!is_dir($storagePublicPath)) {
    log_message("Creating storage directory in public_html...");
    if (!mkdir($storagePublicPath, 0755, true)) {
        log_message("Warning: Could not create storage directory", 'WARN');
    }
}

// Ensure storage subdirectories exist
$storageSubdirs = ['app', 'app/public'];
foreach ($storageSubdirs as $subdir) {
    $fullPath = $storagePublicPath . '/' . $subdir;
    if (!is_dir($fullPath)) {
        if (!mkdir($fullPath, 0755, true)) {
            log_message("Warning: Could not create storage subdirectory: $subdir", 'WARN');
        } else {
            log_message("Created storage subdirectory: $subdir");
        }
    }
}

// Create symbolic link if it doesn't exist (Laravel's storage:link equivalent)
$storageLinkPath = $config['public_html_path'] . '/storage';
$laravelStoragePath = $config['laravel_path'] . '/storage/app/public';

if (!is_link($storageLinkPath) && is_dir($laravelStoragePath)) {
    log_message("Creating storage symbolic link...");
    try {
        // Remove directory if it exists and create symlink
        if (is_dir($storageLinkPath) && !is_link($storageLinkPath)) {
            execute_command("rm -rf " . escapeshellarg($storageLinkPath), "Remove storage directory", true);
        }

        if (symlink($laravelStoragePath, $storageLinkPath)) {
            log_message("Storage symbolic link created successfully");
        } else {
            log_message("Warning: Could not create storage symbolic link", 'WARN');
        }
    } catch (Exception $e) {
        log_message("Warning: Storage link creation failed: " . $e->getMessage(), 'WARN');
    }
}


    // Install dependencies
    update_status('dependencies', 'Installing dependencies');
    chdir($config['laravel_path']);

    if (!file_exists($config['composer_phar'])) {
        log_message("Downloading Composer PHAR...");
        execute_command("curl -sS https://getcomposer.org/installer | {$config['php_path']}", "Download Composer");
    }

    $composerCommand = "COMPOSER_HOME=/home/shopwithcaug/.composer {$config['php_path']} {$config['composer_phar']} install --no-dev --optimize-autoloader --no-interaction";
    execute_command($composerCommand, "Install Composer dependencies");

    // ENHANCED MIGRATION LOGIC
    update_status('migration', 'Handling database migrations');
    $artisanPath = $config['laravel_path'] . '/artisan';
    if (!file_exists($artisanPath)) {
        throw new Exception("Artisan file not found at $artisanPath");
    }

    // Determine if we need fresh migration
    $needsFreshMigration = $force_fresh_migration || $database_files_changed || needs_fresh_migration();

    if ($needsFreshMigration) {
        log_message("Performing fresh migration...");
        if (!perform_fresh_migration()) {
            throw new Exception("Fresh migration failed");
        }
    } else {
        log_message("Running regular migrations...");
        try {
            execute_command("{$config['php_path']} " . escapeshellarg($artisanPath) . " migrate --force", "Run regular migrations");
        } catch (Exception $e) {
            log_message("Regular migration failed, attempting fresh migration...", 'WARN');
            if (!perform_fresh_migration()) {
                throw new Exception("Both regular and fresh migrations failed");
            }
        }
    }

    // =========================================================
    // STEP 1: NUKE ALL CACHES (clear everything before rebuild)
    // =========================================================
    update_status('cache_clear', 'Clearing all caches');
    log_message("--- CLEARING ALL CACHES ---");

    $clearCommands = [
        'config:clear'  => 'Clear config cache',
        'route:clear'   => 'Clear route cache',
        'view:clear'    => 'Clear compiled view cache',
        'cache:clear'   => 'Clear application cache',
        'event:clear'   => 'Clear cached events',
    ];

    foreach ($clearCommands as $command => $description) {
        try {
            execute_command("{$config['php_path']} " . escapeshellarg($artisanPath) . " $command", $description);
        } catch (Exception $e) {
            log_message("Warning: $description failed: " . $e->getMessage(), 'WARN');
        }
    }

    // Force-delete compiled views directory contents (belt and suspenders)
    $compiledViewsPath = $config['laravel_path'] . '/storage/framework/views';
    if (is_dir($compiledViewsPath)) {
        log_message("Force-purging compiled views directory: $compiledViewsPath");
        execute_command(
            "find " . escapeshellarg($compiledViewsPath) . " -type f -name '*.php' -delete",
            "Delete all compiled Blade views",
            true
        );
    }

    // =========================================================
    // STEP 2: HANDLE ADMIN SEEDING (if not already done by fresh migration)
    // =========================================================
    if (!$needsFreshMigration) {
        update_status('seeding', 'Handling admin user seeding');

        if ($database_files_changed) {
            log_message("Database files changed, forcing admin user re-creation...");
            force_reseed_admin_users();
        } else if (!check_admin_users_exist()) {
            log_message("No admin users found, running admin seeder...");
            try {
                execute_command(
                    "{$config['php_path']} " . escapeshellarg($artisanPath) . " db:seed --class=AdminUserSeeder --force",
                    "Seed admin users"
                );
                log_message("Admin users seeded successfully");
            } catch (Exception $e) {
                log_message("Warning: Admin seeding failed: " . $e->getMessage(), 'WARN');
            }
        } else {
            log_message("Admin users already exist and no database changes detected");
        }
    }

    // =========================================================
    // STEP 3: SET PERMISSIONS (before caching, so cache files are writable)
    // =========================================================
    update_status('permissions', 'Setting file permissions');
    $permissionCommands = [
        "chmod -R 775 {$config['laravel_path']}/storage" => 'Set storage permissions',
        "chmod -R 775 {$config['laravel_path']}/bootstrap/cache" => 'Set bootstrap cache permissions',
        "find {$config['laravel_path']}/storage -type f -exec chmod 664 {} \\;" => 'Set storage file permissions',
        "find {$config['laravel_path']}/storage -type d -exec chmod 775 {} \\;" => 'Set storage directory permissions',
        "find {$config['public_html_path']}/build -type f -exec chmod 664 {} \\;" => 'Set build file permissions',
    ];

    foreach ($permissionCommands as $command => $description) {
        try {
            execute_command($command, $description, true);
        } catch (Exception $e) {
            log_message("Warning: $description failed: " . $e->getMessage(), 'WARN');
        }
    }

    // =========================================================
    // STEP 4: REBUILD PRODUCTION CACHES (correct order matters)
    // =========================================================
    update_status('cache_rebuild', 'Rebuilding production caches');
    log_message("--- REBUILDING PRODUCTION CACHES ---");

    // Order: config first (other caches depend on it), then routes, then views
    $buildCommands = [
        'config:cache' => 'Cache configuration',
        'route:cache'  => 'Cache routes',
        'view:cache'   => 'Compile and cache all Blade views',
    ];

    foreach ($buildCommands as $command => $description) {
        try {
            execute_command("{$config['php_path']} " . escapeshellarg($artisanPath) . " $command", $description);
        } catch (Exception $e) {
            log_message("Warning: $description failed: " . $e->getMessage(), 'WARN');
        }
    }

    // =========================================================
    // STEP 5: VERIFY DEPLOYMENT
    // =========================================================
    update_status('verification', 'Verifying deployment');

    // Check that Vite manifest exists in public_html
    $manifestPath = $config['public_html_path'] . '/build/manifest.json';
    if (file_exists($manifestPath)) {
        $manifest = json_decode(file_get_contents($manifestPath), true);
        $assetCount = $manifest ? count($manifest) : 0;
        log_message("Vite manifest OK: $assetCount assets registered");

        // Verify that the actual asset files referenced in manifest exist
        $missingAssets = [];
        foreach ($manifest as $key => $entry) {
            $file = $entry['file'] ?? $entry;
            $assetPath = $config['public_html_path'] . '/build/' . $file;
            if (!file_exists($assetPath)) {
                $missingAssets[] = $file;
            }
        }
        if (!empty($missingAssets)) {
            log_message("WARNING: Missing build assets: " . implode(', ', $missingAssets), 'WARN');
        } else {
            log_message("All build assets verified present");
        }
    } else {
        log_message("WARNING: Vite manifest not found at $manifestPath", 'WARN');
    }

    // Verify compiled views exist
    $compiledViews = glob($compiledViewsPath . '/*.php');
    $viewCount = $compiledViews ? count($compiledViews) : 0;
    log_message("Compiled views: $viewCount files cached");

    update_status('completed', 'Deployment completed successfully');
    log_message("Enhanced background deployment completed successfully!");

} catch (Exception $e) {
    update_status('failed', $e->getMessage());
    log_message("Background deployment failed: " . $e->getMessage(), 'ERROR');
} finally {
    release_lock();
}
?>
