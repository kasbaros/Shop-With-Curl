<?php
/**
 * Fix output buffering in index.php to prevent premature header flushing.
 *
 * https://shopwithcarl.ug/fix_ob.php?key=FIXOB_2026
 */
if (($_GET['key'] ?? '') !== 'FIXOB_2026') { http_response_code(403); die('Forbidden.'); }

header('Content-Type: text/plain; charset=utf-8');

$indexPath = '/home/shopwithcaug/public_html/index.php';
$php = '/opt/alt/php84/usr/bin/php';
$artisan = '/home/shopwithcaug/Laravel/artisan';

echo "=== FIX OUTPUT BUFFERING ===\n\n";

// 1. Check current output_buffering setting
echo "1. PHP output_buffering setting: " . ini_get('output_buffering') . "\n";
echo "   (If '0' or empty, output buffering is OFF — this is the problem)\n\n";

// 2. Rewrite index.php with ob_start()
echo "2. Rewriting index.php with ob_start()...\n";

$newIndex = <<<'PHP'
<?php

// Enable output buffering to prevent premature header flushing on LiteSpeed.
// Without this, any stray output (warnings, BOM, whitespace) flushes headers
// before Laravel can set session cookies, causing 419 CSRF errors.
ob_start();

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| cPanel path detection
|--------------------------------------------------------------------------
| public_html and Laravel are siblings on cPanel, not parent/child.
*/
$laravelBase = is_dir(__DIR__ . '/../Laravel') ? __DIR__ . '/../Laravel' : __DIR__ . '/..';

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = $laravelBase . '/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require $laravelBase . '/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once $laravelBase . '/bootstrap/app.php';

$app->handleRequest(Request::capture());
PHP;

// Backup current
copy($indexPath, $indexPath . '.bak');
file_put_contents($indexPath, $newIndex);
echo "   Done. Backup saved as index.php.bak\n\n";

// 3. Also create a .user.ini to enable output buffering server-wide
$userIni = '/home/shopwithcaug/public_html/.user.ini';
$iniContent = "; Enable output buffering for LiteSpeed/cPanel\noutput_buffering = 4096\n";
file_put_contents($userIni, $iniContent);
echo "3. Created .user.ini with output_buffering = 4096\n\n";

// 4. Clear caches
echo "4. Clearing caches...\n";
$cmds = ['config:clear', 'config:cache', 'route:clear', 'route:cache'];
foreach ($cmds as $cmd) {
    exec("$php $artisan $cmd 2>&1", $o);
    echo "   $cmd: " . trim(end($o)) . "\n";
    $o = [];
}

echo "\n=== DONE ===\n";
echo "Clear browser cookies for shopwithcarl.ug, then try logging in.\n";
echo "DELETE: rm /home/shopwithcaug/public_html/fix_ob.php\n";
