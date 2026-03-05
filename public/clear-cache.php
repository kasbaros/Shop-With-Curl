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

    // Git status - check both repo and Laravel dir
    $repoDir = '/home/shopwithcaug/repositories/shopwithcarl';

    echo "=== Git HEAD (Laravel dir: $laravelDir) ===\n";
    exec("cd $laravelDir && git log --oneline -5 2>&1", $out, $c);
    echo implode("\n", $out) . "\n\n";
    $out = [];

    echo "=== Git HEAD (Repo dir: $repoDir) ===\n";
    if (is_dir($repoDir)) {
        exec("cd $repoDir && git log --oneline -5 2>&1", $out, $c);
        echo implode("\n", $out) . "\n";
    } else {
        echo "Directory does NOT exist!\n";
    }
    echo "\n";
    $out = [];

    // Check deploy scripts
    echo "=== Deploy Scripts ===\n";
    $deployFiles = ['deploy.php', 'background_deploy.php'];
    foreach ($deployFiles as $df) {
        $path = "/home/shopwithcaug/public_html/$df";
        echo "$df: " . (file_exists($path) ? 'EXISTS (' . filesize($path) . ' bytes)' : 'MISSING') . "\n";
    }
    echo "\n";

    // Check if key recent files exist in Laravel dir
    echo "=== Recent Files Check ===\n";
    $checkFiles = [
        'resources/views/components/app-layout.blade.php',
        'resources/views/livewire/guest/shop/shop-grid.blade.php',
        'resources/views/guest/home.blade.php',
        'app/Livewire/Components/ProductCompare.php',
    ];
    foreach ($checkFiles as $cf) {
        $full = "$laravelDir/$cf";
        if (file_exists($full)) {
            echo "$cf: " . date('Y-m-d H:i:s', filemtime($full)) . "\n";
        } else {
            echo "$cf: MISSING\n";
        }
    }
    echo "\n";

    // Check for canvasForgotForm in app-layout (proves our latest code is deployed)
    echo "=== Code Freshness Check ===\n";
    $layoutContent = file_get_contents("$laravelDir/resources/views/components/app-layout.blade.php");
    echo "Has canvasForgotForm (forgot-password pane): " . (str_contains($layoutContent, 'canvasForgotForm') ? 'YES' : 'NO') . "\n";
    echo "Has canvasRegisterForm (register pane): " . (str_contains($layoutContent, 'canvasRegisterForm') ? 'YES' : 'NO') . "\n";
    $homeContent = file_get_contents("$laravelDir/resources/views/guest/home.blade.php");
    echo "Has scoped swiper fix (.tf-slideshow .swiper-wrapper): " . (str_contains($homeContent, '.tf-slideshow .swiper-wrapper') ? 'YES' : 'NO') . "\n";
    echo "\n";

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

// ----- OPCACHE RESET -----
echo "=== OPcache Reset ===\n";
if (function_exists('opcache_reset')) {
    opcache_reset();
    echo "OPcache cleared successfully.\n";
} else {
    echo "OPcache not available (extension not loaded).\n";
}
if (function_exists('opcache_get_status')) {
    $status = opcache_get_status(false);
    if ($status) {
        echo "OPcache enabled: " . ($status['opcache_enabled'] ? 'YES' : 'NO') . "\n";
        echo "Cached scripts: " . ($status['opcache_statistics']['num_cached_scripts'] ?? 0) . "\n";
    }
}
echo "\n";

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

// Force-delete bootstrap cache files
echo "=== purge bootstrap cache ===\n";
$bootstrapCache = "$laravelDir/bootstrap/cache";
$cacheFiles = ['config.php', 'routes-v7.php', 'services.php', 'packages.php', 'events.php'];
foreach ($cacheFiles as $cf) {
    $path = "$bootstrapCache/$cf";
    if (file_exists($path)) {
        unlink($path);
        echo "Deleted: $cf\n";
    }
}
echo "\n";

echo "DONE. All caches cleared (including OPcache + bootstrap).\n";
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
