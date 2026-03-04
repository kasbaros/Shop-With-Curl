<?php
/**
 * Emergency cache clear + diagnostics for production.
 * Hit: https://shopwithcarl.ug/clear-cache.php?secret=YOUR_SECRET
 * Add &diag=1 for diagnostics only (no cache changes).
 * Delete this file after use.
 */

$secret = 'Thi5K3y15Un8eL13vabL363R10Us11Y=';

if (!isset($_GET['secret']) || $_GET['secret'] !== $secret) {
    http_response_code(403);
    die('Forbidden');
}

$php = '/opt/alt/php84/usr/bin/php';
$artisan = '/home/shopwithcaug/Laravel/artisan';
$laravelDir = '/home/shopwithcaug/Laravel';
$viewsDir = "$laravelDir/storage/framework/views";

header('Content-Type: text/plain');

// ----- REHASH ADMIN PASSWORD -----
if (isset($_GET['rehash'])) {
    $email = $_GET['rehash'];
    echo "=== Rehash password for: $email ===\n\n";

    // Boot Laravel
    require "$laravelDir/vendor/autoload.php";
    $app = require "$laravelDir/bootstrap/app.php";
    $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

    $user = \App\Models\User::where('email', $email)->first();
    if (!$user) {
        echo "ERROR: User not found.\n";
        exit;
    }

    echo "Current hash: " . substr($user->password, 0, 10) . "...\n";
    echo "Hash algo: " . (str_starts_with($user->password, '$2y$') ? 'bcrypt' :
        (str_starts_with($user->password, '$argon2') ? 'argon2' : 'unknown')) . "\n\n";

    if (isset($_GET['newpass'])) {
        $newPass = $_GET['newpass'];
        $user->password = \Illuminate\Support\Facades\Hash::make($newPass);
        $user->save();
        echo "Password updated with bcrypt hash.\n";
        echo "New hash: " . substr($user->password, 0, 10) . "...\n";
        echo "DONE. You can now log in.\n";
    } else {
        echo "To reset, add &newpass=YOUR_NEW_PASSWORD to the URL.\n";
        echo "Example: ...&rehash=$email&newpass=MyNewPass123\n";
    }
    exit;
}

// ----- DIAGNOSTICS -----
if (isset($_GET['diag'])) {
    echo "========== DIAGNOSTICS ==========\n\n";

    // PHP version
    echo "=== PHP Version ===\n";
    exec("$php -v 2>&1", $out, $c);
    echo implode("\n", $out) . "\n\n";
    $out = [];

    // Git status
    echo "=== Git HEAD ===\n";
    exec("cd $laravelDir && git log --oneline -5 2>&1", $out, $c);
    echo implode("\n", $out) . "\n\n";
    $out = [];

    // .env key checks
    echo "=== .env Key Settings ===\n";
    $env = parse_ini_file("$laravelDir/.env");
    $keys = ['APP_ENV', 'APP_DEBUG', 'APP_URL', 'DB_CONNECTION', 'SESSION_DRIVER', 'CACHE_STORE', 'QUEUE_CONNECTION'];
    foreach ($keys as $k) {
        echo "$k = " . ($env[$k] ?? '(not set)') . "\n";
    }
    echo "\n";

    // Check DB connection
    echo "=== Database Connection ===\n";
    exec("$php $artisan migrate:status --no-ansi 2>&1 | head -5", $out, $c);
    echo implode("\n", $out) . "\n(exit: $c)\n\n";
    $out = [];

    // Check sessions table
    echo "=== Sessions Table Check ===\n";
    exec("$php $artisan migrate:status --no-ansi 2>&1 | grep -i session", $out, $c);
    echo (empty($out) ? "(no session migration found)" : implode("\n", $out)) . "\n\n";
    $out = [];

    // Check cached config
    echo "=== Config Cache Status ===\n";
    $configCached = file_exists("$laravelDir/bootstrap/cache/config.php");
    echo "config.php cached: " . ($configCached ? "YES" : "NO") . "\n";
    if ($configCached) {
        $cachedConfig = include "$laravelDir/bootstrap/cache/config.php";
        echo "Cached APP_URL: " . ($cachedConfig['app']['url'] ?? '?') . "\n";
        echo "Cached SESSION_DRIVER: " . ($cachedConfig['session']['driver'] ?? '?') . "\n";
        echo "Cached FORTIFY_HOME: " . ($cachedConfig['fortify']['home'] ?? '?') . "\n";
    }
    echo "\n";

    // Check route cache
    echo "=== Route Cache Status ===\n";
    $routeCached = file_exists("$laravelDir/bootstrap/cache/routes-v7.php");
    echo "routes cached: " . ($routeCached ? "YES" : "NO") . "\n\n";

    // Check Livewire
    echo "=== Livewire Check ===\n";
    exec("$php $artisan route:list --name=livewire --no-ansi 2>&1 | head -5", $out, $c);
    echo implode("\n", $out) . "\n\n";
    $out = [];

    // Check login route
    echo "=== Login Routes ===\n";
    exec("$php $artisan route:list --name=login --no-ansi 2>&1", $out, $c);
    echo implode("\n", $out) . "\n\n";
    $out = [];

    // Check storage permissions
    echo "=== Storage Permissions ===\n";
    $dirs = ['storage/logs', 'storage/framework/sessions', 'storage/framework/views', 'storage/framework/cache'];
    foreach ($dirs as $d) {
        $full = "$laravelDir/$d";
        $writable = is_writable($full) ? 'writable' : 'NOT WRITABLE';
        echo "$d: $writable\n";
    }
    echo "\n";

    // Check Vite manifest
    echo "=== Vite Build ===\n";
    $manifest = "$laravelDir/public/build/manifest.json";
    if (file_exists($manifest)) {
        $m = json_decode(file_get_contents($manifest), true);
        echo "Manifest entries: " . count($m) . "\n";
        foreach ($m as $k => $v) {
            echo "  $k => " . ($v['file'] ?? '?') . "\n";
        }
    } else {
        echo "MISSING - Vite manifest not found!\n";
    }
    echo "\n";

    // Check bootstrap/app.php for the isSecurityException fix
    echo "=== bootstrap/app.php Check ===\n";
    $bootstrap = file_get_contents("$laravelDir/bootstrap/app.php");
    if (str_contains($bootstrap, "function isSecurityException")) {
        $pos = strpos($bootstrap, "function isSecurityException");
        $returnPos = strpos($bootstrap, "return Application::configure");
        echo "isSecurityException defined at char $pos\n";
        echo "return statement at char $returnPos\n";
        echo ($pos < $returnPos) ? "OK - function is BEFORE return\n" : "BUG - function is AFTER return!\n";
    } else {
        echo "isSecurityException function NOT FOUND\n";
    }
    echo "\n";

    echo "========== END DIAGNOSTICS ==========\n";
    exit;
}

// ----- CACHE CLEAR + REBUILD -----
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
echo "Now rebuild caches:\n\n";

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
