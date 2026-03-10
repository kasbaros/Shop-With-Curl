<?php
/**
 * Server Diagnostic Script — ShopWithCarl
 * Shows full directory structure, file states, symlinks, and Laravel health.
 *
 * Usage: https://shopwithcarl.ug/diagnose.php?key=DIAG_2026
 * DELETE THIS FILE after use.
 */

if (($_GET['key'] ?? '') !== 'DIAG_2026') {
    http_response_code(403);
    die('Forbidden.');
}

header('Content-Type: text/plain; charset=utf-8');
ob_implicit_flush(true);
if (ob_get_level()) ob_end_flush();

$php = '/opt/alt/php84/usr/bin/php';
$home = '/home/shopwithcaug';
$laravelPath = "$home/Laravel";
$publicHtml = "$home/public_html";
$repoPath = "$home/repositories/shopwithcarl";

function section($title) {
    echo "\n" . str_repeat('=', 60) . "\n";
    echo "  $title\n";
    echo str_repeat('=', 60) . "\n\n";
}

function run($cmd) {
    exec($cmd . ' 2>&1', $out, $rc);
    echo implode("\n", $out) . "\n";
    if ($rc !== 0) echo "[exit code: $rc]\n";
    return $out;
}

// ── 1. Server Environment ──────────────────────────────────────────
section('1. SERVER ENVIRONMENT');
echo "PHP SAPI: " . php_sapi_name() . "\n";
echo "PHP Version: " . phpversion() . "\n";
echo "Server Software: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'unknown') . "\n";
echo "Document Root: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'unknown') . "\n";
echo "Script Filename: " . ($_SERVER['SCRIPT_FILENAME'] ?? 'unknown') . "\n";
echo "Current Dir: " . getcwd() . "\n";
echo "User: " . (function_exists('posix_getpwuid') ? posix_getpwuid(posix_geteuid())['name'] : get_current_user()) . "\n";
echo "Home: $home\n";
echo "Disk free (home): " . round(disk_free_space($home) / 1024 / 1024) . " MB\n";

// ── 2. Home Directory Top-Level ────────────────────────────────────
section('2. HOME DIRECTORY — TOP LEVEL');
run("ls -la $home/");

// ── 3. public_html Contents ────────────────────────────────────────
section('3. PUBLIC_HTML — FULL LISTING');
run("ls -la $publicHtml/");

section('3b. PUBLIC_HTML — ALL FILES (recursive, max depth 3)');
run("find $publicHtml -maxdepth 3 -not -path '*/build/assets/*' -not -path '*/.well-known/*' | head -200");

section('3c. PUBLIC_HTML — index.php CONTENTS');
$indexPath = "$publicHtml/index.php";
if (file_exists($indexPath)) {
    echo file_get_contents($indexPath);
} else {
    echo "FILE NOT FOUND!\n";
}

section('3d. PUBLIC_HTML — .htaccess FIRST 10 LINES');
$htaccess = "$publicHtml/.htaccess";
if (file_exists($htaccess)) {
    $lines = file($htaccess);
    echo implode('', array_slice($lines, 0, 10));
    echo "\n... (" . count($lines) . " lines total)\n";
} else {
    echo "FILE NOT FOUND!\n";
}

section('3e. STORAGE SYMLINK CHECK');
$storageLink = "$publicHtml/storage";
if (is_link($storageLink)) {
    echo "storage is SYMLINK → " . readlink($storageLink) . "\n";
    echo "Target exists: " . (is_dir(readlink($storageLink)) ? 'YES' : 'NO') . "\n";
} elseif (is_dir($storageLink)) {
    echo "storage is a REAL DIRECTORY (not a symlink!)\n";
    run("ls -la $storageLink/ | head -20");
} else {
    echo "storage DOES NOT EXIST!\n";
}

// ── 4. Laravel Directory ───────────────────────────────────────────
section('4. LARAVEL DIRECTORY — TOP LEVEL');
if (is_dir($laravelPath)) {
    run("ls -la $laravelPath/");
} else {
    echo "DIRECTORY NOT FOUND: $laravelPath\n";
}

