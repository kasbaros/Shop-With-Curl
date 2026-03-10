<?php
/**
 * Fix 419 CSRF/session issues on production.
 *
 * Usage: https://shopwithcarl.ug/fix_session.php?key=FIX_SESSION_2026
 * DELETE after use.
 */

if (($_GET['key'] ?? '') !== 'FIX_SESSION_2026') {
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

function out($msg) {
    echo "[" . date('H:i:s') . "] $msg\n";
    flush();
}

out("=== FIX 419 / SESSION ISSUES ===\n");

// 1. Check current .env session/domain config
out("1. Current .env session settings:");
$envPath = "$laravelPath/.env";
$envContent = file_get_contents($envPath);
$sessionLines = [];
foreach (explode("\n", $envContent) as $line) {
    if (preg_match('/^(SESSION_|SANCTUM_|APP_URL)/', $line)) {
        $sessionLines[] = $line;
    }
}
echo implode("\n", $sessionLines) . "\n\n";

// 2. Fix SESSION_DOMAIN
out("2. Fixing SESSION_DOMAIN and SANCTUM_STATEFUL_DOMAINS...");

$fixes = [
    'SESSION_DOMAIN' => [
        'from' => '/^SESSION_DOMAIN=.*$/m',
        'to' => 'SESSION_DOMAIN=.shopwithcarl.ug',
    ],
    'SANCTUM_STATEFUL_DOMAINS' => [
        'from' => '/^SANCTUM_STATEFUL_DOMAINS=.*$/m',
        'to' => 'SANCTUM_STATEFUL_DOMAINS=shopwithcarl.ug,www.shopwithcarl.ug',
    ],
];

foreach ($fixes as $key => $fix) {
    if (preg_match($fix['from'], $envContent)) {
        $envContent = preg_replace($fix['from'], $fix['to'], $envContent);
        out("   Updated $key → {$fix['to']}");
    } else {
        $envContent .= "\n{$fix['to']}";
        out("   Added {$fix['to']}");
    }
}

// Ensure SESSION_SAME_SITE is lax (not strict)
if (preg_match('/^SESSION_SAME_SITE=.*$/m', $envContent)) {
    $envContent = preg_replace('/^SESSION_SAME_SITE=.*$/m', 'SESSION_SAME_SITE=lax', $envContent);
}

file_put_contents($envPath, $envContent);
out("   .env saved");

// 3. Check database sessions table
out("\n3. Checking sessions table...");
exec("$php $artisan tinker --execute=\"try { \$count = DB::table('sessions')->count(); echo 'Sessions table OK: ' . \$count . ' rows'; } catch (\\Throwable \$e) { echo 'ERROR: ' . \$e->getMessage(); }\" 2>&1", $dbOut);
echo "   " . implode("\n   ", $dbOut) . "\n";

// 4. Clear old sessions
out("\n4. Clearing old sessions...");
exec("$php $artisan tinker --execute=\"try { DB::table('sessions')->truncate(); echo 'Sessions cleared'; } catch (\\Throwable \$e) { echo 'ERROR: ' . \$e->getMessage(); }\" 2>&1", $clearOut);
echo "   " . implode("\n   ", $clearOut) . "\n";

// 5. Rebuild config cache (picks up new .env values)
out("\n5. Rebuilding config cache...");
exec("$php $artisan config:clear 2>&1", $o1);
out("   " . trim(implode(' ', $o1)));
exec("$php $artisan config:cache 2>&1", $o2);
out("   " . trim(implode(' ', $o2)));

// 6. Clear route and view caches too
exec("$php $artisan route:clear 2>&1");
exec("$php $artisan route:cache 2>&1", $o3);
out("   " . trim(implode(' ', $o3)));

// 7. Verify new config
out("\n6. Verifying new config...");
exec("$php $artisan tinker --execute=\"echo 'session.domain=' . config('session.domain') . PHP_EOL . 'session.driver=' . config('session.driver') . PHP_EOL . 'session.same_site=' . config('session.same_site') . PHP_EOL . 'session.secure=' . (config('session.secure') ? 'true' : 'false') . PHP_EOL . 'session.http_only=' . (config('session.http_only') ? 'true' : 'false') . PHP_EOL;\" 2>&1", $verifyOut);
echo "   " . implode("\n   ", $verifyOut) . "\n";

out("\n=== DONE ===");
out("Try logging in again at https://shopwithcarl.ug/login");
out("DELETE THIS FILE: rm /home/shopwithcaug/public_html/fix_session.php");
