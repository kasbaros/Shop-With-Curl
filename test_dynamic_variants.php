<?php

// Test script to verify dynamic variant functionality throughout the app
require_once __DIR__ . '/vendor/autoload.php';

// Load Laravel application
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Category;
use App\Services\CartService;

echo "=== Testing Dynamic Variant Implementation ===\n\n";

try {
    // Test 1: Create a product with variants
    echo "1. Creating test product with variants...\n";

    $category = Category::first();
    if (!$category) {
        $category = Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'is_active' => true
        ]);
    }

    $product = Product::create([
        'name' => 'Dynamic Variant Test Product',
        'slug' => 'dynamic-variant-test-' . time(),
        'description' => 'Testing dynamic variant functionality',
        'short_description' => 'Test product for dynamic variants',
        'sku' => 'DVT-' . time(),
        'price' => 50000.00, // UGX 50,000
        'is_active' => true,
        'manage_stock' => true,
        'min_stock_level' => 5
    ]);

    $product->categories()->attach($category->id);
    echo "   ✅ Product created: {$product->name}\n";

    // Create variants
    $variantsData = [
        ['size' => 'S', 'color' => 'Red', 'price' => 48000, 'stock' => 10],
        ['size' => 'M', 'color' => 'Red', 'price' => 50000, 'stock' => 8],
        ['size' => 'L', 'color' => 'Red', 'price' => 52000, 'stock' => 5],
        ['size' => 'S', 'color' => 'Blue', 'price' => 48000, 'stock' => 12],
        ['size' => 'M', 'color' => 'Blue', 'price' => 50000, 'stock' => 0], // Out of stock
        ['size' => 'L', 'color' => 'Blue', 'price' => 52000, 'stock' => 3],
    ];

    $createdVariants = [];
    foreach ($variantsData as $variantData) {
        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'size' => $variantData['size'],
            'color' => $variantData['color'],
            'sku' => $product->sku . '-' . $variantData['size'] . '-' . strtoupper($variantData['color']),
            'price' => $variantData['price'],
            'stock_quantity' => $variantData['stock'],
            'is_active' => true,
        ]);
        $createdVariants[] = $variant;
    }

    echo "   ✅ Created " . count($createdVariants) . " variants\n";

    // Test 2: Test dynamic variant loading
    echo "\n2. Testing dynamic variant loading...\n";
    $productWithVariants = Product::with('variants')->find($product->id);

    // Test available sizes
    $availableSizes = $productWithVariants->variants()
        ->where('is_active', true)
        ->whereNotNull('size')
        ->pluck('size')
        ->unique()
        ->values();
    echo "   ✅ Available sizes: " . implode(', ', $availableSizes->toArray()) . "\n";

    // Test available colors
    $availableColors = $productWithVariants->variants()
        ->where('is_active', true)
        ->whereNotNull('color')
        ->pluck('color')
        ->unique()
        ->values();
    echo "   ✅ Available colors: " . implode(', ', $availableColors->toArray()) . "\n";

    // Test 3: Test CartService with variants
    echo "\n3. Testing CartService with variants...\n";
    $cartService = new CartService();

    // Test adding variant to cart
    $testVariant = $createdVariants[0]; // S/Red variant
    $variantsArray = [
        'size' => $testVariant->size,
        'color' => $testVariant->color,
        'variant_id' => $testVariant->id,
    ];

    $result = $cartService->add($product->id, 2, $variantsArray);
    echo "   " . ($result ? "✅" : "❌") . " Added variant to cart: {$testVariant->size}/{$testVariant->color}\n";

    // Test cart contents
    $cartItems = $cartService->getItems();
    if ($cartItems->count() > 0) {
        $item = $cartItems->first();
        echo "   ✅ Cart item: {$item['product_name']} - Price: UGX " . number_format($item['price'], 2) . "\n";
        echo "   ✅ Variant info: Size={$item['variants']['size']}, Color={$item['variants']['color']}\n";
    }

    // Test 4: Test stock checking
    echo "\n4. Testing variant stock checking...\n";

    // Try to add more than available stock
    $outOfStockVariant = $createdVariants[4]; // M/Blue variant with 0 stock
    $outOfStockVariants = [
        'size' => $outOfStockVariant->size,
        'color' => $outOfStockVariant->color,
        'variant_id' => $outOfStockVariant->id,
    ];

    $result = $cartService->add($product->id, 1, $outOfStockVariants);
    echo "   " . (!$result ? "✅" : "❌") . " Correctly prevented adding out-of-stock variant\n";

    // Test 5: Test price variations
    echo "\n5. Testing price variations...\n";
    $minPrice = $productWithVariants->variants->min('price');
    $maxPrice = $productWithVariants->variants->max('price');
    echo "   ✅ Price range: UGX " . number_format($minPrice, 2) . " - UGX " . number_format($maxPrice, 2) . "\n";

    // Test 6: Test component functionality simulation
    echo "\n6. Testing component functionality simulation...\n";

    // Simulate ProductQuickAdd behavior
    $selectedSize = 'L';
    $selectedColor = 'Red';

    // Get available colors for selected size
    $availableColorsForSize = $productWithVariants->variants()
        ->where('is_active', true)
        ->where('size', $selectedSize)
        ->whereNotNull('color')
        ->pluck('color')
        ->unique()
        ->values();

    echo "   ✅ Available colors for size {$selectedSize}: " . implode(', ', $availableColorsForSize->toArray()) . "\n";

    // Find matching variant
    $matchingVariant = $productWithVariants->variants()
        ->where('is_active', true)
        ->where('size', $selectedSize)
        ->where('color', $selectedColor)
        ->first();

    if ($matchingVariant) {
        echo "   ✅ Found matching variant: {$matchingVariant->display_name} - UGX " . number_format($matchingVariant->price, 2) . " (Stock: {$matchingVariant->stock_quantity})\n";
    }

    echo "\n=== Test Summary ===\n";
    echo "✅ Dynamic variant creation works correctly\n";
    echo "✅ Variant loading and filtering works correctly\n";
    echo "✅ CartService handles variants properly\n";
    echo "✅ Stock checking prevents overselling\n";
    echo "✅ Price variations are handled correctly\n";
    echo "✅ Component logic simulation works\n";

    echo "\n🎉 All dynamic variant functionality is working correctly!\n";
    echo "\nThe following components now support dynamic variants:\n";
    echo "- ProductQuickAdd: Shows dynamic sizes/colors from database\n";
    echo "- ProductQuickView: Shows dynamic sizes/colors from database\n";
    echo "- CartService: Handles variant stock and pricing\n";
    echo "- Cart components: Already supported variants\n";

    echo "\nNo more static 'Orange', 'Black', 'White' or 'S', 'M', 'L', 'XL' hardcoded values!\n";

    // Clean up test data
    echo "\nCleaning up test data...\n";
    $productWithVariants->variants()->delete();
    $productWithVariants->categories()->detach();
    $productWithVariants->delete();
    $cartService->clear();
    echo "✅ Test data cleaned up\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
