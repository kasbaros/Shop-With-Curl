<?php

/**
 * Test script for contact form functionality
 */

echo "=== Contact Form Test Script ===\n";

// Test 1: Check if the contact page loads
echo "\n1. Testing contact page access...\n";
$contactUrl = "http://localhost:8000/pages/contact";
echo "Attempting to access: $contactUrl\n";

// Test 2: Check form submission with valid data
echo "\n2. Testing valid form submission...\n";
$postData = [
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'message' => 'This is a test message for the contact form.'
];

echo "Test data:\n";
foreach ($postData as $key => $value) {
    echo "  $key: $value\n";
}

// Test 3: Check form submission with invalid data
echo "\n3. Testing invalid form submission...\n";
$invalidData = [
    'name' => '', // Empty name
    'email' => 'invalid-email', // Invalid email
    'message' => '' // Empty message
];

echo "Invalid test data:\n";
foreach ($invalidData as $key => $value) {
    echo "  $key: " . ($value ?: '[empty]') . "\n";
}

echo "\n=== Manual Testing Instructions ===\n";
echo "1. Start Laravel development server: php artisan serve\n";
echo "2. Visit: http://localhost:8000/pages/contact\n";
echo "3. Test valid form submission:\n";
echo "   - Fill in name: 'John Doe'\n";
echo "   - Fill in email: 'john@example.com'\n";
echo "   - Fill in message: 'Test message'\n";
echo "   - Click Send button\n";
echo "   - Should see success message and form should reset\n";
echo "\n4. Test invalid form submission:\n";
echo "   - Leave fields empty or enter invalid email\n";
echo "   - Click Send button\n";
echo "   - Should see validation errors\n";

echo "\n=== Expected Results ===\n";
echo "✓ Contact page should load without errors\n";
echo "✓ Valid form submission should show success message\n";
echo "✓ Invalid form submission should show validation errors\n";
echo "✓ Form should retain old input values on validation errors\n";
echo "✓ CSRF token should be present in form\n";

echo "\nTest script completed. Please run manual tests as described above.\n";
