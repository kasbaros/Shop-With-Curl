<?php

// Simple script to test route generation
// Run with: php artisan tinker --execute="require 'test_route.php';"

$galleryId = 1;
$routeUrl = route('admin.gallery.update', ['gallery' => $galleryId]);
echo "Generated URL: " . $routeUrl . "\n";
