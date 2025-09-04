<?php

/**
 * Test script to reproduce the product detail image gallery navigation issue
 */

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use Illuminate\Support\Facades\Log;

echo "=== Testing Product Detail Image Gallery Issue ===\n\n";

try {
    // Find a product with multiple images
    $product = Product::with(['media', 'categories'])
        ->whereHas('media')
        ->first();

    if (!$product) {
        echo "❌ No products with media found. Creating test data...\n";

        // Create a test product with multiple images
        $product = Product::create([
            'name' => 'Test Gallery Product',
            'slug' => 'test-gallery-product',
            'description' => 'Product for testing gallery navigation',
            'short_description' => 'Test product',
            'price' => 100.00,
            'status' => 'active',
            'stock_quantity' => 10,
            'gallery_images' => [
                [
                    'url' => '/images/test1.jpg',
                    'thumb' => '/images/test1_thumb.jpg',
                    'large' => '/images/test1_large.jpg'
                ],
                [
                    'url' => '/images/test2.jpg',
                    'thumb' => '/images/test2_thumb.jpg',
                    'large' => '/images/test2_large.jpg'
                ],
                [
                    'url' => '/images/test3.jpg',
                    'thumb' => '/images/test3_thumb.jpg',
                    'large' => '/images/test3_large.jpg'
                ]
            ]
        ]);

        echo "✅ Test product created with ID: {$product->id}\n";
    }

    echo "Testing product: {$product->name} (ID: {$product->id})\n\n";

    // Test the issue: Check what data is available
    echo "1. Checking product->images property:\n";
    $productImages = $product->images ?? null;
    if ($productImages) {
        echo "   ✅ Found " . count($productImages) . " images in product->images\n";
        foreach ($productImages as $index => $image) {
            echo "   - Image {$index}: " . json_encode($image) . "\n";
        }
    } else {
        echo "   ❌ No images found in product->images property\n";
    }

    echo "\n2. Checking product->gallery_images property:\n";
    $galleryImages = $product->gallery_images ?? null;
    if ($galleryImages) {
        echo "   ✅ Found " . count($galleryImages) . " images in product->gallery_images\n";
        foreach ($galleryImages as $index => $image) {
            echo "   - Image {$index}: " . json_encode($image) . "\n";
        }
    } else {
        echo "   ❌ No images found in product->gallery_images property\n";
    }

    echo "\n3. Testing ProductDetail component getImagesProperty():\n";
    $component = new \App\Livewire\Guest\Products\ProductDetail();
    $component->product = $product;
    $componentImages = $component->getImagesProperty();
    echo "   ✅ Component returns " . count($componentImages) . " images\n";
    foreach ($componentImages as $index => $image) {
        echo "   - Image {$index}: " . json_encode($image) . "\n";
    }

    echo "\n=== Issue Analysis ===\n";
    echo "The template uses \$product->images but should use \$this->images\n";
    echo "This causes the Swiper to have no slides, making navigation arrows non-functional.\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\nTest completed.\n";
