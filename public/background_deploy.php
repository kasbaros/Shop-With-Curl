<?php
    /**
     * Enhanced Background Deployment Script for cPanel
     * Improved version with admin seeding and comprehensive checks
     */

// Get deployment ID from command line argument
    $deployment_id = $argv[1] ?? uniqid('deploy_', true);

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

// Check if seeder files have changed
    function seeder_files_changed($current_commit, $new_commit)
    {
        global $config;
        chdir($config['repo_path']);

        try {
            $command = "git diff --name-only {$current_commit} {$new_commit} | grep -E '(Seeder|config/app)' || true";
            $output = execute_command($command, "Check for seeder changes", true);
            $changed_files = array_filter($output);

            if (!empty($changed_files)) {
                log_message("Seeder-related files changed: " . implode(', ', $changed_files));
                return true;
            }

            return false;
        } catch (Exception $e) {
            log_message("Could not check for seeder changes: " . $e->getMessage(), 'WARN');
            return true; // Assume changes if we can't check
        }
    }

// Force re-seed admin users
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

        // Check if seeder files changed
        $seeder_changed = seeder_files_changed($current_commit, $new_commit);

        // Sync files
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
        $protectedFiles = ['.htaccess', 'deploy.php', 'background_deploy.php', 'deployment.log', 'laravel_error.log', 'php_error_log', 'index.php'];
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

        execute_command("rsync -a --exclude=index.php {$publicSource}/ {$config['public_html_path']}/", "Copy public/ contents to public_html");

        // Install dependencies
        update_status('dependencies', 'Installing dependencies');
        chdir($config['laravel_path']);

        if (!file_exists($config['composer_phar'])) {
            log_message("Downloading Composer PHAR...");
            execute_command("curl -sS https://getcomposer.org/installer | {$config['php_path']}", "Download Composer");
        }

        $composerCommand = "COMPOSER_HOME=/home/shopwithcaug/.composer {$config['php_path']} {$config['composer_phar']} install --no-dev --optimize-autoloader --no-interaction";
        execute_command($composerCommand, "Install Composer dependencies");

        // Run Artisan commands
        update_status('artisan', 'Running Artisan commands');
        $artisanPath = $config['laravel_path'] . '/artisan';
        if (!file_exists($artisanPath)) {
            throw new Exception("Artisan file not found at $artisanPath");
        }

//        $artisanCommands = [
//            'migrate --force' => 'Run database migrations',
//            'config:clear' => 'Clear config cache',
//            'cache:clear' => 'Clear application cache',
//            'route:clear' => 'Clear route cache',
//            'view:clear' => 'Clear view cache',
//        ];

        // TEMPORARY FIX: Use fresh migration for broken database state
        $artisanCommands = [
            'migrate:fresh --seed --force' => 'Fresh migration with seeding (FIXING DATABASE CONFLICTS but will be removed in the future)',
            'config:clear' => 'Clear config cache',
            'cache:clear' => 'Clear application cache',
            'route:clear' => 'Clear route cache',
            'view:clear' => 'Clear view cache',
        ];

        foreach ($artisanCommands as $command => $description) {
            try {
                execute_command("{$config['php_path']} " . escapeshellarg($artisanPath) . " $command", $description);
            } catch (Exception $e) {
                log_message("Warning: $description failed: " . $e->getMessage(), 'WARN');
            }
        }

        // Handle admin user seeding
        update_status('seeding', 'Handling admin user seeding');

        if ($seeder_changed) {
            log_message("Seeder files changed, forcing admin user re-creation...");
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
            log_message("Admin users already exist and no seeder changes detected");
        }

        // Cache configuration for production
        try {
            execute_command("{$config['php_path']} " . escapeshellarg($artisanPath) . " config:cache", "Cache configuration");
        } catch (Exception $e) {
            log_message("Warning: Config caching failed: " . $e->getMessage(), 'WARN');
        }

        // Set permissions
        update_status('permissions', 'Setting file permissions');
        $permissionCommands = [
            "chmod 775 {$config['laravel_path']}/storage" => 'Set storage permissions',
            "chmod 775 {$config['laravel_path']}/bootstrap/cache" => 'Set bootstrap cache permissions',
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

        update_status('completed', 'Deployment completed successfully');
        log_message("Enhanced background deployment completed successfully!");

    } catch (Exception $e) {
        update_status('failed', $e->getMessage());
        log_message("Background deployment failed: " . $e->getMessage(), 'ERROR');
    } finally {
        release_lock();
    }
?>