section('4b. LARAVEL — KEY FILES EXIST?');
$keyFiles = [
    '.env', 'artisan', 'composer.json', 'composer.lock', 'composer.phar',
    'vendor/autoload.php', 'bootstrap/app.php', 'bootstrap/cache/config.php',
    'bootstrap/cache/routes-v7.php', 'config/app.php', 'storage/logs/laravel.log',
    'public/index.php', 'public/build/manifest.json',
];
foreach ($keyFiles as $f) {
    $full = "$laravelPath/$f";
    $exists = file_exists($full);
    $size = $exists ? filesize($full) : 0;
    $mod = $exists ? date('Y-m-d H:i:s', filemtime($full)) : '';
    printf("  %-45s %s  %s  %s\n", $f, $exists ? 'OK' : 'MISSING', $size ? "{$size}b" : '', $mod);
}

section('4c. LARAVEL .env (redacted)');
$envFile = "$laravelPath/.env";
if (file_exists($envFile)) {
    $lines = file($envFile);
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || $line[0] === '#') {
            echo "$line\n";
            continue;
        }
        if (preg_match('/^([A-Z_]+)=(.*)$/', $line, $m)) {
            $key = $m[1];
            $val = $m[2];
            // Redact sensitive values
            $sensitive = ['PASSWORD', 'SECRET', 'KEY', 'TOKEN', 'HASH', 'PRIVATE', 'CREDENTIAL'];
            $isSensitive = false;
            foreach ($sensitive as $s) {
                if (stripos($key, $s) !== false) { $isSensitive = true; break; }
            }
            if ($isSensitive && strlen($val) > 0) {
                echo "$key=***REDACTED***\n";
            } else {
                echo "$line\n";
            }
        } else {
            echo "$line\n";
        }
    }
} else {
    echo ".env NOT FOUND!\n";
}

section('4d. LARAVEL vendor/ STATUS');
if (is_dir("$laravelPath/vendor")) {
    $vendorCount = trim(shell_exec("find $laravelPath/vendor -maxdepth 1 -type d | wc -l") ?? '0');
    echo "vendor/ exists with $vendorCount top-level packages\n";
    echo "autoload.php: " . (file_exists("$laravelPath/vendor/autoload.php") ? 'EXISTS' : 'MISSING') . "\n";
} else {
    echo "vendor/ DOES NOT EXIST — composer install has not been run!\n";
}

section('4e. STORAGE DIRECTORY STRUCTURE');
if (is_dir("$laravelPath/storage")) {
    run("find $laravelPath/storage -maxdepth 3 -type d");
    echo "\nStorage file counts:\n";
    run("find $laravelPath/storage/app/public -type f 2>/dev/null | wc -l");
    echo " files in storage/app/public/\n";
    run("find $laravelPath/storage/framework/views -type f -name '*.php' 2>/dev/null | wc -l");
    echo " compiled views\n";
    run("du -sh $laravelPath/storage/ 2>/dev/null");
} else {
    echo "storage/ DOES NOT EXIST!\n";
}

section('4f. BOOTSTRAP/CACHE STATUS');
if (is_dir("$laravelPath/bootstrap/cache")) {
    run("ls -la $laravelPath/bootstrap/cache/");
} else {
    echo "bootstrap/cache/ DOES NOT EXIST!\n";
}

// ── 5. Repository Directory ────────────────────────────────────────
section('5. REPOSITORY DIRECTORY');
if (is_dir($repoPath)) {
    run("ls -la $repoPath/ | head -20");
    echo "\nGit status:\n";
    run("cd $repoPath && git log --oneline -3 2>/dev/null");
    run("cd $repoPath && git status --short 2>/dev/null | head -10");
} else {
    echo "REPO NOT FOUND: $repoPath\n";
}

// ── 6. Laravel Artisan Health Checks ───────────────────────────────
section('6. ARTISAN HEALTH CHECKS');

