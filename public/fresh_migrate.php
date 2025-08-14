<?php
    /**
     * Fresh Migration Script for cPanel - SECURE VERSION
     * Runs migrate:fresh with seeding and creates a database backup
     * DELETE THIS SCRIPT AFTER USE!
     */

    error_reporting(E_ALL);
    ini_set('display_errors', 0);

// Enhanced configuration with validation
    $config = [
        'laravel_path' => '/home/shopwithcaug/Laravel',
        'php_path' => '/opt/alt/php84/usr/bin/php',
        'backup_dir' => '/home/shopwithcaug/Laravel/storage/backups',
        'log_file' => '/home/shopwithcaug/public_html/fresh_migration.log',
        'lock_file' => '/home/shopwithcaug/Laravel/storage/framework/.fresh_migrate.lock',
        'max_execution_time' => 300, // 5 minutes
        'max_log_size' => 5 * 1024 * 1024, // 5MB
    ];

// Enhanced security configuration
    $security = [
        'require_https' => true,
        'require_post' => true,
        'require_confirm' => true,
        'ip_allowlist' => [], // Add your IPs: ['203.0.113.10', '192.168.1.100']
        'basic_auth_user' => getenv('FRESH_MIGRATE_USER') ?: null,
        'basic_auth_pass_hash' => getenv('FRESH_MIGRATE_PASS_HASH') ?: null,
        'token_hash' => getenv('FRESH_MIGRATE_TOKEN_HASH') ?: null,
        'rate_limit_seconds' => 60, // Increased from 30
        'allow_dry_run' => true,
        'max_attempts' => 3, // Lock after failed attempts
        'lockout_duration' => 900, // 15 minutes
    ];

// Validate configuration
    function validate_config(): void {
        global $config;

        if (!is_dir($config['laravel_path'])) {
            http_response_code(500);
            die('Laravel path not found');
        }

        if (!is_executable($config['php_path'])) {
            http_response_code(500);
            die('PHP executable not found');
        }

        if (!is_writable(dirname($config['backup_dir']))) {
            http_response_code(500);
            die('Backup directory not writable');
        }
    }

// Enhanced logging with size limits
    function log_message($message, $level = 'INFO'): void {
        global $config;
        $timestamp = date('Y-m-d H:i:s');
        $client_ip = get_client_ip();
        $formatted_message = "[$timestamp] [$level] [IP:$client_ip] $message";

        // Rotate log if too large
        if (file_exists($config['log_file']) && filesize($config['log_file']) > $config['max_log_size']) {
            rename($config['log_file'], $config['log_file'] . '.old');
        }

        if (is_writable(dirname($config['log_file']))) {
            file_put_contents($config['log_file'], $formatted_message . "\n", FILE_APPEND | LOCK_EX);
        }

        if ($level === 'ERROR') {
            error_log($formatted_message);
        }

        // Only show in browser if not CLI
        if (PHP_SAPI !== 'cli') {
            echo "<pre>" . htmlspecialchars($formatted_message) . "</pre>";
        }
    }

