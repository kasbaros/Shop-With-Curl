<?php
/**
 * Fix 419 — switch to file sessions and test cookies.
 *
 * Usage: https://shopwithcarl.ug/fix_419.php?key=FIX419_2026
 * DELETE after use.
 */

if (($_GET['key'] ?? '') !== 'FIX419_2026') {
    http_response_code(403);
    die('Forbidden.');
}

$action = $_GET['action'] ?? 'diagnose';

header('Content-Type: text/plain; charset=utf-8');

$home = '/home/shopwithcaug';
$laravelPath = "$home/Laravel";
$php = '/opt/alt/php84/usr/bin/php';
$artisan = "$laravelPath/artisan";
$envPath = "$laravelPath/.env";

function out($msg) {
    echo "[" . date('H:i:s') . "] $msg\n";
}

// ── ACTION: cookie_test — raw PHP cookie test ───────────────────────
if ($action === 'cookie_test') {
    out("=== RAW COOKIE TEST ===\n");

    // Check if our test cookie came back
    if (isset($_COOKIE['test_csrf_debug'])) {
        out("SUCCESS: Cookie 'test_csrf_debug' received back: " . $_COOKIE['test_csrf_debug']);
        out("Browser cookies are working correctly.");
        out("\nAll cookies received:");
        foreach ($_COOKIE as $k => $v) {
            out("   $k = " . substr($v, 0, 50) . (strlen($v) > 50 ? '...' : ''));
        }
    } else {
        // Set a test cookie and tell user to reload
        $testVal = 'test_' . time();
        setcookie('test_csrf_debug', $testVal, [
            'expires' => time() + 300,
            'path' => '/',
            'domain' => '',  // use default (current host)
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        out("Set test cookie 'test_csrf_debug' = $testVal");
        out("\nNow RELOAD this same URL to check if the cookie comes back.");
        out("URL: https://shopwithcarl.ug/fix_419.php?key=FIX419_2026&action=cookie_test");

        // Also set one with the .shopwithcarl.ug domain to compare
        setcookie('test_domain_cookie', 'domain_test_' . time(), [
            'expires' => time() + 300,
            'path' => '/',
            'domain' => '.shopwithcarl.ug',
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        out("Also set 'test_domain_cookie' with domain=.shopwithcarl.ug");
    }

    out("\nResponse headers:");
    foreach (headers_list() as $h) {
        out("   $h");
    }
    exit;
}

// ── ACTION: switch_file — switch to file sessions ───────────────────
if ($action === 'switch_file') {
    out("=== SWITCHING TO FILE SESSIONS ===\n");

    $envContent = file_get_contents($envPath);

    // Change SESSION_DRIVER to file
    if (preg_match('/^SESSION_DRIVER=.*$/m', $envContent)) {
        $envContent = preg_replace('/^SESSION_DRIVER=.*$/m', 'SESSION_DRIVER=file', $envContent);
        out("Changed SESSION_DRIVER to 'file'");
    }

    // Remove SESSION_DOMAIN (let Laravel auto-detect)
    if (preg_match('/^SESSION_DOMAIN=.*$/m', $envContent)) {
        $envContent = preg_replace('/^SESSION_DOMAIN=.*$/m', 'SESSION_DOMAIN=null', $envContent);
        out("Reset SESSION_DOMAIN to null (auto-detect)");
    }

    file_put_contents($envPath, $envContent);

    // Ensure sessions directory is writable
    $sessDir = "$laravelPath/storage/framework/sessions";
    if (!is_dir($sessDir)) {
        mkdir($sessDir, 0775, true);
        out("Created sessions directory");
    }
    exec("chmod -R 775 $sessDir");
    out("Set sessions directory permissions");

    // Rebuild config cache
    exec("$php $artisan config:clear 2>&1");
    exec("$php $artisan config:cache 2>&1", $o);
    out("Config cache rebuilt: " . trim(end($o)));

    // Verify
    exec("$php $artisan tinker --execute=\"echo 'driver=' . config('session.driver') . PHP_EOL . 'domain=' . var_export(config('session.domain'), true) . PHP_EOL;\" 2>&1", $vOut);
    foreach ($vOut as $line) {
        $line = trim($line);
        if ($line && !str_starts_with($line, '=') && !str_starts_with($line, '>')) {
            out("   $line");
        }
    }

    out("\nDone! Try logging in now.");
    out("If it works → DB session driver has an issue.");
    out("If still 419 → problem is in cookie handling.\n");
    out("To switch back to database sessions:");
    out("   https://shopwithcarl.ug/fix_419.php?key=FIX419_2026&action=switch_db");
    exit;
}

// ── ACTION: switch_db — switch back to database sessions ────────────
if ($action === 'switch_db') {
    out("=== SWITCHING BACK TO DATABASE SESSIONS ===\n");

    $envContent = file_get_contents($envPath);
    $envContent = preg_replace('/^SESSION_DRIVER=.*$/m', 'SESSION_DRIVER=database', $envContent);
    file_put_contents($envPath, $envContent);

    exec("$php $artisan config:clear 2>&1");
    exec("$php $artisan config:cache 2>&1", $o);
    out("Switched to database sessions and rebuilt cache.");
    exit;
}

// ── ACTION: diagnose (default) ──────────────────────────────────────
out("=== 419 FIX OPTIONS ===\n");
out("Choose an action:\n");
out("1. TEST COOKIES (check if browser accepts cookies from this domain):");
out("   https://shopwithcarl.ug/fix_419.php?key=FIX419_2026&action=cookie_test\n");
out("2. SWITCH TO FILE SESSIONS (bypass database session driver):");
out("   https://shopwithcarl.ug/fix_419.php?key=FIX419_2026&action=switch_file\n");
out("3. SWITCH BACK TO DATABASE SESSIONS:");
out("   https://shopwithcarl.ug/fix_419.php?key=FIX419_2026&action=switch_db\n");

// Show current state
out("--- Current State ---");
exec("$php $artisan tinker --execute=\"echo 'driver=' . config('session.driver') . PHP_EOL . 'domain=' . var_export(config('session.domain'), true) . PHP_EOL . 'cookie=' . config('session.cookie') . PHP_EOL . 'secure=' . var_export(config('session.secure'), true) . PHP_EOL;\" 2>&1", $stateOut);
foreach ($stateOut as $line) {
    $line = trim($line);
    if ($line && !str_starts_with($line, '=') && !str_starts_with($line, '>')) {
        out("   $line");
    }
}
