<?php
/**
 * Fresh Deployment Script for cPanel
 *
 * This script performs a CLEAN deployment:
 *   1. Backs up .env and all user-uploaded media
 *   2. Wipes the Laravel directory and public_html (except protected files)
 *   3. Clones the repo fresh
 *   4. Restores .env and media
 *   5. Installs dependencies, runs migrations, rebuilds caches
 *
 * Usage (run via SSH on the server):
 *   /opt/alt/php84/usr/bin/php /home/shopwithcaug/public_html/fresh_deploy.php
 *
 * Or trigger via browser (one-time, remove after use):
 *   https://shopwithcarl.com/fresh_deploy.php?key=FRESH_DEPLOY_2026
 */

// ── Safety: browser trigger requires key ────────────────────────────
if (php_sapi_name() !== 'cli') {
    if (($_GET['key'] ?? '') !== 'FRESH_DEPLOY_2026') {
        http_response_code(403);
        die('Forbidden. Supply ?key= to proceed.');
    }
    // Stream output to browser
    header('Content-Type: text/plain; charset=utf-8');
    ob_implicit_flush(true);
    if (ob_get_level()) ob_end_flush();
}

// ── Configuration ───────────────────────────────────────────────────
$config = [
    'git_repo_url'    => 'https://github.com/kasbaros/Shop-With-Curl.git',
    'branch'          => 'master',
    'repo_path'       => '/home/shopwithcaug/repositories/shopwithcarl',
    'laravel_path'    => '/home/shopwithcaug/Laravel',
    'public_html_path'=> '/home/shopwithcaug/public_html',
    'php_path'        => '/opt/alt/php84/usr/bin/php',
    'backup_dir'      => '/home/shopwithcaug/fresh_deploy_backup_' . date('Ymd_His'),
];

$startTime = microtime(true);

// ── Helpers ─────────────────────────────────────────────────────────
function out($msg, $level = 'INFO') {
    $line = '[' . date('H:i:s') . "] [$level] $msg\n";
    echo $line;
    if (php_sapi_name() !== 'cli') flush();
}

function run($cmd, $desc, $allowFail = false) {
    out("  → $desc");
    out("    \$ $cmd");
    exec($cmd . ' 2>&1', $output, $rc);
    if ($rc !== 0 && !$allowFail) {
        $err = implode("\n", $output);
        out("FAILED (exit $rc): $err", 'ERROR');
        throw new RuntimeException("Command failed: $desc");
    }
    if (!empty($output)) {
        foreach (array_slice($output, 0, 20) as $line) {
            out("    $line");
        }
        if (count($output) > 20) out("    ... (" . count($output) . " lines total)");
    }
    return $output;
}

// ── Pre-flight checks ──────────────────────────────────────────────
out("========================================");
out("  FRESH DEPLOYMENT — ShopWithCarl");
out("========================================");
out("Backup dir: {$config['backup_dir']}");

foreach (['repo_path', 'laravel_path', 'public_html_path'] as $key) {
    if (!is_dir($config[$key])) {
        out("{$config[$key]} does not exist!", 'ERROR');
        die("Aborting.\n");
    }
}

// ── STEP 1: BACKUP ─────────────────────────────────────────────────
out("\n== STEP 1/8: BACKING UP USER DATA ==");

$backupDir = $config['backup_dir'];
mkdir($backupDir, 0755, true);

// 1a. Back up .env
$envSource = $config['laravel_path'] . '/.env';
if (file_exists($envSource)) {
    copy($envSource, "$backupDir/.env");
    out("Backed up .env");
} else {
    out(".env not found — will need manual config!", 'WARN');
}

// 1b. Back up composer.phar (so we don't need to re-download)
$composerSource = $config['laravel_path'] . '/composer.phar';
if (file_exists($composerSource)) {
    copy($composerSource, "$backupDir/composer.phar");
    out("Backed up composer.phar");
}

