<?php
if (($_GET['key'] ?? '') !== 'COOKIELOG_2026') { http_response_code(403); die('Forbidden.'); }
header('Content-Type: text/plain');
$log = '/home/shopwithcaug/Laravel/storage/logs/cookie_debug.log';
if (file_exists($log)) {
    echo file_get_contents($log);
    // Clear after reading
    if (isset($_GET['clear'])) { file_put_contents($log, ''); echo "\n--- LOG CLEARED ---"; }
} else {
    echo "No cookie_debug.log found yet. Visit /session-test first.";
}
