<?php
/**
 * Emergency cache clear for production.
 * Hit: https://shopwithcarl.ug/clear-cache.php?secret=YOUR_SECRET
 * Delete this file after use.
 */

$secret = 'Thi5K3y15Un8eL13vabL363R10Us11Y=';

if (!isset($_GET['secret']) || $_GET['secret'] !== $secret) {
    http_response_code(403);
    die('Forbidden');
}

$php = '/opt/alt/php84/usr/bin/php';
$artisan = '/home/shopwithcaug/Laravel/artisan';
$viewsDir = '/home/shopwithcaug/Laravel/storage/framework/views';

header('Content-Type: text/plain');

$commands = [
    "$php $artisan config:clear"  => 'config:clear',
    "$php $artisan route:clear"   => 'route:clear',
    "$php $artisan view:clear"    => 'view:clear',
    "$php $artisan cache:clear"   => 'cache:clear',
    "$php $artisan event:clear"   => 'event:clear',
];

foreach ($commands as $cmd => $label) {
    echo "=== $label ===\n";
    exec("$cmd 2>&1", $output, $code);
    echo implode("\n", $output) . "\n(exit: $code)\n\n";
    $output = [];
}

// Force-purge compiled views
echo "=== purge compiled views ===\n";
$files = glob("$viewsDir/*.php");
$count = 0;
foreach ($files as $f) {
    if (unlink($f)) $count++;
}
echo "Deleted $count compiled view files\n\n";

echo "DONE. All caches cleared.\n";
echo "Now rebuild caches:\n";

$rebuild = [
    "$php $artisan config:cache" => 'config:cache',
    "$php $artisan route:cache"  => 'route:cache',
    "$php $artisan view:cache"   => 'view:cache',
];

foreach ($rebuild as $cmd => $label) {
    echo "=== $label ===\n";
    exec("$cmd 2>&1", $output, $code);
    echo implode("\n", $output) . "\n(exit: $code)\n\n";
    $output = [];
}

echo "ALL DONE. Site should be working now.\n";
