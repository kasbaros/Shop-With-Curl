<?php
///**
// * Laravel Auto-Deployment Script for cPanel with Asynchronous Processing
// * Place in /home/shopwithcaug/public_html/deploy.php
// * Responds immediately to GitHub webhook and runs deployment in background
// */
//
//// Configuration
//$config = [
//    'git_repo_url' => 'https://github.com/kasbaros/Shop-With-Curl.git',
//    'branch' => 'master',
//    'repo_path' => '/home/shopwithcaug/repositories/shopwithcarl',
//    'laravel_path' => '/home/shopwithcaug/Laravel',
//    'public_html_path' => '/home/shopwithcaug/public_html',
//    'webhook_secret' => 'Thi5K3y15Un8eL13vabL363R10Us11Y=',
//    'allowed_ips' => [
//        '140.82.112.0/20',
//        '192.30.252.0/22',
//        '185.199.108.0/22',
//        '143.55.64.0/20',
//        '127.0.0.1',
//        '41.210.145.40',
//    ],
//    'php_path' => '/opt/alt/php84/usr/bin/php',
//    'composer_phar' => '/home/shopwithcaug/Laravel/composer.phar',
//    'node_path' => '/usr/local/bin/node',
//    'npm_path' => '/usr/local/bin/npm',
//    'background_script' => '/home/shopwithcaug/public_html/background_deploy.php',
//];
//
//// Logging function
//function log_message($message) {
//    global $config;
//    $log_file = rtrim($config['public_html_path'], '/') . '/deployment.log';
//    $timestamp = date('Y-m-d H:i:s');
//
//    // Try to write to log file
//    if (!is_writable($config['public_html_path']) || (file_exists($log_file) && !is_writable($log_file))) {
//        error_log("[$timestamp] Warning: Cannot write to $log_file");
//        error_log("[$timestamp] $message");
//    } else {
//        file_put_contents($log_file, "[$timestamp] $message\n", FILE_APPEND | LOCK_EX);
//    }
//
//    // Also output to response
//    echo "[$timestamp] $message\n";
//}
//
//// IP validation
//function is_allowed_ip($ip, $allowed_ranges) {
//    foreach ($allowed_ranges as $range) {
//        if (strpos($range, '/') !== false) {
//            list($subnet, $mask) = explode('/', $range);
//            if ((ip2long($ip) & ~((1 << (32 - $mask)) - 1)) == ip2long($subnet)) {
//                return true;
//            }
//        } else {
//            if ($ip === $range) return true;
//        }
//    }
//    return false;
//}
//
//// Get client IP
//function get_client_ip() {
//    $ip_keys = ['HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'HTTP_CLIENT_IP', 'REMOTE_ADDR'];
//    foreach ($ip_keys as $key) {
//        if (array_key_exists($key, $_SERVER) === true) {
//            foreach (explode(',', $_SERVER[$key]) as $ip) {
//                $ip = trim($ip);
//                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
//                    return $ip;
//                }
//            }
//        }
//    }
//    return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
//}
//
//// Function to run background deployment
//function run_background_deployment($config) {
//    // Check if background script exists
//    if (!file_exists($config['background_script'])) {
//        log_message("ERROR: Background script not found at: {$config['background_script']}");
//        return false;
//    }
//
//    // Check if background script is readable
//    if (!is_readable($config['background_script'])) {
//        log_message("ERROR: Background script is not readable: {$config['background_script']}");
//        return false;
//    }
//
//    // Create a unique log file for this deployment
//    $deployment_id = uniqid('deploy_', true);
//    $deployment_log = rtrim($config['public_html_path'], '/') . "/deployment_{$deployment_id}.log";
//
//    // Build the command
//    $command = sprintf(
//        '%s %s > %s 2>&1 &',
//        escapeshellarg($config['php_path']),
//        escapeshellarg($config['background_script']),
//        escapeshellarg($deployment_log)
//    );
//
//    log_message("Starting background deployment with ID: $deployment_id");
//    log_message("Command: $command");
//    log_message("Deployment log will be written to: $deployment_log");
//
//    // Execute the command
//    exec($command, $output, $return_code);
//
//    // Give the process a moment to start
//    sleep(1);
//
//    // Check if the deployment log was created (indicates the script started)
//    if (file_exists($deployment_log)) {
//        log_message("Background deployment started successfully (ID: $deployment_id)");
//        return $deployment_id;
//    } else {
//        log_message("ERROR: Background deployment may have failed to start");
//        return false;
//    }
//}
//
//// Function to check if deployment is already running
//function is_deployment_running($config) {
//    // Check for any running PHP processes with our background script
//    $command = "ps aux | grep '{$config['background_script']}' | grep -v grep";
//    exec($command, $output, $return_code);
//
//    return !empty($output);
//}
//
//// Verify deployment trigger
//$deploy = false;
//$client_ip = get_client_ip();
//
//// Set content type for JSON responses
//header('Content-Type: application/json');
//
//if (isset($_GET['manual']) && $_GET['manual'] === 'true') {
//    // Manual deployment trigger
//    if (!isset($_GET['secret']) || $_GET['secret'] !== $config['webhook_secret']) {
//        log_message("Manual deployment blocked: Invalid secret (IP: $client_ip)");
//        http_response_code(403);
//        die(json_encode([
//            'status' => 'error',
//            'message' => 'Unauthorized',
//            'timestamp' => date('Y-m-d H:i:s')
//        ]));
//    }
//
//    log_message("Manual deployment triggered (IP: $client_ip)");
//    $deploy = true;
//
//} else {
//    // Webhook deployment trigger
//    if (!is_allowed_ip($client_ip, $config['allowed_ips'])) {
//        log_message("Webhook deployment blocked from unauthorized IP: $client_ip");
//        http_response_code(403);
//        die(json_encode([
//            'status' => 'error',
//            'message' => 'Forbidden',
//            'timestamp' => date('Y-m-d H:i:s')
//        ]));
//    }
//
//    $payload = file_get_contents('php://input');
//    $signature = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '';
//
//    if (!empty($config['webhook_secret']) && !empty($signature)) {
//        $expected_signature = 'sha256=' . hash_hmac('sha256', $payload, $config['webhook_secret']);
//        if (!hash_equals($expected_signature, $signature)) {
//            log_message("Invalid webhook signature from IP: $client_ip");
//            http_response_code(403);
//            die(json_encode([
//                'status' => 'error',
//                'message' => 'Invalid signature',
//                'timestamp' => date('Y-m-d H:i:s')
//            ]));
//        }
//    }
//
//    $data = json_decode($payload, true);
//    if (isset($data['ref']) && $data['ref'] === 'refs/heads/' . $config['branch']) {
//        log_message("Webhook deployment triggered for branch: " . $config['branch']);
//        $deploy = true;
//    } else {
//        log_message("Ignoring push to branch: " . ($data['ref'] ?? 'unknown'));
//        echo json_encode([
//            'status' => 'ignored',
//            'message' => 'Push to non-target branch',
//            'branch' => $data['ref'] ?? 'unknown',
//            'timestamp' => date('Y-m-d H:i:s')
//        ]);
//        exit;
//    }
//}
//
//if ($deploy) {
//    // Check if deployment is already running
//    if (is_deployment_running($config)) {
//        log_message("Deployment request ignored: Another deployment is already running");
//        echo json_encode([
//            'status' => 'ignored',
//            'message' => 'Deployment already in progress',
//            'timestamp' => date('Y-m-d H:i:s')
//        ]);
//        exit;
//    }
//
//    // Run deployment in background
//    $deployment_id = run_background_deployment($config);
//
//    if ($deployment_id) {
//        // Respond with success
//        http_response_code(200);
//        echo json_encode([
//            'status' => 'accepted',
//            'message' => 'Deployment started in background',
//            'deployment_id' => $deployment_id,
//            'log_file' => "deployment_{$deployment_id}.log",
//            'timestamp' => date('Y-m-d H:i:s')
//        ]);
//    } else {
//        // Respond with error
//        http_response_code(500);
//        echo json_encode([
//            'status' => 'error',
//            'message' => 'Failed to start background deployment',
//            'timestamp' => date('Y-m-d H:i:s')
//        ]);
//    }
//
//} else {
//    echo json_encode([
//        'status' => 'ignored',
//        'message' => 'No deployment triggered',
//        'timestamp' => date('Y-m-d H:i:s')
//    ]);
//}
//?>

