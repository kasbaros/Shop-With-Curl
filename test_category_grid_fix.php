<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use App\Livewire\Guest\CategoryGrid;
use App\Models\Category;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Testing CategoryGrid Component for \$parentCategories Variable\n";
echo "============================================================\n\n";

try {
    // Test 1: Check if parent categories exist in database
    echo "1. Checking parent categories in database...\n";
    $parentCategories = Category::active()
        ->whereNull('parent_id')
        ->withCount('products')
        ->orderBy('sort_order')
        ->get();

    if ($parentCategories->count() > 0) {
        echo "✓ Found " . $parentCategories->count() . " parent categories in database\n";
        foreach ($parentCategories as $category) {
            echo "  - {$category->name} (slug: {$category->slug})\n";
        }
    } else {
        echo "✗ No parent categories found in database!\n";
    }
    echo "\n";

    // Test 2: Instantiate CategoryGrid component and check render method
    echo "2. Testing CategoryGrid component render method...\n";
    $component = new CategoryGrid();

    // Call render method
    $viewData = $component->render();

    if ($viewData instanceof \Illuminate\View\View) {
        $data = $viewData->getData();

        if (isset($data['parentCategories'])) {
            echo "✓ \$parentCategories variable is properly passed to view\n";
            echo "  - Count: " . $data['parentCategories']->count() . " items\n";

            foreach ($data['parentCategories'] as $category) {
                echo "  - {$category->name} (products: {$category->products_count})\n";
            }
        } else {
            echo "✗ \$parentCategories variable is NOT passed to view!\n";
            echo "Available variables: " . implode(', ', array_keys($data)) . "\n";
        }
    } else {
        echo "✗ Render method did not return a View instance\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\nTest completed!\n";
