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
| On cPanel, public_html and Laravel are siblings under the home directory,
| so standard ../vendor paths don't work. Detect and use the correct base.
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



