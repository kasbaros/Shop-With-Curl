<?php

// This is a simple test script to verify the route URL generation

// Simulate the URL generation for a gallery item with ID 1
$galleryId = 1;
$url = url('admin/gallery') . '/' . $galleryId;

echo "Generated URL: " . $url . "\n";
echo "Expected format: http://your-domain.com/admin/gallery/1\n";
echo "This URL should correctly map to the admin.gallery.destroy route with the gallery parameter.\n";

// In our fix, we changed:
// document.getElementById('deleteForm').action = "{{ route('admin.gallery.destroy', '') }}/" + itemId;
// to:
// document.getElementById('deleteForm').action = "{{ url('admin/gallery') }}/" + itemId;

// This ensures the gallery ID is properly included in the URL.
