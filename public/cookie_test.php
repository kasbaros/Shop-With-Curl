<?php
/**
 * Raw cookie test — no Laravel, no middleware.
 * If this fails, the problem is LiteSpeed/server, not Laravel.
 *
 * https://shopwithcarl.ug/cookie_test.php?key=COOKIE_2026
 */
if (($_GET['key'] ?? '') !== 'COOKIE_2026') {
    http_response_code(403);
    die('Forbidden.');
}

header('Content-Type: text/html; charset=utf-8');
header('Cache-Control: no-cache, no-store, must-revalidate, private');
header('X-LiteSpeed-Cache-Control: no-cache');
header('Pragma: no-cache');

echo "<h2>Raw Cookie Test (No Laravel)</h2>";

// Check if our test cookie came back
if (isset($_COOKIE['raw_test_cookie'])) {
    echo "<p style='color:green;font-size:20px;font-weight:bold;'>&#10004; SUCCESS — Cookie persisted!</p>";
    echo "<p>Cookie value: " . htmlspecialchars($_COOKIE['raw_test_cookie']) . "</p>";
    echo "<p>This means cookies work at the server level. The problem is in Laravel's middleware.</p>";
} else {
    echo "<p style='color:red;font-size:20px;font-weight:bold;'>&#10008; Cookie NOT received back.</p>";
    echo "<p>Setting a test cookie now. <b>Reload this page</b> to see if it persists.</p>";
}

// Always set/refresh the cookie
$val = 'test_' . time();
setcookie('raw_test_cookie', $val, [
    'expires' => time() + 3600,
    'path' => '/',
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Lax',
]);

echo "<hr>";
echo "<h3>Response Headers Being Sent:</h3><pre>";
foreach (headers_list() as $h) {
    echo htmlspecialchars($h) . "\n";
}
echo "</pre>";

echo "<h3>Cookies Received From Browser:</h3><pre>";
if (empty($_COOKIE)) {
    echo "(none)\n";
} else {
    foreach ($_COOKIE as $k => $v) {
        echo htmlspecialchars("$k = " . substr($v, 0, 80)) . "\n";
    }
}
echo "</pre>";

echo "<h3>Relevant Server Variables:</h3><pre>";
$keys = ['HTTPS', 'SERVER_PORT', 'HTTP_HOST', 'REQUEST_SCHEME', 'SERVER_SOFTWARE'];
foreach ($keys as $k) {
    echo "$k = " . ($_SERVER[$k] ?? 'not set') . "\n";
}
echo "</pre>";

echo "<p><a href='?key=COOKIE_2026'>Reload to test cookie persistence</a></p>";
echo "<p><small>DELETE this file when done.</small></p>";