$artisan = "$laravelPath/artisan";
if (file_exists($artisan) && file_exists("$laravelPath/vendor/autoload.php")) {
    echo "-- route:list (first 10) --\n";
    run("$php $artisan route:list --columns=method,uri,name 2>&1 | head -15");

    echo "\n-- migrate:status (last 5) --\n";
    run("$php $artisan migrate:status 2>&1 | tail -10");

    echo "\n-- config values --\n";
    run("$php $artisan tinker --execute=\"echo 'APP_URL=' . config('app.url') . PHP_EOL . 'APP_ENV=' . config('app.env') . PHP_EOL . 'APP_DEBUG=' . (config('app.debug') ? 'true' : 'false') . PHP_EOL . 'DB_CONN=' . config('database.default') . PHP_EOL . 'SESSION=' . config('session.driver') . PHP_EOL . 'CACHE=' . config('cache.default') . PHP_EOL;\" 2>&1");
} else {
    echo "Cannot run artisan — ";
    if (!file_exists($artisan)) echo "artisan file missing!\n";
    if (!file_exists("$laravelPath/vendor/autoload.php")) echo "vendor/autoload.php missing!\n";
}

// ── 7. Error Log (last 30 lines) ──────────────────────────────────
section('7. LARAVEL LOG (last 40 lines)');
$logFile = "$laravelPath/storage/logs/laravel.log";
if (file_exists($logFile)) {
    $lines = file($logFile);
    $tail = array_slice($lines, -40);
    echo implode('', $tail);
} else {
    echo "No laravel.log found.\n";
}

// ── 8. PHP Error Log ───────────────────────────────────────────────
section('8. PHP ERROR LOG');
$phpErrorLog = "$publicHtml/php_error_log";
if (file_exists($phpErrorLog)) {
    $lines = file($phpErrorLog);
    $tail = array_slice($lines, -20);
    echo implode('', $tail);
} else {
    echo "No php_error_log in public_html.\n";
    // Try common cPanel location
    $altLog = "$home/logs/error.log";
    if (file_exists($altLog)) {
        echo "Found $altLog:\n";
        $lines = file($altLog);
        echo implode('', array_slice($lines, -20));
    }
}

// ── 9. Deployment Status ───────────────────────────────────────────
section('9. DEPLOYMENT STATUS');
$statusFile = "$publicHtml/.deployment.status";
if (file_exists($statusFile)) {
    echo file_get_contents($statusFile) . "\n";
} else {
    echo "No deployment status file.\n";
}

$deployLog = "$publicHtml/deployment.log";
if (file_exists($deployLog)) {
    echo "\nLast 20 lines of deployment.log:\n";
    $lines = file($deployLog);
    echo implode('', array_slice($lines, -20));
}

// ── 10. Test Laravel Bootstrap ─────────────────────────────────────
section('10. LARAVEL BOOTSTRAP TEST');
try {
    // Try to bootstrap Laravel from this script
    $autoload = "$laravelPath/vendor/autoload.php";
    $bootstrap = "$laravelPath/bootstrap/app.php";

    if (!file_exists($autoload)) {
        echo "FATAL: vendor/autoload.php missing. Run composer install.\n";
    } elseif (!file_exists($bootstrap)) {
        echo "FATAL: bootstrap/app.php missing. Laravel files not deployed.\n";
    } else {
        echo "Both autoload.php and bootstrap/app.php exist.\n";
        echo "Attempting require of autoload.php... ";
        // Don't actually bootstrap — just verify the files parse
        $syntax1 = shell_exec("$php -l $autoload 2>&1");
        echo trim($syntax1) . "\n";
        $syntax2 = shell_exec("$php -l $bootstrap 2>&1");
        echo "bootstrap/app.php syntax: " . trim($syntax2) . "\n";
        $syntax3 = shell_exec("$php -l $publicHtml/index.php 2>&1");
        echo "public_html/index.php syntax: " . trim($syntax3) . "\n";
    }
} catch (Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

section('DONE');
echo "Diagnosis completed at " . date('Y-m-d H:i:s') . "\n";
echo "DELETE THIS FILE when done: rm $publicHtml/diagnose.php\n";