<?php
    /**
     * Robust Laravel Auto-Deployment Script for cPanel
     * Enhanced version of your existing deploy.php
     */

// Configuration
    $config = [
        'git_repo_url' => 'https://github.com/kasbaros/Shop-With-Curl.git',
        'branch' => 'master',
        'repo_path' => '/home/shopwithcaug/repositories/shopwithcarl',
        'laravel_path' => '/home/shopwithcaug/Laravel',
        'public_html_path' => '/home/shopwithcaug/public_html',
        'webhook_secret' => 'Thi5K3y15Un8eL13vabL363R10Us11Y=',
        'allowed_ips' => [
            '140.82.112.0/20',
            '192.30.252.0/22',
            '185.199.108.0/22',
            '143.55.64.0/20',
            '127.0.0.1',
            '41.210.145.40',
        ],
        'php_path' => '/opt/alt/php84/usr/bin/php',
        'composer_phar' => '/home/shopwithcaug/Laravel/composer.phar',
        'background_script' => '/home/shopwithcaug/public_html/background_deploy.php',
        'lock_file' => '/home/shopwithcaug/public_html/.deployment.lock',
        'status_file' => '/home/shopwithcaug/public_html/.deployment.status',
        'max_deployment_time' => 600, // 10 minutes
    ];

