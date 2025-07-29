<!---->
<!--text/x-generic background_deploy.php ( PHP script, ASCII text )-->
<?php
///**
// * Background Deployment Script for cPanel
// * Place in /home/shopwithcaug/public_html/background_deploy.php
// * Copies public/ to both public_html and Laravel/public, with manifest verification
// */
//
//$config = [
//    'git_repo_url' => 'https://github.com/kasbaros/Shop-With-Curl.git',
//    'branch' => 'master',
//    'repo_path' => '/home/shopwithcaug/repositories/shopwithcarl',
//    'laravel_path' => '/home/shopwithcaug/Laravel',
//    'public_html_path' => '/home/shopwithcaug/public_html',
//    'php_path' => '/opt/alt/php84/usr/bin/php',
//    'composer_phar' => '/home/shopwithcaug/Laravel/composer.phar',
//    'node_path' => '/usr/local/bin/node',
//    'npm_path' => '/usr/local/bin/npm',
//];
//
//function log_message($message) {
//    $log_file = rtrim($config['public_html_path'], '/') . '/deployment.log';
//    $timestamp = date('Y-m-d H:i:s');
//    if (!is_writable($config['public_html_path']) || (file_exists($log_file) && !is_writable($log_file))) {
//        file_put_contents('php://stderr', "[$timestamp] Warning: Cannot write to $log_file\n");
//    } else {
//        file_put_contents($log_file, "[$timestamp] $message\n", FILE_APPEND | LOCK_EX);
//    }
//}
//
//try {
//    log_message("Starting background deployment process...");
//
//    // Verify directories
//    $required_dirs = [
//        $config['repo_path'] => 'Repository directory',
//        $config['laravel_path'] => 'Laravel directory',
//        $config['public_html_path' => 'Public HTML directory',
//    ];
//    foreach ($required_dirs as $dir => $desc) {
//        if (!is_dir($dir)) {
//            throw new Exception("$desc not found at $dir");
//        }
//        if (!is_writable($dir)) {
//            throw new Exception("$desc at $dir is not writable");
//        }
//        log_message("$desc exists and is writable: $dir");
//    }
//
//    // Create Laravel/public/ if it doesn't exist
//    $laravelPublicPath = $config['laravel_path'] . '/public';
//    if (!is_dir($laravelPublicPath)) {
//        mkdir($laravelPublicPath, 0755, true);
//        log_message("Created Laravel public directory: $laravelPublicPath");
//    }
//
//    // Change to repository directory
//    chdir($config['repo_path']);
//
//    // Verify .git directory
//    if (!is_dir($config['repo_path'] . '/.git')) {
//        throw new Exception("Git repository not found at {$config['repo_path']}/.git");
//    }
//
//    // Log current commit
//    exec("git log -1 --pretty=%H 2>&1", $commit_output, $commit_return);
//    log_message("Current commit: " . implode("\n", $commit_output));
//
//    // Pull latest changes
//    log_message("Pulling latest changes from Git...");
//    exec("git fetch origin 2>&1 && git reset --hard origin/{$config['branch']} 2>&1", $output, $return_code);
//    if ($return_code !== 0) {
//        throw new Exception("Git pull failed: " . implode("\n", $output));
//    }
//    log_message("Git pull successful: " . implode("\n", $output));
//
//    // Log new commit
//    exec("git log -1 --pretty=%H 2>&1", $commit_output, $commit_return);
//    log_message("New commit: " . implode("\n", $commit_output));
//
//    // Use pre-built assets
//    log_message("Node/NPM not found, using pre-built assets from repository");
//
//    // Clear existing Laravel directory contents (except .env and public/)
//    log_message("Clearing old files in {$config['laravel_path']}...");
//    $protectedFiles = ['.env', 'public'];
//    $laravelFiles = scandir($config['laravel_path']);
//    foreach ($laravelFiles as $file) {
//        if ($file !== '.' && $file !== '..' && !in_array($file, $protectedFiles)) {
//            $path = "{$config['laravel_path']}/$file";
//            if (is_dir($path)) {
//                exec("rm -rf $path 2>&1", $output, $return_code);
//            } else {
//                unlink($path);
//            }
//            if ($return_code !== 0) {
//                log_message("Warning: Failed to delete $path: " . implode("\n", $output));
//            } else {
//                log_message("Deleted $path");
//            }
//        }
//    }
//
//    // Copy Laravel files (excluding public/, .env, .git) to Laravel directory
//    log_message("Copying Laravel files to {$config['laravel_path']}...");
//    $excludeFiles = ['public', '.env', '.git', '.gitattributes', '.gitignore'];
//    exec("rsync -a --exclude=" . implode(' --exclude=', $excludeFiles) . " {$config['repo_path']}/ {$config['laravel_path']}/ 2>&1", $output, $return_code);
//    if ($return_code !== 0) {
//        throw new Exception("Failed to copy Laravel files: " . implode("\n", $output));
//    }
//    log_message("Copied Laravel files to {$config['laravel_path']}");
//
//    // Copy public/ to Laravel/public/
//    log_message("Copying public/ to {$config['laravel_path']}/public/...");
//    $publicSource = $config['repo_path'] . '/public';
//    if (!is_dir($publicSource)) {
//        throw new Exception("Public directory not found: $publicSource");
//    }
//    exec("rsync -a {$publicSource}/ {$config['laravel_path']}/public/ 2>&1", $output, $return_code);
//    if ($return_code !== 0) {
//        throw new Exception("Failed to copy public/ to {$config['laravel_path']}/public/: " . implode("\n", $output));
//    }
//    log_message("Copied public/ to {$config['laravel_path']}/public/");
//
//    // Copy public/ to public_html, excluding index.php
//    log_message("Copying public/ contents to {$config['public_html_path']}...");
//    $protectedFiles = ['.htaccess', 'deploy.php', 'background_deploy.php', 'deployment.log', 'laravel_error.log', 'php_error_log', 'index.php'];
//    $publicHtmlFiles = scandir($config['public_html_path']);
//    foreach ($publicHtmlFiles as $file) {
//        if ($file !== '.' && $file !== '..' && !in_array($file, $protectedFiles)) {
//            $path = "{$config['public_html_path']}/$file";
//            if (is_dir($path)) {
//                exec("rm -rf $path 2>&1", $output, $return_code);
//            } else {
//                unlink($path);
//            }
//        }
//    }
//    exec("rsync -a --exclude=index.php {$publicSource}/ {$config['public_html_path']}/ 2>&1", $output, $return_code);
//    if ($return_code !== 0) {
//        throw new Exception("Failed to copy public/ contents: " . implode("\n", $output));
//    }
//    log_message("Copied public/ contents to {$config['public_html_path']}");
//
//    // Verify copied files
//    $keyFiles = [
//        'artisan',
//        'app/Http/Kernel.php',
//        'resources/views/layouts/app.blade.php',
//        'resources/views/welcome.blade.php', // Adjust to your slider file, e.g., 'resources/views/components/slider.blade.php'
//        'public/build/manifest.json',
//    ];
//    foreach ($keyFiles as $file) {
//        $path = $config['laravel_path'] . '/' . $file;
//        if (file_exists($path)) {
//            $content = file_get_contents($path);
//            log_message("Verified $file: " . substr($content, 0, 500) . "...");
//        } else {
//            log_message("Error: $file not found at $path");
//        }
//    }
//
//    // Check for misplaced manifest.json in .vite/
//    $misplacedManifest = $config['public_html_path'] . '/build/.vite/manifest.json';
//    if (file_exists($misplacedManifest)) {
//        log_message("Warning: Found misplaced manifest.json in {$config['public_html_path']}/build/.vite/");
//        exec("mv {$misplacedManifest} {$config['public_html_path']}/build/manifest.json 2>&1", $output, $return_code);
//        if ($return_code === 0) {
//            log_message("Moved manifest.json to {$config['public_html_path']}/build/");
//        } else {
//            log_message("Warning: Failed to move manifest.json: " . implode("\n", $output));
//        }
//        exec("rm -rf {$config['public_html_path']}/build/.vite 2>&1", $output, $return_code);
//        if ($return_code === 0) {
//            log_message("Removed misplaced .vite directory");
//        } else {
//            log_message("Warning: Failed to remove .vite directory: " . implode("\n", $output));
//        }
//    }
//
//    // Verify assets in public_html
//    $assetPath = $config['public_html_path'] . '/build/assets';
//    if (is_dir($assetPath)) {
//        $assets = scandir($assetPath);
//        log_message("Assets in {$assetPath}: " . implode(", ", array_diff($assets, ['.', '..'])));
//        exec("ls -l $assetPath 2>&1", $perm_output, $perm_return);
//        log_message("Permissions in {$assetPath}: " . implode("\n", $perm_output));
//    } else {
//        log_message("Error: No assets found in {$assetPath}");
//    }
//
//    // Verify manifest in public_html
//    $manifestPath = $config['public_html_path'] . '/build/manifest.json';
//    if (file_exists($manifestPath)) {
//        $manifest = file_get_contents($manifestPath);
//        log_message("Manifest contents in public_html: " . $manifest);
//        chmod($manifestPath, 0644);
//    } else {
//        throw new Exception("Manifest file not found at $manifestPath");
//    }
//
//    // Verify manifest in Laravel/public
//    $laravelManifestPath = $config['laravel_path'] . '/public/build/manifest.json';
//    if (file_exists($laravelManifestPath)) {
//        $manifest = file_get_contents($laravelManifestPath);
//        log_message("Manifest contents in Laravel/public: " . $manifest);
//        chmod($laravelManifestPath, 0644);
//    } else {
//        throw new Exception("Manifest file not found at $laravelManifestPath");
//    }
//
//    // Install Composer dependencies
//    log_message("Installing Composer dependencies...");
//    chdir($config['laravel_path']);
//    if (!file_exists($config['composer_phar'])) {
//        log_message("Downloading Composer PHAR...");
//        exec("curl -sS https://getcomposer.org/installer | {$config['php_path']} 2>&1", $output, $return_code);
//        if ($return_code !== 0) {
//            throw new Exception("Failed to download Composer: " . implode("\n", $output));
//        }
//    }
//    $composerCommand = "COMPOSER_HOME=/home/shopwithcaug/.composer {$config['php_path']} {$config['composer_phar']} install --no-dev --optimize-autoloader --no-interaction --quiet 2>&1";
//    exec($composerCommand, $output, $return_code);
//    if ($return_code !== 0) {
//        throw new Exception("Composer install failed: " . implode("\n", $output));
//    }
//    log_message("Composer dependencies installed");
//
//    // Run Artisan commands
//    log_message("Running Artisan commands...");
//    $artisanPath = $config['laravel_path'] . '/artisan';
//    if (!file_exists($artisanPath)) {
//        throw new Exception("Artisan file not found at $artisanPath");
//    }
//    $artisanCommands = [
//        'migrate --force',
//        'config:clear',
//        'cache:clear',
//        'route:clear',
//        'view:clear',
//        'vite:clear',
//        'vendor:publish --tag=laravel-assets --force',
//        'horizon:install',
//        'filament:upgrade',
//        'filament:install --panels',
//        'config:clear',
//        'cache:clear',
//        'route:clear',
//        'view:clear',
//        'vite:clear',
//        'config:cache',
//    ];
//    foreach ($artisanCommands as $command) {
//        exec("{$config['php_path']} \"$artisanPath\" $command 2>&1", $cmd_output, $cmd_return);
//        if ($cmd_return !== 0) {
//            log_message("Warning: Command '{$config['php_path']} artisan $command' failed: " . implode("\n", $cmd_output));
//        } else {
//            log_message("Executed: php artisan $command");
//        }
//    }
//
//    // Clear all caches
//    log_message("Clearing all Laravel caches...");
//    $cachePaths = [
//        $config['laravel_path'] . '/storage/framework/views',
//        $config['laravel_path'] . '/storage/framework/cache',
//        $config['laravel_path'] . '/storage/framework/cache/data',
//        $config['laravel_path'] . '/bootstrap/cache',
//        $config['laravel_path'] . '/storage/framework/laravel-vite',
//    ];
//    foreach ($cachePaths as $cachePath) {
//        if (is_dir($cachePath)) {
//            exec("find {$cachePath} -type f -delete 2>&1", $output, $return_code);
//            if ($return_code !== 0) {
//                log_message("Warning: Failed to clear cache at {$cachePath}: " . implode("\n", $output));
//            } else {
//                log_message("Cleared cache at {$cachePath}");
//                exec("find {$cachePath} -type f 2>&1", $cache_output, $cache_return);
//                if (empty($cache_output)) {
//                    log_message("No cached files remain in {$cachePath}");
//                } else {
//                    log_message("Warning: Cached files still present in {$cachePath}: " . implode(", ", $cache_output));
//                }
//            }
//        } else {
//            log_message("Warning: Cache directory not found at {$cachePath}");
//        }
//    }
//
//    // Clear OPCache
//    log_message("Clearing OPCache...");
//    $opcacheCommand = "{$config['php_path']} -r \"if (function_exists('opcache_reset')) { opcache_reset(); echo 'OPCache cleared'; } else { echo 'OPCache not available'; }\" 2>&1";
//    exec($opcacheCommand, $output, $return_code);
//    if ($return_code !== 0 || strpos(implode("\n", $output), 'OPCache cleared') === false) {
//        log_message("Warning: Failed to clear OPCache: " . implode("\n", $output));
//    } else {
//        log_message("OPCache cleared successfully");
//    }
//
//    // Clear LiteSpeed cache
//    log_message("Attempting to clear LiteSpeed cache...");
//    $litespeedCachePath = '/home/shopwithcaug/.lscache';
//    if (is_dir($litespeedCachePath)) {
//        exec("rm -rf {$litespeedCachePath}/* 2>&1", $output, $return_code);
//        if ($return_code === 0) {
//            log_message("Cleared LiteSpeed cache at {$litespeedCachePath}");
//        } else {
//            log_message("Warning: Failed to clear LiteSpeed cache: " . implode("\n", $output));
//        }
//    } else {
//        log_message("LiteSpeed cache directory not found at {$litespeedCachePath}");
//    }
//
//    // Set permissions
//    log_message("Setting file permissions...");
//    chmod($config['laravel_path'] . '/storage', 0775);
//    chmod($config['laravel_path'] . '/bootstrap/cache', 0775);
//    chmod($config['laravel_path'] . '/public', 0755);
//    exec("find {$config['laravel_path']}/storage -type f -exec chmod 664 {} \;", $output, $return_code);
//    exec("find {$config['laravel_path']}/storage -type d -exec chmod 775 {} \;", $output, $return_code);
//    exec("find {$config['laravel_path']}/bootstrap/cache -type f -exec chmod 664 {} \;", $output, $return_code);
//    exec("find {$config['laravel_path']}/bootstrap/cache -type d -exec chmod 775 {} \;", $output, $return_code);
//    exec("find {$config['laravel_path']}/public -type f -exec chmod 664 {} \;", $output, $return_code);
//    exec("find {$config['laravel_path']}/public -type d -exec chmod 775 {} \;", $output, $return_code);
//    exec("find {$config['public_html_path']}/build -type f -exec chmod 664 {} \;", $output, $return_code);
//    exec("find {$config['public_html_path']}/build -type d -exec chmod 775 {} \;", $output, $return_code);
//    $log_file = rtrim($config['public_html_path'], '/') . '/deployment.log';
//    if (!file_exists($log_file)) {
//        touch($log_file);
//        chmod($log_file, 0664);
//    } elseif (!is_writable($log_file)) {
//        chmod($log_file, 0664);
//    }
//
//    log_message("Background deployment completed successfully!");
//
//} catch (Exception $e) {
//    log_message("Background deployment failed: " . $e->getMessage());
//}
//?>

