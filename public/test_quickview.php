<?php

// Simple test script to check if the ProductQuickView component works after our changes
// To run: Visit http://127.0.0.1:8000/test_quickview.php in your browser

// Bootstrap the Laravel application
require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

// Test creating the ProductQuickView component
try {
    $component = new App\Livewire\Components\ProductQuickView();
    echo "SUCCESS: ProductQuickView component created successfully!";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