// 1c. Back up ALL user-uploaded media from storage/app/public/
$storagePublic = $config['laravel_path'] . '/storage/app/public';
if (is_dir($storagePublic)) {
    mkdir("$backupDir/storage_public", 0755, true);
    run(
        "cp -a " . escapeshellarg($storagePublic) . "/. " . escapeshellarg("$backupDir/storage_public/"),
        "Back up storage/app/public/ (product images, banners, media, etc.)"
    );

    // Show what we backed up
    $output = [];
    exec("du -sh " . escapeshellarg("$backupDir/storage_public") . " 2>/dev/null", $output);
    out("Media backup size: " . ($output[0] ?? 'unknown'));
} else {
    out("No storage/app/public/ found — no media to back up", 'WARN');
}

// 1d. Back up deployment scripts and index.php (they live in public_html)
foreach (['deploy.php', 'background_deploy.php', '.htaccess', 'index.php'] as $file) {
    $src = $config['public_html_path'] . "/$file";
    if (file_exists($src)) {
        copy($src, "$backupDir/$file");
        out("Backed up public_html/$file");
    }
}

// 1e. Back up database (PostgreSQL)
out("Attempting PostgreSQL backup...");
$envContent = file_exists($envSource) ? file_get_contents($envSource) : '';
preg_match('/^DB_DATABASE=(.+)$/m', $envContent, $mDb);
preg_match('/^DB_USERNAME=(.+)$/m', $envContent, $mUser);
preg_match('/^DB_PASSWORD=(.+)$/m', $envContent, $mPass);
preg_match('/^DB_HOST=(.+)$/m', $envContent, $mHost);
preg_match('/^DB_PORT=(.+)$/m', $envContent, $mPort);

$dbName = trim($mDb[1] ?? '');
$dbUser = trim($mUser[1] ?? '');
$dbPass = trim($mPass[1] ?? '');
$dbHost = trim($mHost[1] ?? '127.0.0.1');
$dbPort = trim($mPort[1] ?? '5432');

if ($dbName && $dbUser) {
    $dumpFile = "$backupDir/database_backup.sql";
    $pgCmd = "PGPASSWORD=" . escapeshellarg($dbPass) . " pg_dump"
        . " -h " . escapeshellarg($dbHost)
        . " -p " . escapeshellarg($dbPort)
        . " -U " . escapeshellarg($dbUser)
        . " " . escapeshellarg($dbName)
        . " > " . escapeshellarg($dumpFile);
    run($pgCmd, "Dump PostgreSQL database", true);

    if (file_exists($dumpFile) && filesize($dumpFile) > 100) {
        out("Database backup OK (" . round(filesize($dumpFile) / 1024) . " KB)");
    } else {
        out("Database dump may have failed — check manually", 'WARN');
    }
} else {
    out("Could not parse DB credentials from .env — skipping DB backup", 'WARN');
}

out("\nBackup complete at: $backupDir");

// ── STEP 2: WIPE LARAVEL DIRECTORY ─────────────────────────────────
out("\n== STEP 2/8: WIPING LARAVEL DIRECTORY ==");

// Remove everything EXCEPT .env (we'll restore from backup anyway)
$laravelPath = $config['laravel_path'];
run(
    "find " . escapeshellarg($laravelPath) . " -mindepth 1 -maxdepth 1 ! -name '.env' -exec rm -rf {} +",
    "Wipe Laravel directory (preserving .env)"
);
out("Laravel directory wiped.");

// ── STEP 3: WIPE PUBLIC_HTML (except protected) ────────────────────
out("\n== STEP 3/8: CLEANING PUBLIC_HTML ==");

$protected = ['.', '..', '.htaccess', 'index.php', 'deploy.php', 'background_deploy.php', 'fresh_deploy.php',
              'storage', '.deployment.lock', '.deployment.status', 'cgi-bin', '.well-known'];

$pubFiles = scandir($config['public_html_path']);
foreach ($pubFiles as $f) {
    if (in_array($f, $protected) || preg_match('/^deployment.*\.log$/', $f)) continue;
    $path = $config['public_html_path'] . "/$f";
    if (is_link($path)) {
        unlink($path);
        out("Removed symlink: $f");
    } elseif (is_dir($path)) {
        run("rm -rf " . escapeshellarg($path), "Remove public_html/$f");
    } else {
        unlink($path);
        out("Removed: $f");
    }
}

