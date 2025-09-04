<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use App\Models\Category;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Testing Category Grid Filter Implementation\n";
echo "==========================================\n\n";

try {
    // Test 1: Check if parent categories exist
    echo "1. Checking parent categories...\n";
    $parentCategories = Category::active()
        ->whereNull('parent_id')
        ->withCount('products')
        ->orderBy('sort_order')
        ->get();

    if ($parentCategories->count() > 0) {
        echo "✓ Found " . $parentCategories->count() . " parent categories:\n";
        foreach ($parentCategories as $category) {
            echo "  - {$category->name} (slug: {$category->slug}, products: {$category->products_count})\n";
        }
    } else {
        echo "✗ No parent categories found!\n";
    }
    echo "\n";

    // Test 2: Check child categories for each parent
    echo "2. Checking child categories for each parent...\n";
    foreach ($parentCategories as $parent) {
        $children = Category::active()
            ->where('parent_id', $parent->id)
            ->withCount('products')
            ->get();

        echo "Parent: {$parent->name}\n";
        if ($children->count() > 0) {
            echo "✓ Found " . $children->count() . " subcategories:\n";
            foreach ($children as $child) {
                echo "  - {$child->name} (products: {$child->products_count})\n";
            }
        } else {
            echo "✗ No subcategories found for {$parent->name}\n";
        }
        echo "\n";
    }

    // Test 3: Test filter logic
    echo "3. Testing filter logic with first parent category...\n";
    if ($parentCategories->count() > 0) {
        $firstParent = $parentCategories->first();

        $filteredCategories = Category::query()
            ->active()
            ->whereNotNull('parent_id')
            ->withCount('products')
            ->with(['parent'])
            ->whereHas('parent', function($q) use ($firstParent) {
                $q->where('slug', $firstParent->slug);
            })
            ->get();

        echo "Filtering by '{$firstParent->name}' (slug: {$firstParent->slug}):\n";
        echo "✓ Found " . $filteredCategories->count() . " filtered categories:\n";
        foreach ($filteredCategories as $category) {
            echo "  - {$category->name} (parent: {$category->parent->name})\n";
        }
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\nTest completed!\n";
