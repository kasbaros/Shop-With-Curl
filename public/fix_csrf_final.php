<?php
/**
 * Final CSRF fix: reset session domain, rebuild all caches.
 *
 * Usage: https://shopwithcarl.ug/fix_csrf_final.php?key=CSRF_FINAL_2026
 * DELETE after use.
 */

if (($_GET['key'] ?? '') !== 'CSRF_FINAL_2026') {
    http_response_code(403);
    die('Forbidden.');
}

header('Content-Type: text/plain; charset=utf-8');
ob_implicit_flush(true);
if (ob_get_level()) ob_end_flush();

$home = '/home/shopwithcaug';
$laravelPath = "$home/Laravel";
$php = '/opt/alt/php84/usr/bin/php';
$artisan = "$laravelPath/artisan";
$envPath = "$laravelPath/.env";

function out($msg) {
    echo "[" . date('H:i:s') . "] $msg\n";
    flush();
}

out("=== FINAL CSRF FIX ===\n");

// 1. Fix .env — reset SESSION_DOMAIN to null and SESSION_DRIVER to database
out("1. Fixing .env session settings...");
$envContent = file_get_contents($envPath);

$replacements = [
    'SESSION_DRIVER' => 'SESSION_DRIVER=database',
    'SESSION_DOMAIN' => 'SESSION_DOMAIN=null',
    'SESSION_SECURE_COOKIE' => 'SESSION_SECURE_COOKIE=true',
    'SESSION_SAME_SITE' => 'SESSION_SAME_SITE=lax',
];

foreach ($replacements as $key => $newLine) {
    if (preg_match("/^{$key}=.*$/m", $envContent)) {
        $envContent = preg_replace("/^{$key}=.*$/m", $newLine, $envContent);
        out("   Set $newLine");
    } else {
        $envContent .= "\n$newLine";
        out("   Added $newLine");
    }
}

file_put_contents($envPath, $envContent);

// 2. Truncate sessions table (clear all stale sessions)
out("\n2. Clearing stale sessions from database...");
exec("$php $artisan tinker --execute=\"DB::table('sessions')->truncate(); echo 'Cleared ' . DB::table('sessions')->count() . ' sessions';\" 2>&1", $truncOut);
foreach ($truncOut as $line) {
    $line = trim($line);
    if ($line && !str_starts_with($line, '=') && !str_starts_with($line, '>') && !str_starts_with($line, 'In ')) {
        out("   $line");
    }
}

// 3. Full cache rebuild
out("\n3. Full cache clear and rebuild...");
$commands = [
    'config:clear', 'route:clear', 'view:clear', 'cache:clear', 'event:clear',
    'config:cache', 'route:cache', 'view:cache',
];
foreach ($commands as $cmd) {
    exec("$php $artisan $cmd 2>&1", $o);
    $last = trim(end($o));
    out("   $cmd → $last");
    $o = [];
}

// 4. Verify
out("\n4. Verification:");
exec("$php $artisan tinker --execute=\"
echo 'session.driver=' . config('session.driver') . PHP_EOL;
echo 'session.domain=' . var_export(config('session.domain'), true) . PHP_EOL;
echo 'session.secure=' . var_export(config('session.secure'), true) . PHP_EOL;
echo 'session.cookie=' . config('session.cookie') . PHP_EOL;
echo 'session.same_site=' . config('session.same_site') . PHP_EOL;
\" 2>&1", $vOut);
foreach ($vOut as $line) {
    $line = trim($line);
    if ($line && !str_starts_with($line, '=') && !str_starts_with($line, '>')) {
        out("   $line");
    }
}

out("\n=== DONE ===");
out("IMPORTANT: Clear your browser cookies for shopwithcarl.ug, then try logging in.");
out("DELETE: rm /home/shopwithcaug/public_html/fix_csrf_final.php");
