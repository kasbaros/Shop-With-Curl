<?php
/**
 * Emergency path fix for cPanel deployment.
 *
 * Usage: https://shopwithcarl.ug/fix_paths.php?key=FIX_2026
 * DELETE after use.
 */

if (($_GET['key'] ?? '') !== 'FIX_2026') {
    http_response_code(403);
    die('Forbidden.');
}

header('Content-Type: text/plain; charset=utf-8');
ob_implicit_flush(true);
if (ob_get_level()) ob_end_flush();

$home = '/home/shopwithcaug';
$laravelPath = "$home/Laravel";
$publicHtml = "$home/public_html";
$php = '/opt/alt/php84/usr/bin/php';

function out($msg) {
    echo "[" . date('H:i:s') . "] $msg\n";
    flush();
}

out("=== EMERGENCY PATH FIX ===\n");

// ── 1. Fix index.php ────────────────────────────────────────────────
out("1. Fixing index.php paths...");

$indexContent = <<<'PHP'
<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| cPanel path override
|--------------------------------------------------------------------------
| public_html is NOT inside the Laravel directory on this host.
| Laravel lives at /home/shopwithcaug/Laravel
*/
$laravelBase = __DIR__ . '/../Laravel';

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

$indexPath = "$publicHtml/index.php";
$backup = "$publicHtml/index.php.bak." . date('His');
if (file_exists($indexPath)) {
    copy($indexPath, $backup);
    out("   Backed up old index.php to $backup");
}

file_put_contents($indexPath, $indexContent);
out("   Written new index.php with correct Laravel path");

// ── 2. Create storage/app/public directory ──────────────────────────
out("\n2. Creating storage/app/public...");

$storagePublic = "$laravelPath/storage/app/public";
if (!is_dir($storagePublic)) {
    if (mkdir($storagePublic, 0775, true)) {
        out("   Created $storagePublic");
    } else {
        out("   ERROR: Could not create $storagePublic");
    }
} else {
    out("   Already exists");
}

// ── 3. Fix storage symlink ──────────────────────────────────────────
out("\n3. Fixing storage symlink...");

$storageLink = "$publicHtml/storage";
if (is_link($storageLink)) {
    $target = readlink($storageLink);
    out("   Current symlink: $storageLink → $target");
    if (!is_dir($target)) {
        out("   Target doesn't exist! Recreating...");
        unlink($storageLink);
        symlink($storagePublic, $storageLink);
        out("   Recreated: $storageLink → $storagePublic");
    } else {
        out("   Target exists, symlink is OK");
    }
} elseif (is_dir($storageLink)) {
    out("   storage is a real directory, replacing with symlink...");
    rename($storageLink, "$storageLink.bak." . date('His'));
    symlink($storagePublic, $storageLink);
    out("   Created symlink: $storageLink → $storagePublic");
} else {
    symlink($storagePublic, $storageLink);
    out("   Created symlink: $storageLink → $storagePublic");
}

// ── 4. Create missing storage subdirectories ────────────────────────
out("\n4. Ensuring storage subdirectories exist...");

$dirs = [
    'storage/app/public/products',
    'storage/app/public/media',
    'storage/app/public/banners',
    'storage/app/public/categories',
    'storage/app/public/gallery',
    'storage/app/public/promo-banners',
    'storage/app/public/lookbooks',
    'storage/app/public/variant_images',
    'storage/framework/cache/data',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs',
];

foreach ($dirs as $dir) {
    $full = "$laravelPath/$dir";
    if (!is_dir($full)) {
        mkdir($full, 0775, true);
        out("   Created $dir");
    }
}

// ── 5. Fix route cache ──────────────────────────────────────────────
out("\n5. Rebuilding route cache...");
exec("$php $laravelPath/artisan route:clear 2>&1", $o1);
out("   " . implode("\n   ", $o1));
exec("$php $laravelPath/artisan route:cache 2>&1", $o2);
out("   " . implode("\n   ", $o2));

// ── 6. Clear and rebuild all caches ─────────────────────────────────
out("\n6. Clearing and rebuilding caches...");
$cmds = ['config:clear', 'view:clear', 'cache:clear', 'event:clear', 'config:cache', 'view:cache'];
foreach ($cmds as $cmd) {
    exec("$php $laravelPath/artisan $cmd 2>&1", $out);
    out("   $cmd: " . trim(end($out)));
    $out = [];
}

// ── 7. Set permissions ──────────────────────────────────────────────
out("\n7. Setting permissions...");
exec("chmod -R 775 $laravelPath/storage 2>&1");
exec("chmod -R 775 $laravelPath/bootstrap/cache 2>&1");
out("   Done");

// ── 8. Verify ───────────────────────────────────────────────────────
out("\n8. Verification...");

// Test the index.php can find autoload
$autoload = "$laravelPath/vendor/autoload.php";
out("   vendor/autoload.php: " . (file_exists($autoload) ? 'EXISTS' : 'MISSING'));

$bootstrap = "$laravelPath/bootstrap/app.php";
out("   bootstrap/app.php: " . (file_exists($bootstrap) ? 'EXISTS' : 'MISSING'));

out("   storage/app/public: " . (is_dir($storagePublic) ? 'EXISTS' : 'MISSING'));

$link = "$publicHtml/storage";
if (is_link($link)) {
    $t = readlink($link);
    out("   public_html/storage → $t (target exists: " . (is_dir($t) ? 'YES' : 'NO') . ")");
}

$routeCache = "$laravelPath/bootstrap/cache/routes-v7.php";
out("   routes-v7.php: " . (file_exists($routeCache) ? 'EXISTS' : 'MISSING'));

out("\n=== FIX COMPLETE ===");
out("Try loading https://shopwithcarl.ug now");
out("\nDELETE THIS FILE: rm $publicHtml/fix_paths.php");
