<?php
// This is a simple script to verify that our fix worked
// We would typically check by loading the component in a browser
// For demonstration purposes, we'll just test that the component class exists and has the necessary properties

require_once __DIR__ . '/vendor/autoload.php';

use App\Livewire\Guest\Products\ProductDetail;

// Check if the class exists
echo "Checking if ProductDetail class exists...\n";
if (class_exists(ProductDetail::class)) {
    echo "✓ ProductDetail class exists\n";
} else {
    echo "❌ ProductDetail class does not exist\n";
    exit(1);
}

// Check if the showSizeChart property exists
echo "Checking if showSizeChart property exists...\n";
$reflection = new ReflectionClass(ProductDetail::class);
$hasProperty = $reflection->hasProperty('showSizeChart');
if ($hasProperty) {
    echo "✓ showSizeChart property exists\n";
} else {
    echo "❌ showSizeChart property does not exist\n";
    exit(1);
}

// Check if the toggleSizeChart method exists
echo "Checking if toggleSizeChart method exists...\n";
if (method_exists(ProductDetail::class, 'toggleSizeChart')) {
    echo "✓ toggleSizeChart method exists\n";
} else {
    echo "❌ toggleSizeChart method does not exist\n";
    exit(1);
}

// Check if the render method returns the correct view
echo "Checking if render method returns the correct view...\n";
$reflectionMethod = new ReflectionMethod(ProductDetail::class, 'render');
$method = $reflectionMethod->getFileName();
$startLine = $reflectionMethod->getStartLine();
$endLine = $reflectionMethod->getEndLine();
$file = new SplFileObject($method);
$methodCode = '';
for ($i = $startLine; $i < $endLine; $i++) {
    $file->seek($i - 1);
    $methodCode .= $file->current();
}

if (strpos($methodCode, "view('livewire.guest.products.product-detail')") !== false) {
    echo "✓ render method returns the correct view\n";
} else {
    echo "❌ render method does not return the correct view\n";
    exit(1);
}

echo "\nAll checks passed! The fix should resolve the issue.\n";
