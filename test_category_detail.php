<?php

use App\Livewire\Guest\CategoryDetail;
use App\Models\Category;
use Livewire\Livewire;

require_once __DIR__ . '/vendor/autoload.php';

try {
    // Create an app instance
    $app = require __DIR__ . '/bootstrap/app.php';
    $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

    // Check if category with ID 3 exists (the one mentioned in the error)
    $category = Category::find(3);
    if (!$category) {
        echo "Test skipped: Category with ID 3 not found.\n";
        exit(0);
    }

    // Test the component
    echo "Testing CategoryDetail component with category ID 3...\n";
    $component = Livewire::test(CategoryDetail::class, ['category' => $category]);

    echo "Test completed successfully! The error has been fixed.\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