<?php
    /**
     * Enhanced Background Deployment Script for cPanel
     * Improved version of your existing background_deploy.php
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
    function log_message($message, $level = 'INFO') {
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
    function update_status($status, $message = '') {
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
    function release_lock() {
        global $config;
        if (file_exists($config['lock_file'])) {
            @unlink($config['lock_file']);
        }
    }

    // Execute command with proper error handling
    function execute_command($command, $description) {
        log_message("Executing: $description");
        log_message("Command: $command");

        exec($command . ' 2>&1', $output, $return_code);

        if ($return_code !== 0) {
            $error_message = "Failed: $description (Exit code: $return_code)";
            log_message($error_message, 'ERROR');
            log_message("Output: " . implode("\n", $output), 'ERROR');
            throw new Exception($error_message);
        }

        log_message("Success: $description");
        if (!empty($output)) {
            log_message("Output: " . implode("\n", $output));
        }

        return $output;
    }

    // Main deployment process
    try {
        log_message("Starting background deployment process...");
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
        log_message("Current commit: " . implode("", $current_commit));

        // Pull latest changes
        log_message("Pulling latest changes from Git...");
        execute_command("git fetch origin", "Fetch from origin");
        execute_command("git reset --hard origin/{$config['branch']}", "Reset to origin/{$config['branch']}");

        // Log new commit
        $new_commit = execute_command("git rev-parse HEAD", "Get new commit");
        log_message("New commit: " . implode("", $new_commit));

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
        $rsyncExcludes = implode(' ', array_map(function($file) {
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

        $artisanCommands = [
            'migrate --force' => 'Run database migrations',
            'config:clear' => 'Clear config cache',
            'cache:clear' => 'Clear application cache',
            'route:clear' => 'Clear route cache',
            'view:clear' => 'Clear view cache',
            'config:cache' => 'Cache configuration',
        ];

        foreach ($artisanCommands as $command => $description) {
            try {
                execute_command("{$config['php_path']} " . escapeshellarg($artisanPath) . " $command", $description);
            } catch (Exception $e) {
                log_message("Warning: $description failed: " . $e->getMessage(), 'WARN');
            }
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
                execute_command($command, $description);
            } catch (Exception $e) {
                log_message("Warning: $description failed: " . $e->getMessage(), 'WARN');
            }
        }

        update_status('completed', 'Deployment completed successfully');
        log_message("Background deployment completed successfully!");

    } catch (Exception $e) {
        update_status('failed', $e->getMessage());
        log_message("Background deployment failed: " . $e->getMessage(), 'ERROR');
    } finally {
        release_lock();
    }
?>