// Enhanced logging function
    function log_message($message, $level = 'INFO') {
        global $config;
        $log_file = rtrim($config['public_html_path'], '/') . '/deployment.log';
        $timestamp = date('Y-m-d H:i:s');
        $formatted_message = "[$timestamp] [$level] $message";

        // Try to write to log file
        if (is_writable(dirname($log_file))) {
            file_put_contents($log_file, $formatted_message . "\n", FILE_APPEND | LOCK_EX);
        }

        // Log errors to system log as well
        if ($level === 'ERROR') {
            error_log($formatted_message);
        }

        // Also output to response
        echo $formatted_message . "\n";
    }

// IP validation function
    function is_allowed_ip($ip, $allowed_ranges) {
        foreach ($allowed_ranges as $range) {
            if (strpos($range, '/') !== false) {
                list($subnet, $mask) = explode('/', $range);
                if ((ip2long($ip) & ~((1 << (32 - $mask)) - 1)) == ip2long($subnet)) {
                    return true;
                }
            } else {
                if ($ip === $range) return true;
            }
        }
        return false;
    }

// Get client IP function
    function get_client_ip() {
        $ip_keys = ['HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'HTTP_CLIENT_IP', 'REMOTE_ADDR'];
        foreach ($ip_keys as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
        return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    }

// Enhanced lock management
    function acquire_deployment_lock($config) {
        $lock_file = $config['lock_file'];

        // Check if lock file exists and is still valid
        if (file_exists($lock_file)) {
            $lock_data = @json_decode(file_get_contents($lock_file), true);
            if ($lock_data && isset($lock_data['timestamp'])) {
                $lock_age = time() - $lock_data['timestamp'];

                // If lock is older than max deployment time, consider it stale
                if ($lock_age < $config['max_deployment_time']) {
                    return false; // Lock is still valid
                } else {
                    log_message("Removing stale lock file (age: {$lock_age}s)", 'WARN');
                    @unlink($lock_file);
                }
            }
        }

        // Create new lock
        $deployment_id = uniqid('deploy_', true);
        $lock_data = [
            'timestamp' => time(),
            'pid' => getmypid(),
            'deployment_id' => $deployment_id
        ];

        if (file_put_contents($lock_file, json_encode($lock_data), LOCK_EX) === false) {
            log_message("Failed to create lock file", 'ERROR');
            return false;
        }

        return $deployment_id;
    }

// Release lock
    function release_deployment_lock($config) {
        if (file_exists($config['lock_file'])) {
            @unlink($config['lock_file']);
        }
    }

// Update deployment status
    function update_deployment_status($config, $status, $message = '', $deployment_id = '') {
        $status_data = [
            'status' => $status,
            'message' => $message,
            'deployment_id' => $deployment_id,
            'timestamp' => time(),
            'human_time' => date('Y-m-d H:i:s')
        ];

        file_put_contents($config['status_file'], json_encode($status_data), LOCK_EX);
    }

// Get deployment status
    function get_deployment_status($config) {
        if (!file_exists($config['status_file'])) {
            return ['status' => 'idle', 'message' => 'No deployment in progress'];
        }

        $status_data = @json_decode(file_get_contents($config['status_file']), true);
        return $status_data ?: ['status' => 'unknown', 'message' => 'Unable to read status'];
    }

// Enhanced background deployment function
    function run_background_deployment($config) {
        // Check if background script exists
        if (!file_exists($config['background_script'])) {
            log_message("ERROR: Background script not found at: {$config['background_script']}", 'ERROR');
            return false;
        }

        // Check if background script is readable
        if (!is_readable($config['background_script'])) {
            log_message("ERROR: Background script is not readable: {$config['background_script']}", 'ERROR');
            return false;
        }

        // Acquire lock
        $deployment_id = acquire_deployment_lock($config);
        if (!$deployment_id) {
            log_message("Could not acquire deployment lock - another deployment may be running", 'ERROR');
            return false;
        }

        // Create a unique log file for this deployment
        $deployment_log = rtrim($config['public_html_path'], '/') . "/deployment_{$deployment_id}.log";

        // Update status
        update_deployment_status($config, 'starting', "Deployment {$deployment_id} starting", $deployment_id);

        // Build the command with timeout
        $timeout_cmd = "timeout {$config['max_deployment_time']}";
        $command = sprintf(
            '%s %s %s %s > %s 2>&1 &',
            $timeout_cmd,
            escapeshellarg($config['php_path']),
            escapeshellarg($config['background_script']),
            escapeshellarg($deployment_id),
            escapeshellarg($deployment_log)
        );

        log_message("Starting background deployment with ID: $deployment_id");
        log_message("Command: $command");
        log_message("Deployment log will be written to: $deployment_log");

        // Execute the command
        exec($command, $output, $return_code);

        // Give the process a moment to start
        usleep(500000); // 0.5 seconds

        // Check if the deployment log was created (indicates the script started)
        if (file_exists($deployment_log)) {
            log_message("Background deployment started successfully (ID: $deployment_id)");
            return $deployment_id;
        } else {
            log_message("ERROR: Background deployment may have failed to start", 'ERROR');
            release_deployment_lock($config);
            update_deployment_status($config, 'failed', 'Failed to start background deployment');
            return false;
        }
    }

// Main execution logic
    $deploy = false;
    $client_ip = get_client_ip();

// Set content type for JSON responses
    header('Content-Type: application/json');
    header('Cache-Control: no-cache, must-revalidate');

// Handle status check requests
    if (isset($_GET['status'])) {
        echo json_encode(get_deployment_status($config));
        exit;
    }

    if (isset($_GET['manual']) && $_GET['manual'] === 'true') {
        // Manual deployment trigger
        if (!isset($_GET['secret']) || $_GET['secret'] !== $config['webhook_secret']) {
            log_message("Manual deployment blocked: Invalid secret (IP: $client_ip)", 'WARN');
            http_response_code(403);
            die(json_encode([
                'status' => 'error',
                'message' => 'Unauthorized',
                'timestamp' => date('Y-m-d H:i:s')
            ]));
        }

        log_message("Manual deployment triggered (IP: $client_ip)");
        $deploy = true;

    } else {
        // Webhook deployment trigger
        if (!is_allowed_ip($client_ip, $config['allowed_ips'])) {
            log_message("Webhook deployment blocked from unauthorized IP: $client_ip", 'WARN');
            http_response_code(403);
            die(json_encode([
                'status' => 'error',
                'message' => 'Forbidden',
                'timestamp' => date('Y-m-d H:i:s')
            ]));
        }

        $payload = file_get_contents('php://input');
        $signature = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '';

        if (!empty($config['webhook_secret']) && !empty($signature)) {
            $expected_signature = 'sha256=' . hash_hmac('sha256', $payload, $config['webhook_secret']);
            if (!hash_equals($expected_signature, $signature)) {
                log_message("Invalid webhook signature from IP: $client_ip", 'WARN');
                http_response_code(403);
                die(json_encode([
                    'status' => 'error',
                    'message' => 'Invalid signature',
                    'timestamp' => date('Y-m-d H:i:s')
                ]));
            }
        }

        $data = json_decode($payload, true);
        if (isset($data['ref']) && $data['ref'] === 'refs/heads/' . $config['branch']) {
            log_message("Webhook deployment triggered for branch: " . $config['branch']);
            $deploy = true;
        } else {
            log_message("Ignoring push to branch: " . ($data['ref'] ?? 'unknown'));
            echo json_encode([
                'status' => 'ignored',
                'message' => 'Push to non-target branch',
                'branch' => $data['ref'] ?? 'unknown',
                'timestamp' => date('Y-m-d H:i:s')
            ]);
            exit;
        }
    }

    if ($deploy) {
        $deployment_id = run_background_deployment($config);

        if ($deployment_id) {
            // Respond with success
            http_response_code(200);
            echo json_encode([
                'status' => 'accepted',
                'message' => 'Deployment started in background',
                'deployment_id' => $deployment_id,
                'log_file' => "deployment_{$deployment_id}.log",
                'status_url' => "?status",
                'timestamp' => date('Y-m-d H:i:s')
            ]);
        } else {
            // Respond with error
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to start background deployment',
                'timestamp' => date('Y-m-d H:i:s')
            ]);
        }

    } else {
        echo json_encode([
            'status' => 'ignored',
            'message' => 'No deployment triggered',
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }
?>
