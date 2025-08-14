<?php
// This is a simple script to verify that our fix worked
// We would typically check by loading the component in a browser
// For demonstration purposes, we'll just test that the component class exists and has the necessary methods

require_once __DIR__ . '/vendor/autoload.php';

use App\Livewire\Guest\Products\ProductReviews;

// Check if the class exists
echo "Checking if ProductReviews class exists...\n";
if (class_exists(ProductReviews::class)) {
    echo "✓ ProductReviews class exists\n";
} else {
    echo "❌ ProductReviews class does not exist\n";
    exit(1);
}

// Check if the getRatingsSummaryProperty method exists
echo "Checking if getRatingsSummaryProperty method exists...\n";
if (method_exists(ProductReviews::class, 'getRatingsSummaryProperty')) {
    echo "✓ getRatingsSummaryProperty method exists\n";
} else {
    echo "❌ getRatingsSummaryProperty method does not exist\n";
    exit(1);
}

// Check if the render method returns the correct view
echo "Checking if render method returns the correct view...\n";
$reflectionMethod = new ReflectionMethod(ProductReviews::class, 'render');
$method = $reflectionMethod->getFileName();
$startLine = $reflectionMethod->getStartLine();
$endLine = $reflectionMethod->getEndLine();
$file = new SplFileObject($method);
$methodCode = '';
for ($i = $startLine; $i < $endLine; $i++) {
    $file->seek($i - 1);
    $methodCode .= $file->current();
}

if (strpos($methodCode, "view('livewire.guest.products.product-reviews')") !== false) {
    echo "✓ render method returns the correct view\n";
} else {
    echo "❌ render method does not return the correct view\n";
    exit(1);
}

echo "\nAll checks passed! The fix should resolve the issue.\n";
