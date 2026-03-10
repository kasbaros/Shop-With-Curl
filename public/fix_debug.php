<?php
/**
 * Fix APP_DEBUG and test cookies.
 *
 * https://shopwithcarl.ug/fix_debug.php?key=FIXDEBUG_2026
 */
if (($_GET['key'] ?? '') !== 'FIXDEBUG_2026') { http_response_code(403); die('Forbidden.'); }

header('Content-Type: text/plain; charset=utf-8');
ob_implicit_flush(true);
if (ob_get_level()) ob_end_flush();

$laravelPath = '/home/shopwithcaug/Laravel';
$php = '/opt/alt/php84/usr/bin/php';
$artisan = "$laravelPath/artisan";
$envPath = "$laravelPath/.env";

function out($msg) { echo "[" . date('H:i:s') . "] $msg\n"; flush(); }

out("=== FIX APP_DEBUG ===\n");

// 1. Set APP_DEBUG=false
out("1. Setting APP_DEBUG=false in .env...");
$env = file_get_contents($envPath);
$env = preg_replace('/^APP_DEBUG=.*$/m', 'APP_DEBUG=false', $env);
file_put_contents($envPath, $env);
out("   Done.");

// 2. Also ensure SESSION_SECURE_COOKIE and SESSION_DOMAIN are correct
out("\n2. Verifying session settings...");
if (!preg_match('/^SESSION_SECURE_COOKIE=/m', $env)) {
    $env .= "\nSESSION_SECURE_COOKIE=true";
    file_put_contents($envPath, $env);
    out("   Added SESSION_SECURE_COOKIE=true");
}

// 3. Rebuild config cache
out("\n3. Rebuilding caches...");
$cmds = ['config:clear', 'route:clear', 'view:clear', 'config:cache', 'route:cache', 'view:cache'];
foreach ($cmds as $cmd) {
    exec("$php $artisan $cmd 2>&1", $o);
    out("   $cmd: " . trim(end($o)));
    $o = [];
}

// 4. Verify
out("\n4. Verifying config...");
exec("$php $artisan tinker --execute=\"echo 'debug=' . var_export(config('app.debug'), true) . PHP_EOL . 'session.driver=' . config('session.driver') . PHP_EOL . 'session.domain=' . var_export(config('session.domain'), true) . PHP_EOL . 'session.secure=' . var_export(config('session.secure'), true) . PHP_EOL;\" 2>&1", $vOut);
foreach ($vOut as $line) {
    $line = trim($line);
    if ($line && !str_starts_with($line, '=') && !str_starts_with($line, '>')) {
        out("   $line");
    }
}

out("\n=== DONE ===");
out("Clear browser cookies for shopwithcarl.ug, then test login.");
out("DELETE: rm /home/shopwithcaug/public_html/fix_debug.php");
