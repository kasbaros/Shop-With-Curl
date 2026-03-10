<?php
/**
 * Session debugging — find why 419 CSRF errors occur.
 *
 * Usage: https://shopwithcarl.ug/debug_session.php?key=DEBUG_S_2026
 * DELETE after use.
 */

if (($_GET['key'] ?? '') !== 'DEBUG_S_2026') {
    http_response_code(403);
    die('Forbidden.');
}

header('Content-Type: text/plain; charset=utf-8');

$home = '/home/shopwithcaug';
$laravelPath = "$home/Laravel";
$php = '/opt/alt/php84/usr/bin/php';
$artisan = "$laravelPath/artisan";

function out($msg) {
    echo "[" . date('H:i:s') . "] $msg\n";
}

out("=== SESSION / CSRF DEBUG ===\n");

// 1. Check session config from cached config
out("1. SESSION CONFIG (from cached config):");
exec("$php $artisan tinker --execute=\"
\\\$keys = ['driver','lifetime','expire_on_close','encrypt','files','connection','table','store','lottery','cookie','path','domain','secure','http_only','same_site','partitioned'];
foreach (\\\$keys as \\\$k) {
    \\\$v = config('session.' . \\\$k);
    echo \\\$k . '=' . var_export(\\\$v, true) . PHP_EOL;
}
\" 2>&1", $configOut);
foreach ($configOut as $line) {
    $line = trim($line);
    if ($line && !str_starts_with($line, '=') && !str_starts_with($line, '>')) {
        out("   $line");
    }
}

// 2. Test DB connection and sessions table
out("\n2. DATABASE SESSION TABLE:");
exec("$php $artisan tinker --execute=\"
try {
    \\\$table = DB::getSchemaBuilder()->getColumnListing('sessions');
    echo 'Columns: ' . implode(', ', \\\$table) . PHP_EOL;
    \\\$count = DB::table('sessions')->count();
    echo 'Rows: ' . \\\$count . PHP_EOL;

    // Try writing a test session
    DB::table('sessions')->updateOrInsert(
        ['id' => 'test_session_debug'],
        ['user_id' => null, 'ip_address' => '127.0.0.1', 'user_agent' => 'debug', 'payload' => base64_encode('test'), 'last_activity' => time()]
    );
    echo 'Write test: OK' . PHP_EOL;

    // Read it back
    \\\$row = DB::table('sessions')->where('id', 'test_session_debug')->first();
    echo 'Read test: ' . (\\\$row ? 'OK' : 'FAILED') . PHP_EOL;

    // Clean up
    DB::table('sessions')->where('id', 'test_session_debug')->delete();
    echo 'Delete test: OK' . PHP_EOL;
} catch (\\Throwable \\\$e) {
    echo 'ERROR: ' . \\\$e->getMessage() . PHP_EOL;
}
\" 2>&1", $dbOut);
foreach ($dbOut as $line) {
    $line = trim($line);
    if ($line && !str_starts_with($line, '=') && !str_starts_with($line, '>')) {
        out("   $line");
    }
}

// 3. Bootstrap Laravel and test session directly
out("\n3. LARAVEL SESSION ROUND-TRIP TEST:");
try {
    // Bootstrap Laravel
    require "$laravelPath/vendor/autoload.php";
    $app = require "$laravelPath/bootstrap/app.php";
    $kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);

    // Boot the app
    $request = \Illuminate\Http\Request::capture();
    $app->instance('request', $request);

    // Try to start session manually
    $sessionManager = $app->make('session');
    $session = $sessionManager->driver();
    $session->start();

    out("   Session ID: " . $session->getId());
    out("   Session driver: " . get_class($session->getHandler()));

    // Write a value
    $session->put('debug_test', 'hello_' . time());
    $session->save();
    out("   Write: OK (saved debug_test)");

    // Read it back
    $session->start();
    $val = $session->get('debug_test');
    out("   Read back: " . ($val ? $val : 'FAILED - empty'));

    // Generate CSRF token
    $token = $session->token();
    out("   CSRF token: " . ($token ? substr($token, 0, 20) . '...' : 'EMPTY'));

} catch (\Throwable $e) {
    out("   ERROR: " . $e->getMessage());
    out("   File: " . $e->getFile() . ":" . $e->getLine());
}

// 4. Check cookie settings being sent
out("\n4. RESPONSE HEADERS (cookie check):");
$sentHeaders = headers_list();
foreach ($sentHeaders as $h) {
    if (stripos($h, 'cookie') !== false || stripos($h, 'set-cookie') !== false) {
        out("   $h");
    }
}
if (empty(array_filter($sentHeaders, fn($h) => stripos($h, 'cookie') !== false))) {
    out("   No cookie headers being sent (expected — we're not going through middleware)");
}

// 5. Check if HTTPS is detected
out("\n5. HTTPS DETECTION:");
out("   \$_SERVER['HTTPS']: " . ($_SERVER['HTTPS'] ?? 'not set'));
out("   \$_SERVER['SERVER_PORT']: " . ($_SERVER['SERVER_PORT'] ?? 'not set'));
out("   \$_SERVER['HTTP_X_FORWARDED_PROTO']: " . ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? 'not set'));
out("   \$_SERVER['REQUEST_SCHEME']: " . ($_SERVER['REQUEST_SCHEME'] ?? 'not set'));

// 6. Check middleware stack
out("\n6. CSRF MIDDLEWARE CHECK:");
exec("$php $artisan tinker --execute=\"
\\\$middlewareGroups = app(\Illuminate\Contracts\Http\Kernel::class)->getMiddlewareGroups();
echo 'web middleware:' . PHP_EOL;
foreach (\\\$middlewareGroups['web'] ?? [] as \\\$m) {
    echo '  - ' . \\\$m . PHP_EOL;
}
\" 2>&1", $mwOut);
foreach ($mwOut as $line) {
    $line = trim($line);
    if ($line && !str_starts_with($line, '=') && !str_starts_with($line, '>')) {
        out("   $line");
    }
}

// 7. Check APP_KEY
out("\n7. APP_KEY CHECK:");
exec("$php $artisan tinker --execute=\"
\\\$key = config('app.key');
echo 'APP_KEY set: ' . (!empty(\\\$key) ? 'YES (' . strlen(\\\$key) . ' chars)' : 'NO — THIS IS THE PROBLEM') . PHP_EOL;
echo 'Cipher: ' . config('app.cipher') . PHP_EOL;
\" 2>&1", $keyOut);
foreach ($keyOut as $line) {
    $line = trim($line);
    if ($line && !str_starts_with($line, '=') && !str_starts_with($line, '>')) {
        out("   $line");
    }
}

// 8. Quick check — can we read the sessions table schema
out("\n8. SESSIONS TABLE DDL:");
exec("$php $artisan tinker --execute=\"
try {
    \\\$columns = DB::select('SHOW COLUMNS FROM sessions');
    foreach (\\\$columns as \\\$col) {
        echo \\\$col->Field . ' | ' . \\\$col->Type . ' | ' . \\\$col->Null . ' | ' . \\\$col->Key . PHP_EOL;
    }
} catch (\\Throwable \\\$e) {
    echo 'ERROR: ' . \\\$e->getMessage() . PHP_EOL;
}
\" 2>&1", $ddlOut);
foreach ($ddlOut as $line) {
    $line = trim($line);
    if ($line && !str_starts_with($line, '=') && !str_starts_with($line, '>')) {
        out("   $line");
    }
}

out("\n=== DONE ===");
out("DELETE: rm /home/shopwithcaug/public_html/debug_session.php");
