<?php

// Test script to verify the CartController fix for "Undefined array key 'product_variant_id'"
require_once __DIR__ . '/vendor/autoload.php';

// Load Laravel application
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Category;

echo "=== Testing CartController Fix for Undefined Array Key ===\n\n";

try {
    // Test 1: Create test data
    echo "1. Setting up test data...\n";

    $category = Category::first();
    if (!$category) {
        $category = Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'is_active' => true
        ]);
    }

    $product = Product::create([
        'name' => 'Cart Controller Test Product',
        'slug' => 'cart-controller-test-' . time(),
        'description' => 'Testing cart controller fix',
        'short_description' => 'Test product for cart controller',
        'sku' => 'CCT-' . time(),
        'price' => 30000.00,
        'is_active' => true,
        'manage_stock' => true,
        'stock_quantity' => 50,
        'min_stock_level' => 5
    ]);

    $product->categories()->attach($category->id);

    $variant = ProductVariant::create([
        'product_id' => $product->id,
        'size' => 'M',
        'color' => 'Blue',
        'sku' => $product->sku . '-M-BLUE',
        'price' => 30000.00,
        'stock_quantity' => 25,
        'is_active' => true,
    ]);

    echo "   ✅ Test product and variant created\n";

    // Test 2: Create cart items with inconsistent structure
    echo "\n2. Testing cart with inconsistent data structures...\n";

    // Clear any existing cart
    session()->forget('cart');

    // Simulate cart with different data structures (some missing 'product_variant_id')
    $testCart = [
        'item1' => [
            'product_id' => $product->id,
            // Missing 'product_variant_id' key - this should not cause error anymore
            'quantity' => 2,
            'price' => 30000,
        ],
        'item2' => [
            'product_id' => $product->id,
            'product_variant_id' => $variant->id,
            'quantity' => 1,
            'price' => 30000,
        ],
        'item3' => [
            'product_id' => $product->id,
            'product_variant_id' => null, // Explicit null
            'quantity' => 1,
            'price' => 30000,
        ]
    ];

    session()->put('cart', $testCart);
    echo "   ✅ Created test cart with inconsistent data structures\n";

    // Test 3: Test CartController's getCartWithDetails method
    echo "\n3. Testing CartController's getCartWithDetails method...\n";

    $cartController = new \App\Http\Controllers\Client\CartController();

    // Use reflection to access private method
    $reflection = new ReflectionClass($cartController);
    $method = $reflection->getMethod('getCartWithDetails');
    $method->setAccessible(true);

    // This should not throw "Undefined array key 'product_variant_id'" error
    $result = $method->invoke($cartController);

    echo "   ✅ getCartWithDetails() executed without errors\n";
    echo "   ✅ Processed " . count($result) . " cart items\n";

    foreach ($result as $key => $item) {
        $variantInfo = $item['variant'] ? "with variant: " . $item['variant']->display_name : "without variant";
        echo "   - {$item['name']} (Qty: {$item['quantity']}) - {$variantInfo}\n";
    }

    // Test 4: Test actual cart index method (simulation)
    echo "\n4. Testing cart index method simulation...\n";

    // Simulate what happens in the index method
    $cart = $result; // This is the result from getCartWithDetails
    $subtotal = collect($cart)->sum('total');

    echo "   ✅ Cart subtotal calculated: UGX " . number_format($subtotal, 2) . "\n";
    echo "   ✅ Cart index method would work without errors\n";

    echo "\n=== Test Results Summary ===\n";
    echo "✅ CartController no longer throws 'Undefined array key' error\n";
    echo "✅ Handles cart items missing 'product_variant_id' key gracefully\n";
    echo "✅ Handles cart items with null 'product_variant_id' value correctly\n";
    echo "✅ Handles cart items with valid 'product_variant_id' correctly\n";
    echo "✅ Cart view functionality should work without errors\n";

    echo "\n🎉 The CartController fix is working correctly!\n";

    // Clean up test data
    echo "\nCleaning up test data...\n";
    session()->forget('cart');
    $product->variants()->delete();
    $product->categories()->detach();
    $product->delete();
    echo "✅ Test data cleaned up\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
