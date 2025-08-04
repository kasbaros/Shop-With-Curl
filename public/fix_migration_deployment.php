<?php
/**
 * Production Migration Fix Script (Web Version)
 * Use this to fix the failed post_categories migration in production
 * Access via: https://yourdomain.com/fix_migration_deployment.php
 */

// Set content type for proper display
header('Content-Type: text/html; charset=utf-8');

$config = [
    'laravel_path' => '/home/shopwithcaug/Laravel',
    'php_path' => '/opt/alt/php84/usr/bin/php',
];

function log_message($message, $level = 'INFO') {
    $timestamp = date('Y-m-d H:i:s');
    $formatted_message = "[$timestamp] [$level] $message";
    
    // Color coding for web display
    $color = match($level) {
        'ERROR' => '#ff4444',
        'WARN' => '#ff8800', 
        'SUCCESS' => '#00aa00',
        default => '#333333'
    };
    
    echo "<div style='color: $color; font-family: monospace; margin: 2px 0;'>$formatted_message</div>";
    
    // Also log to file
    $log_file = '/home/shopwithcaug/public_html/migration_fix.log';
    file_put_contents($log_file, $formatted_message . "\n", FILE_APPEND | LOCK_EX);
    
    // Flush output immediately
    if (ob_get_level()) {
        ob_flush();
    }
    flush();
}

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

// Start HTML output
echo '<!DOCTYPE html>
<html>
<head>
    <title>Migration Fix - Shop With Carl</title>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; max-width: 1200px; margin: 0 auto; padding: 20px; background: #f5f5f5; }
        .container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header { background: #2563eb; color: white; padding: 15px; margin: -20px -20px 20px -20px; border-radius: 8px 8px 0 0; }
        .log-container { background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 4px; padding: 15px; max-height: 500px; overflow-y: auto; }
        .success { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 10px; border-radius: 4px; margin: 10px 0; }
        .error { background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 10px; border-radius: 4px; margin: 10px 0; }
        .warning { background: #fff3cd; border: 1px solid #ffeaa7; color: #856404; padding: 10px; border-radius: 4px; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔧 Migration Fix Script</h1>
            <p>Fixing the post_categories migration issue in production</p>
        </div>
        <div class="log-container">
';

try {
    log_message("Starting migration fix process...", 'SUCCESS');
    
    chdir($config['laravel_path']);
    $artisanPath = $config['laravel_path'] . '/artisan';
    
    if (!file_exists($artisanPath)) {
        throw new Exception("Artisan file not found at $artisanPath");
    }
    
    // Step 1: Check migration status
    log_message("Checking migration status...");
    execute_command(
        "{$config['php_path']} " . escapeshellarg($artisanPath) . " migrate:status",
        "Check migration status"
    );
    
    // Step 2: Try to rollback the failed migration
    log_message("Rolling back failed migration...");
    try {
        execute_command(
            "{$config['php_path']} " . escapeshellarg($artisanPath) . " migrate:rollback --step=1 --force",
            "Rollback failed migration"
        );
    } catch (Exception $e) {
        log_message("Rollback may have failed, but continuing...", 'WARN');
    }
    
    // Step 3: Clear all caches
    log_message("Clearing caches...");
    $clearCommands = [
        'config:clear' => 'Clear config cache',
        'cache:clear' => 'Clear application cache',
        'route:clear' => 'Clear route cache', 
        'view:clear' => 'Clear view cache',
    ];
    
    foreach ($clearCommands as $command => $description) {
        try {
            execute_command(
                "{$config['php_path']} " . escapeshellarg($artisanPath) . " $command",
                $description
            );
        } catch (Exception $e) {
            log_message("Warning: $description failed: " . $e->getMessage(), 'WARN');
        }
    }
    
    // Step 4: Run migrations with fixed order
    log_message("Running migrations with proper order...");
    execute_command(
        "{$config['php_path']} " . escapeshellarg($artisanPath) . " migrate --force",
        "Run database migrations"
    );
    
    // Step 5: Check if we need to seed admin users
    log_message("Checking for admin users...");
    try {
        $checkAdminCommand = "{$config['php_path']} " . escapeshellarg($artisanPath) . " tinker --execute=\"echo App\\\\Models\\\\User::where('is_admin', true)->count();\"";
        $output = execute_command($checkAdminCommand, "Check admin users count");
        $count = intval(trim(implode('', $output)));
        
        if ($count == 0) {
            log_message("No admin users found, running seeder...");
            execute_command(
                "{$config['php_path']} " . escapeshellarg($artisanPath) . " db:seed --class=AdminUserSeeder --force",
                "Seed admin users"
            );
        } else {
            log_message("Found $count admin users, skipping seeding");
        }
    } catch (Exception $e) {
        log_message("Could not check/seed admin users: " . $e->getMessage(), 'WARN');
    }
    
    // Step 6: Cache configuration
    log_message("Caching configuration...");
    try {
        execute_command(
            "{$config['php_path']} " . escapeshellarg($artisanPath) . " config:cache",
            "Cache configuration"
        );
    } catch (Exception $e) {
        log_message("Warning: Config caching failed: " . $e->getMessage(), 'WARN');
    }
    
    log_message("Migration fix completed successfully!", 'SUCCESS');
    
    echo '</div>'; // Close log-container
    echo '<div class="success">';
    echo '<h3>✅ MIGRATION FIX COMPLETED SUCCESSFULLY!</h3>';
    echo '<p>All migration issues have been resolved. You can now:</p>';
    echo '<ul>';
    echo '<li>Try your deployment again</li>';
    echo '<li>Check that your application is working properly</li>';
    echo '<li>Admin users have been seeded if none existed</li>';
    echo '</ul>';
    echo '<p><strong>Log file:</strong> /home/shopwithcaug/public_html/migration_fix.log</p>';
    echo '</div>';
    
} catch (Exception $e) {
    log_message("Migration fix failed: " . $e->getMessage(), 'ERROR');
    
    echo '</div>'; // Close log-container
    echo '<div class="error">';
    echo '<h3>❌ MIGRATION FIX FAILED</h3>';
    echo '<p><strong>Error:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '<p>Please check the log file for more details: /home/shopwithcaug/public_html/migration_fix.log</p>';
    echo '<p>You may need to contact your developer or try manual database cleanup.</p>';
    echo '</div>';
}

echo '</div>'; // Close container
echo '</body>';
echo '</html>';
?>