// ── STEP 4: FRESH GIT PULL ─────────────────────────────────────────
out("\n== STEP 4/8: FRESH GIT PULL ==");

$repoPath = $config['repo_path'];
if (is_dir("$repoPath/.git")) {
    run("cd " . escapeshellarg($repoPath) . " && git fetch origin && git reset --hard origin/{$config['branch']}",
        "Update existing repo clone");
} else {
    // Full fresh clone
    run("rm -rf " . escapeshellarg($repoPath), "Remove old repo dir");
    run("git clone --branch {$config['branch']} {$config['git_repo_url']} " . escapeshellarg($repoPath),
        "Fresh clone from GitHub");
}

$commitHash = trim(implode('', run("cd " . escapeshellarg($repoPath) . " && git rev-parse --short HEAD", "Get commit hash")));
out("Deployed commit: $commitHash");

// ── STEP 5: COPY FILES ─────────────────────────────────────────────
out("\n== STEP 5/8: COPYING FILES ==");

// 5a. Copy Laravel files (everything except public/, .git, .env)
run(
    "rsync -a --exclude='public' --exclude='.git' --exclude='.gitattributes' --exclude='.gitignore' --exclude='.env' "
    . escapeshellarg($repoPath) . "/ " . escapeshellarg($laravelPath) . "/",
    "Copy Laravel application files"
);

// 5b. Copy public/ to Laravel/public/
$repoPublic = "$repoPath/public";
if (!is_dir("$laravelPath/public")) mkdir("$laravelPath/public", 0755, true);
run(
    "rsync -a " . escapeshellarg($repoPublic) . "/ " . escapeshellarg("$laravelPath/public/"),
    "Copy public/ to Laravel/public/"
);

// 5c. Copy public/ to public_html (excluding protected files)
$pubExcludes = '--exclude=.htaccess --exclude=index.php --exclude=deploy.php --exclude=background_deploy.php '
    . '--exclude=fresh_deploy.php --exclude=storage';
run(
    "rsync -a $pubExcludes " . escapeshellarg($repoPublic) . "/ " . escapeshellarg($config['public_html_path']) . "/",
    "Copy public/ contents to public_html"
);

// ── STEP 6: RESTORE BACKUPS ────────────────────────────────────────
out("\n== STEP 6/8: RESTORING USER DATA ==");

// 6a. Restore .env
if (file_exists("$backupDir/.env") && !file_exists("$laravelPath/.env")) {
    copy("$backupDir/.env", "$laravelPath/.env");
    out("Restored .env");
} elseif (file_exists("$laravelPath/.env")) {
    out(".env already in place");
} else {
    out("NO .env AVAILABLE — app will not work until configured!", 'ERROR');
}

// 6b. Restore composer.phar
if (file_exists("$backupDir/composer.phar")) {
    copy("$backupDir/composer.phar", "$laravelPath/composer.phar");
    chmod("$laravelPath/composer.phar", 0755);
    out("Restored composer.phar");
}

// 6c. Restore user-uploaded media
$storagePublicDest = "$laravelPath/storage/app/public";
if (is_dir("$backupDir/storage_public")) {
    // Ensure target directory structure exists
    if (!is_dir($storagePublicDest)) {
        mkdir($storagePublicDest, 0755, true);
    }
    run(
        "cp -a " . escapeshellarg("$backupDir/storage_public") . "/. " . escapeshellarg($storagePublicDest) . "/",
        "Restore all user-uploaded media"
    );
    out("Media files restored.");
} else {
    out("No media backup to restore.");
}

// 6d. Restore deployment scripts and index.php to public_html
foreach (['deploy.php', 'background_deploy.php', '.htaccess', 'index.php'] as $file) {
    $src = "$backupDir/$file";
    $dest = $config['public_html_path'] . "/$file";
    if (file_exists($src) && !file_exists($dest)) {
        copy($src, $dest);
        out("Restored $file to public_html");
    }
}