// Get real client IP
    function get_client_ip(): string {
        $headers = [
            'HTTP_CF_CONNECTING_IP',     // Cloudflare
            'HTTP_X_FORWARDED_FOR',      // Load balancers
            'HTTP_X_REAL_IP',           // Nginx proxy
            'REMOTE_ADDR'               // Standard
        ];

        foreach ($headers as $header) {
            if (!empty($_SERVER[$header])) {
                $ips = explode(',', $_SERVER[$header]);
                $ip = trim($ips[0]);
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }

        return $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    }

// Enhanced rate limiting with attempt tracking
    function check_rate_limit(): void {
        global $config, $security;

        $attempts_file = dirname($config['lock_file']) . '/.migrate_attempts';
        $now = time();

        // Check lockout
        if (file_exists($config['lock_file'])) {
            $lock_time = filemtime($config['lock_file']);
            if ($lock_time && ($now - $lock_time) < $security['rate_limit_seconds']) {
                $retry = $security['rate_limit_seconds'] - ($now - $lock_time);
                http_response_code(429);
                header('Content-Type: text/plain');
                die("Rate limited. Retry in $retry seconds");
            }
        }

        // Track failed attempts
        $client_ip = get_client_ip();
        $attempts = [];
        if (file_exists($attempts_file)) {
            $attempts = json_decode(file_get_contents($attempts_file), true) ?: [];
        }

        // Clean old attempts (older than lockout duration)
        $attempts = array_filter($attempts, fn($time) => ($now - $time) < $security['lockout_duration']);

        // Check if IP has too many recent attempts
        $ip_attempts = array_filter($attempts, fn($time, $ip) => $ip === $client_ip, ARRAY_FILTER_USE_BOTH);
        if (count($ip_attempts) >= $security['max_attempts']) {
            log_message("IP $client_ip blocked due to too many attempts", 'SECURITY');
            http_response_code(429);
            die('Too many attempts. Try again later.');
        }

        touch($config['lock_file']);
    }

// Record failed attempt
    function record_failed_attempt(): void {
        global $config, $security;

        $attempts_file = dirname($config['lock_file']) . '/.migrate_attempts';
        $client_ip = get_client_ip();
        $now = time();

        $attempts = [];
        if (file_exists($attempts_file)) {
            $attempts = json_decode(file_get_contents($attempts_file), true) ?: [];
        }

        $attempts[$client_ip] = $now;
        file_put_contents($attempts_file, json_encode($attempts), LOCK_EX);

        log_message("Failed authentication attempt from $client_ip", 'SECURITY');
    }

// Enhanced security gate
    (function () use (&$security, &$config) {
        if (PHP_SAPI === 'cli') return;

        // Validate config first
        validate_config();

        $deny = function ($message = 'Access denied', $code = 403) {
            record_failed_attempt();
            http_response_code($code);
            header('Content-Type: text/plain; charset=utf-8');
            header('X-Content-Type-Options: nosniff');
            header('X-Frame-Options: DENY');
            log_message("Access denied: $message", 'SECURITY');
            echo $message;
            exit;
        };

        // HTTPS enforcement
        if ($security['require_https']) {
            $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
                ($_SERVER['SERVER_PORT'] ?? 0) == 443 ||
                (strtolower($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
            if (!$isHttps) $deny('HTTPS required', 403);
        }

        // Rate limiting
        check_rate_limit();

        // IP allowlist
        if (!empty($security['ip_allowlist'])) {
            $client_ip = get_client_ip();
            if (!in_array($client_ip, $security['ip_allowlist'])) {
                $deny("IP $client_ip not allowed", 403);
            }
        }

        // POST method required
        if ($security['require_post'] && ($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
            header('Allow: POST');
            $deny('POST method required', 405);
        }

        // Confirmation required
        if ($security['require_confirm']) {
            $confirm = $_GET['confirm'] ?? ($_POST['confirm'] ?? ($_SERVER['HTTP_X_CONFIRM'] ?? '0'));
            if ($confirm !== '1') {
                $deny('Confirmation required: add confirm=1', 400);
            }
        }

        // Basic Auth
        if ($security['basic_auth_user'] && $security['basic_auth_pass_hash']) {
            $user = $_SERVER['PHP_AUTH_USER'] ?? '';
            $pass = $_SERVER['PHP_AUTH_PW'] ?? '';
            if ($user !== $security['basic_auth_user'] || !password_verify($pass, $security['basic_auth_pass_hash'])) {
                header('WWW-Authenticate: Basic realm="Fresh Migrate"');
                $deny('Invalid credentials', 401);
            }
        }

        // Token auth (required if set)
        if ($security['token_hash']) {
            $token = $_GET['token'] ?? ($_POST['token'] ?? ($_SERVER['HTTP_X_AUTH_TOKEN'] ?? ''));
            if (!$token) {
                $deny('Token required', 401);
            }

            $valid = password_verify($token, $security['token_hash']) ||
                hash_equals($security['token_hash'], hash('sha256', $token));
            if (!$valid) {
                $deny('Invalid token', 401);
            }
        }

        // Log successful authentication
        $client_ip = get_client_ip();
        log_message("Successful authentication from $client_ip", 'SECURITY');
    })();

// Set execution time limit
    set_time_limit($config['max_execution_time']);

// Rest of your existing code (execute_command, main logic) remains the same...
// [Previous execute_command function and main logic here]

    function execute_command($command, $description, $allow_failure = false) {
        log_message("Executing: $description");
        log_message("Command: " . preg_replace('/--password=\S+/', '--password=***', $command)); // Hide passwords in logs

        $output = [];
        $return_code = 0;
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

// Main execution logic
    $dry_run = isset($_GET['dry_run']) && $_GET['dry_run'] === '1' && $security['allow_dry_run'];

    try {
        log_message("Starting fresh migration process..." . ($dry_run ? " (DRY RUN)" : ""));

        // Create backup directory
        if (!is_dir($config['backup_dir'])) {
            if (!mkdir($config['backup_dir'], 0755, true)) {
                throw new Exception("Failed to create backup directory");
            }
            log_message("Created backup directory");
        }

        // Get database name
        $artisanPath = $config['laravel_path'] . '/artisan';
        $dbCommand = "{$config['php_path']} " . escapeshellarg($artisanPath) . " tinker --execute=\"echo config('database.connections.'.config('database.default').'.database');\"";
        $dbOutput = execute_command($dbCommand, "Get database name", true);
        $dbName = trim(implode('', $dbOutput));

        if ($dbName) {
            log_message("Backing up database: $dbName");
            $backupFile = $config['backup_dir'] . '/backup_' . date('Y_m_d_H_i_s') . '.sql';

            // Try mysqldump first (more reliable)
            $dumpCmd = "mysqldump --single-transaction --routines --triggers " . escapeshellarg($dbName) . " > " . escapeshellarg($backupFile);
            try {
                execute_command($dumpCmd, "Create database backup", true);
                log_message("Database backup created: $backupFile");
            } catch (Exception $e) {
                log_message("mysqldump failed, trying pg_dump for PostgreSQL", 'WARN');
                // Try pg_dump for PostgreSQL
                $pgDumpCmd = "pg_dump " . escapeshellarg($dbName) . " > " . escapeshellarg($backupFile);
                execute_command($pgDumpCmd, "PostgreSQL backup", true);
            }
        } else {
            log_message("Could not determine database name, skipping backup", 'WARN');
        }

        // Clear all caches
        $clearCommands = ['config:clear', 'cache:clear', 'route:clear', 'view:clear'];
        foreach ($clearCommands as $cmd) {
            execute_command(
                "{$config['php_path']} " . escapeshellarg($artisanPath) . " $cmd",
                "Clear $cmd cache",
                true
            );
        }

        if (!$dry_run) {
            // Run fresh migration with seeding
            execute_command(
                "{$config['php_path']} " . escapeshellarg($artisanPath) . " migrate:fresh --seed --force",
                "Execute fresh migration with seeding"
            );

            // Verify migration success
            $verifyCmd = "{$config['php_path']} " . escapeshellarg($artisanPath) . " tinker --execute=\"echo 'Migrations: '.DB::table('migrations')->count().', Users: '.(class_exists('App\\\\Models\\\\User') ? App\\\\Models\\\\User::count() : 'N/A');\"";
            $verifyOutput = execute_command($verifyCmd, "Verify migration results", true);
            log_message("Verification result: " . trim(implode('', $verifyOutput)));
        } else {
            log_message("DRY RUN: Migration commands would execute here");
        }

        log_message("Migration process completed successfully!");
        log_message("IMPORTANT: Delete this script file immediately!");

        // Clean up lock file on success
        if (file_exists($config['lock_file'])) {
            unlink($config['lock_file']);
        }

    } catch (Exception $e) {
        log_message("Migration failed: " . $e->getMessage(), 'ERROR');

        // Don't clean up lock file on failure to prevent immediate retry
        http_response_code(500);

        if (PHP_SAPI !== 'cli') {
            echo "<div style='color: red; font-weight: bold;'>Migration failed. Check logs for details.</div>";
        }
    }
?>
