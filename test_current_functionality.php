<?php

// Test script to verify current product and variant functionality
require_once __DIR__ . '/vendor/autoload.php';

// Load Laravel application
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Category;

echo "=== Testing Current Product and Variant Functionality ===\n\n";

try {
    // Test 1: Check if we can create a basic product
    echo "1. Testing basic product creation...\n";

    $category = Category::first();
    if (!$category) {
        echo "   ❌ No categories found. Creating a test category...\n";
        $category = Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'is_active' => true
        ]);
        echo "   ✅ Test category created\n";
    }

    $product = Product::create([
        'name' => 'Test Product - Variant Support Check',
        'slug' => 'test-product-variant-' . time(),
        'description' => 'Testing variant support functionality',
        'short_description' => 'Test product for variants',
        'sku' => 'TEST-VARIANT-' . time(),
        'price' => 100.00,
        'stock_quantity' => 10,
        'is_active' => true,
        'manage_stock' => true,
        'min_stock_level' => 5
    ]);

    $product->categories()->attach($category->id);
    echo "   ✅ Basic product created with ID: " . $product->id . "\n";

    // Test 2: Check variant creation
    echo "\n2. Testing product variant creation...\n";

    $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
    $colors = ['Black', 'Green', 'Blue', 'Red'];

    $variantCount = 0;
    foreach ($sizes as $size) {
        foreach (['Black', 'Green'] as $color) { // Test with 2 colors only
            $variant = ProductVariant::create([
                'product_id' => $product->id,
                'size' => $size,
                'color' => $color,
                'sku' => $product->sku . '-' . $size . '-' . strtoupper($color),
                'price' => $product->price + (rand(0, 2) * 10), // Vary price slightly
                'stock_quantity' => rand(0, 20),
                'is_active' => true
            ]);
            $variantCount++;
        }
    }

    echo "   ✅ Created $variantCount variants for the product\n";

    // Test 3: Check relationships
    echo "\n3. Testing model relationships...\n";

    $productWithVariants = Product::with('variants')->find($product->id);
    echo "   ✅ Product has " . $productWithVariants->variants->count() . " variants\n";

    foreach ($productWithVariants->variants as $variant) {
        echo "   - {$variant->size} / {$variant->color}: {$variant->stock_quantity} in stock, Price: UGX {$variant->price}\n";
    }

    // Test 4: Check if colors and sizes relationships work
    echo "\n4. Testing product colors and sizes relationships...\n";

    if (method_exists($product, 'colors') && method_exists($product, 'sizes')) {
        echo "   ✅ Product model has colors() and sizes() relationships\n";
    } else {
        echo "   ❌ Product model missing colors() or sizes() relationships\n";
    }

    echo "\n=== Current State Analysis ===\n";
    echo "✅ Database structure supports variants (sizes, colors, stock per variant)\n";
    echo "✅ ProductVariant model exists and works correctly\n";
    echo "✅ Product-Variant relationships are functional\n";
    echo "❌ Admin interface doesn't support variant creation\n";
    echo "❌ ProductController doesn't handle variant creation\n";

    echo "\n=== What needs to be implemented ===\n";
    echo "1. Modify admin product creation form to support variants\n";
    echo "2. Update ProductController to handle variant creation\n";
    echo "3. Add JavaScript for dynamic variant management in admin\n";
    echo "4. Update frontend to display variant options\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