// 6e. Create storage symlink
$storageLinkPath = $config['public_html_path'] . '/storage';
if (is_link($storageLinkPath)) {
    unlink($storageLinkPath);
}
if (is_dir($storageLinkPath) && !is_link($storageLinkPath)) {
    run("rm -rf " . escapeshellarg($storageLinkPath), "Remove storage directory (replacing with symlink)");
}
if (symlink($storagePublicDest, $storageLinkPath)) {
    out("Created storage symlink: public_html/storage → storage/app/public");
} else {
    out("Could not create storage symlink!", 'ERROR');
}

// ── STEP 7: INSTALL & MIGRATE ──────────────────────────────────────
out("\n== STEP 7/8: INSTALL DEPENDENCIES & MIGRATE ==");

$php = $config['php_path'];
$artisan = "$laravelPath/artisan";
$composerPhar = "$laravelPath/composer.phar";

// Ensure storage directories exist
$storageDirs = [
    'storage/framework/cache/data',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs',
    'bootstrap/cache',
];
foreach ($storageDirs as $dir) {
    $full = "$laravelPath/$dir";
    if (!is_dir($full)) {
        mkdir($full, 0775, true);
        out("Created $dir");
    }
}

// Composer install
if (!file_exists($composerPhar)) {
    out("Downloading Composer...");
    run("cd " . escapeshellarg($laravelPath) . " && curl -sS https://getcomposer.org/installer | $php", "Download Composer");
}

run(
    "cd " . escapeshellarg($laravelPath) . " && COMPOSER_HOME=/home/shopwithcaug/.composer $php $composerPhar install --no-dev --optimize-autoloader --no-interaction",
    "Composer install (production)"
);

// Run migrations (NOT fresh — preserve existing database data)
run("$php " . escapeshellarg($artisan) . " migrate --force", "Run database migrations");

// ── STEP 8: PERMISSIONS & CACHE ────────────────────────────────────
out("\n== STEP 8/8: PERMISSIONS & CACHES ==");

// Permissions
run("chmod -R 775 " . escapeshellarg("$laravelPath/storage"), "Set storage permissions", true);
run("chmod -R 775 " . escapeshellarg("$laravelPath/bootstrap/cache"), "Set bootstrap/cache permissions", true);

// Clear all caches first
$clearCmds = ['config:clear', 'route:clear', 'view:clear', 'cache:clear', 'event:clear'];
foreach ($clearCmds as $cmd) {
    run("$php " . escapeshellarg($artisan) . " $cmd", "php artisan $cmd", true);
}

// Force-purge compiled views
run("find " . escapeshellarg("$laravelPath/storage/framework/views") . " -type f -name '*.php' -delete",
    "Purge compiled views", true);

// Rebuild caches (order matters: config → route → view)
$buildCmds = ['config:cache', 'route:cache', 'view:cache'];
foreach ($buildCmds as $cmd) {
    run("$php " . escapeshellarg($artisan) . " $cmd", "php artisan $cmd", true);
}

// ── DONE ────────────────────────────────────────────────────────────
$elapsed = round(microtime(true) - $startTime, 1);

out("\n========================================");
out("  FRESH DEPLOYMENT COMPLETE");
out("  Commit: $commitHash");
out("  Time: {$elapsed}s");
out("  Backup: {$config['backup_dir']}");
out("========================================");
out("");
out("POST-DEPLOY CHECKLIST:");
out("  1. Visit the site and verify it loads");
out("  2. Check images load (product pages, banners)");
out("  3. Test login (admin + customer)");
out("  4. Verify storage symlink: ls -la {$config['public_html_path']}/storage");
out("  5. Once confirmed working, you can delete the backup:");
out("     rm -rf {$config['backup_dir']}");
out("  6. REMOVE THIS SCRIPT from public_html:");
out("     rm {$config['public_html_path']}/fresh_deploy.php");
