<?php
//
//    namespace App\Http\Controllers\Admin;
//
//    use App\Helpers\ImageStorageHelper;
//    use Illuminate\Support\Facades\DB;
//    use App\Models\{Product, Category, ProductVariant};
//    use Illuminate\Http\Request;
//    use Illuminate\Support\Facades\Storage;
//    use Illuminate\Support\Str;
//    use Illuminate\Validation\Rule;
//
//    class ProductController extends AdminController
//    {
//        public function index(Request $request)
//        {
//            $query = Product::with(['categories']);
//
//            // Search
//            if ($request->filled('search')) {
//                $search = $request->search;
//                $query->where(function ($q) use ($search) {
//                    $q->where('name', 'LIKE', "%{$search}%")
//                        ->orWhere('sku', 'LIKE', "%{$search}%")
//                        ->orWhere('description', 'LIKE', "%{$search}%");
//                });
//            }
//
//            // Category filter
//            if ($request->filled('category')) {
//                $query->whereHas('categories', function ($q) use ($request) {
//                    $q->where('categories.id', $request->category);
//                });
//            }
//
//            // Status filter
//            if ($request->filled('status')) {
//                $query->where('is_active', $request->status === 'active');
//            }
//
//            // Stock filter
//            if ($request->filled('stock')) {
//                switch ($request->stock) {
//                    case 'in_stock':
//                        $query->where('manage_stock', false)
//                            ->orWhere(function ($q) {
//                                $q->where('manage_stock', true)
//                                    ->where('stock_quantity', '>', 0);
//                            });
//                        break;
//                    case 'low_stock':
//                        $query->where('manage_stock', true)
//                            ->whereRaw('stock_quantity <= COALESCE(min_stock_level, 5)')
//                            ->where('stock_quantity', '>', 0);
//                        break;
//                    case 'out_of_stock':
//                        $query->where('manage_stock', true)
//                            ->where('stock_quantity', '<=', 0);
//                        break;
//                }
//            }
//
//            // Sort
//            $sortField = $request->get('sort', 'created_at');
//            $sortDirection = $request->get('direction', 'desc');
//            $query->orderBy($sortField, $sortDirection);
//
//            $products = $query->paginate(20)->withQueryString();
//            $categories = Category::where('is_active', true)->get();
//
//            return view('admin.products.index', array_merge(
//                $this->getAdminViewData(),
//                compact('products', 'categories')
//            ));
//        }
//
//        public function create()
//        {
//            // Fetch parent categories as groups with their active children
//            $categoryGroups = Category::active()
//                ->parent()
//                ->with(['children' => function ($q) {
//                    $q->active()->orderBy('name');
//                }])
//                ->orderBy('name')
//                ->get();
//
//            return view('admin.products.create', array_merge(
//                $this->getAdminViewData(),
//                compact('categoryGroups')
//            ));
//        }
//
////        public function store(Request $request)
////        {
////            $validated = $request->validate([
////                'name' => 'required|string|max:255',
////                'slug' => 'nullable|string|max:255|unique:products,slug',
////                'description' => 'required|string',
////                'short_description' => 'nullable|string|max:500',
////                'sku' => 'required|string|max:100|unique:products,sku',
////                'category_id' => 'required|exists:categories,id',
////                'price' => 'required|numeric|min:0',
////                'sale_price' => 'nullable|numeric|min:0|lt:price',
////                'manage_stock' => 'boolean',
////                'stock_quantity' => 'nullable|integer|min:0',
////                'min_stock_level' => 'nullable|integer|min:0',
////                'weight' => 'nullable|numeric|min:0',
////                'dimensions' => 'nullable|string|max:100',
////                'is_active' => 'boolean',
////                'is_featured' => 'boolean',
////                'meta_title' => 'nullable|string|max:255',
////                'meta_description' => 'nullable|string|max:500',
////                'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
////                'images' => 'nullable|array',
////                'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:4096',
////                // Variant validation
////                'has_variants' => 'boolean',
////                'variants' => 'nullable|array',
////                'variants.*.size' => 'required_with:variants|string|max:50',
////                'variants.*.color' => 'required_with:variants|string|max:50',
////                'variants.*.sku_suffix' => 'required_with:variants|string|max:50',
////                'variants.*.price' => 'required_with:variants|numeric|min:0',
////                'variants.*.stock_quantity' => 'required_with:variants|integer|min:0',
////                'variants.*.is_active' => 'boolean',
////            ]);
////
////            // Generate slug if not provided
////            if (empty($validated['slug'])) {
////                $validated['slug'] = Str::slug($validated['name']);
////                $base = $validated['slug'];
////                $i = 1;
////                while (Product::where('slug', $validated['slug'])->exists()) {
////                    $validated['slug'] = $base . '-' . $i++;
////                }
////            }
////
////            // Prepare data
////            $categories = [$validated['category_id']];
////            unset($validated['category_id']);
////
////            // Handle featured image (stored under /public_html/storage/products)
////            if ($request->hasFile('featured_image')) {
////                $validated['featured_image'] = ImageStorageHelper::store(
////                    $request->file('featured_image'),
////                    'products'
////                );
////            }
////
////            // Initialize gallery array
////            $gallery = [];
////
////            if ($request->hasFile('images')) {
////                foreach ($request->file('images') as $img) {
////                    $gallery[] = ImageStorageHelper::store($img, 'products');
////                }
////            }
////
////            // Persist product with gallery array
////            $validated['gallery'] = $gallery;
////
////            // Remove variant-specific fields from product data
////            $productData = collect($validated)->except(['has_variants', 'variants'])->toArray();
////            $product = Product::create($productData);
////
////            // Attach categories
////            $product->categories()->attach($categories);
////
////            // Create variants if enabled
////            if ($request->has_variants && $request->variants) {
////                foreach ($request->variants as $variantData) {
////                    if (isset($variantData['is_active'])) {
////                        $variantData['is_active'] = true;
////                    } else {
////                        $variantData['is_active'] = false;
////                    }
////
////                    // Generate full SKU for variant
////                    $variantSku = $product->sku . '-' . $variantData['sku_suffix'];
////
////                    // Check if variant SKU already exists
////                    $counter = 1;
////                    $originalSku = $variantSku;
////                    while (\App\Models\ProductVariant::where('sku', $variantSku)->exists()) {
////                        $variantSku = $originalSku . '-' . $counter++;
////                    }
////
////                    \App\Models\ProductVariant::create([
////                        'product_id' => $product->id,
////                        'size' => $variantData['size'],
////                        'color' => $variantData['color'],
////                        'sku' => $variantSku,
////                        'price' => $variantData['price'],
////                        'stock_quantity' => $variantData['stock_quantity'],
////                        'is_active' => $variantData['is_active'],
////                    ]);
////                }
////
////                $successMessage = 'Product created successfully with ' . count($request->variants) . ' variants!';
////            } else {
////                $successMessage = 'Product created successfully!';
////            }
////
////            return redirect()
////                ->route('admin.products.show', $product)
////                ->with('success', $successMessage);
////        }
//
//        public function store(Request $request)
//        {
//            $validated = $request->validate([
//                'name' => 'required|string|max:255',
//                'slug' => 'nullable|string|max:255|unique:products,slug',
//                'description' => 'required|string',
//                'short_description' => 'nullable|string|max:500',
//                'sku' => 'required|string|max:100|unique:products,sku',
//                'category_id' => 'required|exists:categories,id',
//                'price' => 'required|numeric|min:0',
//                'sale_price' => 'nullable|numeric|min:0|lt:price',
//                'manage_stock' => 'boolean',
//                'stock_quantity' => 'nullable|integer|min:0',
//                'min_stock_level' => 'nullable|integer|min:0',
//                'weight' => 'nullable|numeric|min:0',
//                'dimensions' => 'nullable|string|max:100',
//                'is_active' => 'boolean',
//                'is_featured' => 'boolean',
//                'meta_title' => 'nullable|string|max:255',
//                'meta_description' => 'nullable|string|max:500',
//                'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
//                'images' => 'nullable|array',
//                'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:4096',
//                // Variant validation
//                'has_variants' => 'boolean',
//                'variants' => 'nullable|array',
//                'variants.*.size' => 'required_with:variants|string|max:50',
//                'variants.*.color' => 'required_with:variants|string|max:50',
//                'variants.*.sku_suffix' => 'required_with:variants|string|max:50',
//                'variants.*.price' => 'required_with:variants|numeric|min:0',
//                'variants.*.stock_quantity' => 'required_with:variants|integer|min:0',
//                'variants.*.is_active' => 'boolean',
//                'variants.*.image' => 'required_with:variants|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
//            ]);
//
//            // Generate slug if not provided
//            if (empty($validated['slug'])) {
//                $validated['slug'] = Str::slug($validated['name']);
//                $base = $validated['slug'];
//                $i = 1;
//                while (Product::where('slug', $validated['slug'])->exists()) {
//                    $validated['slug'] = $base . '-' . $i++;
//                }
//            }
//
//            // Prepare data
//            $categories = [$validated['category_id']];
//            unset($validated['category_id']);
//
//            // Handle featured image
//            if ($request->hasFile('featured_image')) {
//                $validated['featured_image'] = ImageStorageHelper::store(
//                    $request->file('featured_image'),
//                    'products'
//                );
//            }
//
//            // Initialize gallery array
//            $gallery = [];
//            if ($request->hasFile('images')) {
//                foreach ($request->file('images') as $img) {
//                    $gallery[] = ImageStorageHelper::store($img, 'products');
//                }
//            }
//
//            // Persist product with gallery array
//            $validated['gallery'] = $gallery;
//
//            // Remove variant-specific fields from product data
//            $productData = collect($validated)->except(['has_variants', 'variants'])->toArray();
//            $product = Product::create($productData);
//
//            // Attach categories
//            $product->categories()->attach($categories);
//
//            // Create variants if enabled
//            if ($request->has_variants && $request->variants) {
//                foreach ($request->variants as $variantData) {
//                    $variantData['is_active'] = isset($variantData['is_active']) ? true : false;
//
//                    // Generate full SKU for variant
//                    $variantSku = $product->sku . '-' . $variantData['sku_suffix'];
//                    $counter = 1;
//                    $originalSku = $variantSku;
//                    while (ProductVariant::where('sku', $variantSku)->exists()) {
//                        $variantSku = $originalSku . '-' . $counter++;
//                    }
//
//                    // Handle variant image
//                    $variantImages = [];
//                    if (isset($variantData['image']) && $request->hasFile("variants.{$loop->index}.image")) {
//                        $variantImages[] = ImageStorageHelper::store(
//                            $request->file("variants.{$loop->index}.image"),
//                            'variant_images'
//                        );
//                    }
//
//                    ProductVariant::create([
//                        'product_id' => $product->id,
//                        'size' => $variantData['size'],
//                        'color' => $variantData['color'],
//                        'sku' => $variantSku,
//                        'price' => $variantData['price'],
//                        'stock_quantity' => $variantData['stock_quantity'],
//                        'is_active' => $variantData['is_active'],
//                        'images' => $variantImages,
//                    ]);
//                }
//
//                $successMessage = 'Product created successfully with ' . count($request->variants) . ' variants!';
//            } else {
//                $successMessage = 'Product created successfully!';
//            }
//
//            return redirect()
//                ->route('admin.products.show', $product)
//                ->with('success', $successMessage);
//        }
//
//        public function show(Product $product)
//        {
//            $product->load(['categories', 'reviews.user', 'variants']);
//
//            return view('admin.products.show', array_merge(
//                $this->getAdminViewData(),
//                compact('product')
//            ));
//        }
//
//
//        public function edit(Product $product)
//        {
//            $categories = Category::where('is_active', true)->get();
//            $product->load(['categories', 'variants']);
//
//            return view('admin.products.edit', array_merge(
//                $this->getAdminViewData(),
//                compact('product', 'categories')
//            ));
//        }
//
//        public function update(Request $request, Product $product)
//        {
//            $validated = $request->validate([
//                'name' => 'required|string|max:255',
//                'slug' => ['nullable', 'string', 'max:255', Rule::unique('products')->ignore($product)],
//                'description' => 'required|string',
//                'short_description' => 'nullable|string|max:500',
//                'sku' => ['required', 'string', 'max:100', Rule::unique('products')->ignore($product)],
//                'categories' => 'required|array|min:1',
//                'categories.*' => 'exists:categories,id',
//                'price' => 'required|numeric|min:0',
//                'sale_price' => 'nullable|numeric|min:0|lt:price',
//                'manage_stock' => 'boolean',
//                'stock_quantity' => 'nullable|integer|min:0',
//                'min_stock_level' => 'nullable|integer|min:0',
//                'weight' => 'nullable|numeric|min:0',
//                'dimensions' => 'nullable|string|max:100',
//                'is_active' => 'boolean',
//                'is_featured' => 'boolean',
//                'status' => 'required|in:draft,published,inactive',
//                'meta_title' => 'nullable|string|max:255',
//                'meta_description' => 'nullable|string|max:500',
//
//                // Image handling
//                'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
//                'remove_featured_image' => 'nullable|boolean',
//                'images' => 'nullable|array',
//                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
//                'remove_gallery_images' => 'nullable|array',
//                'remove_gallery_images.*' => 'integer|min:0',
//                'remove_media_images' => 'nullable|array',
//                'remove_media_images.*' => 'integer',
//
//                // Variant handling
//                'has_variants' => 'boolean',
//                'variants' => 'nullable|array',
//                'variants.*.id' => 'nullable|exists:product_variants,id',
//                'variants.*.size' => 'required_with:variants|string|max:50',
//                'variants.*.color' => 'required_with:variants|string|max:50',
//                'variants.*.sku_suffix' => 'required_with:variants|string|max:50',
//                'variants.*.price' => 'required_with:variants|numeric|min:0',
//                'variants.*.stock_quantity' => 'required_with:variants|integer|min:0',
//                'variants.*.is_active' => 'boolean',
//                'delete_variants' => 'nullable|array',
//                'delete_variants.*' => 'exists:product_variants,id',
//            ]);
//
//            DB::beginTransaction();
//
//            try {
//                // Generate slug if empty
//                if (empty($validated['slug'])) {
//                    $validated['slug'] = Str::slug($validated['name']);
//                    $base = $validated['slug'];
//                    $i = 1;
//                    while (Product::where('slug', $validated['slug'])
//                        ->where('id', '!=', $product->id)->exists()) {
//                        $validated['slug'] = $base . '-' . $i++;
//                    }
//                }
//
//                // Handle featured image
//                if ($request->has('remove_featured_image') && $request->remove_featured_image) {
//                    if (!empty($product->featured_image)) {
//                        ImageStorageHelper::delete($product->featured_image);
//                    }
//                    $validated['featured_image'] = null;
//                } elseif ($request->hasFile('featured_image')) {
//                    if (!empty($product->featured_image)) {
//                        ImageStorageHelper::delete($product->featured_image);
//                    }
//                    $validated['featured_image'] = ImageStorageHelper::store(
//                        $request->file('featured_image'),
//                        'products'
//                    );
//                } else {
//                    // Keep existing featured image
//                    unset($validated['featured_image']);
//                }
//
//                // Handle gallery images
//                $gallery = is_array($product->gallery) ? $product->gallery : [];
//
//                // Remove selected gallery images by index
//                if ($request->filled('remove_gallery_images')) {
//                    $indicesToRemove = array_map('intval', $request->input('remove_gallery_images'));
//                    rsort($indicesToRemove); // Remove from end to maintain indices
//
//                    foreach ($indicesToRemove as $idx) {
//                        if (isset($gallery[$idx])) {
//                            ImageStorageHelper::delete($gallery[$idx]);
//                            unset($gallery[$idx]);
//                        }
//                    }
//                    $gallery = array_values($gallery); // Reindex
//                }
//
//                // Remove legacy media library images if they exist
//                if ($request->filled('remove_media_images')) {
//                    foreach ($request->input('remove_media_images') as $mediaId) {
//                        $media = $product->media()->where('id', $mediaId)->first();
//                        if ($media) {
//                            $media->delete();
//                        }
//                    }
//                }
//
//                // Add new gallery images
//                if ($request->hasFile('images')) {
//                    foreach ($request->file('images') as $img) {
//                        // Only process valid file uploads
//                        if ($img && $img->isValid()) {
//                            $gallery[] = ImageStorageHelper::store($img, 'products');
//                        }
//                    }
//                }
//
//                $validated['gallery'] = $gallery;
//
//                // Extract categories for separate handling
//                $categories = $validated['categories'];
//                unset($validated['categories']);
//
//                // Handle variants
//                $hasVariants = $validated['has_variants'] ?? false;
//                unset($validated['has_variants'], $validated['variants'], $validated['delete_variants']);
//
//                if ($hasVariants && $request->variants) {
//                    $existingVariantIds = [];
//
//                    foreach ($request->variants as $index => $variantData) {
//                        $variantData['is_active'] = isset($variantData['is_active']) ? true : false;
//
//                        // Handle variant image
//                        $variantImages = $variant->images ?? [];
//                        if (isset($variantData['remove_image']) && $variantData['remove_image'] == '1') {
//                            // Delete existing images
//                            if (is_array($variantImages)) {
//                                foreach ($variantImages as $image) {
//                                    ImageStorageHelper::delete($image);
//                                }
//                            }
//                            $variantImages = [];
//                        }
//                        if (isset($variantData['image']) && $request->hasFile("variants.{$index}.image")) {
//                            // Delete old images if new image is uploaded
//                            if (is_array($variantImages)) {
//                                foreach ($variantImages as $image) {
//                                    ImageStorageHelper::delete($image);
//                                }
//                            }
//                            $variantImages = [ImageStorageHelper::store($request->file("variants.{$index}.image"), 'variant_images')];
//                        }
//                        $variant->update([
//                            'size' => $variantData['size'],
//                            'color' => $variantData['color'],
//                            'sku' => $newSku,
//                            'price' => $variantData['price'],
//                            'stock_quantity' => $variantData['stock_quantity'],
//                            'is_active' => $variantData['is_active'],
//                            'images' => $variantImages,
//                        ]);
//
//
//
//                        $variantImages = [];
//                        if (isset($variantData['image']) && $request->hasFile("variants.{$index}.image")) {
//                            $variantImages[] = ImageStorageHelper::store(
//                                $request->file("variants.{$index}.image"),
//                                'variant_images'
//                            );
//                        }
//
//                        if (!empty($variantData['id'])) {
//                            $variant = ProductVariant::where('id', $variantData['id'])
//                                ->where('product_id', $product->id)
//                                ->first();
//
//                            if ($variant) {
//                                $newSku = $product->sku . '-' . $variantData['sku_suffix'];
//                                if ($variant->sku !== $newSku) {
//                                    $counter = 1;
//                                    $originalSku = $newSku;
//                                    while (ProductVariant::where('sku', $newSku)
//                                        ->where('id', '!=', $variant->id)
//                                        ->exists()) {
//                                        $newSku = $originalSku . '-' . $counter++;
//                                    }
//                                }
//
//                                $variant->update([
//                                    'size' => $variantData['size'],
//                                    'color' => $variantData['color'],
//                                    'sku' => $newSku,
//                                    'price' => $variantData['price'],
//                                    'stock_quantity' => $variantData['stock_quantity'],
//                                    'is_active' => $variantData['is_active'],
//                                    'images' => $variantImages ?: $variant->images, // Preserve existing images if no new upload
//                                ]);
//
//                                $existingVariantIds[] = $variant->id;
//                            }
//                        } else {
//                            $variantSku = $product->sku . '-' . $variantData['sku_suffix'];
//                            $counter = 1;
//                            $originalSku = $variantSku;
//                            while (ProductVariant::where('sku', $variantSku)->exists()) {
//                                $variantSku = $originalSku . '-' . $counter++;
//                            }
//
//                            $newVariant = ProductVariant::create([
//                                'product_id' => $product->id,
//                                'size' => $variantData['size'],
//                                'color' => $variantData['color'],
//                                'sku' => $variantSku,
//                                'price' => $variantData['price'],
//                                'stock_quantity' => $variantData['stock_quantity'],
//                                'is_active' => $variantData['is_active'],
//                                'images' => $variantImages,
//                            ]);
//
//                            $existingVariantIds[] = $newVariant->id;
//                        }
//                    }
//
//                    // Delete variants marked for deletion
//                    if ($request->filled('delete_variants')) {
//                        $variantsToDelete = ProductVariant::whereIn('id', $request->delete_variants)
//                            ->where('product_id', $product->id)
//                            ->get();
//                        foreach ($variantsToDelete as $variant) {
//                            if (is_array($variant->images)) {
//                                foreach ($variant->images as $image) {
//                                    ImageStorageHelper::delete($image);
//                                }
//                            }
//                            $variant->delete();
//                        }
//                    }
//
//                    // Delete variants that were removed from the form
//                    ProductVariant::where('product_id', $product->id)
//                        ->whereNotIn('id', $existingVariantIds)
//                        ->each(function ($variant) {
//                            if (is_array($variant->images)) {
//                                foreach ($variant->images as $image) {
//                                    ImageStorageHelper::delete($image);
//                                }
//                            }
//                            $variant->delete();
//                        });
//                }
//
//                // Update product
//                $product->update($validated);
//
//                // Sync categories
//                $product->categories()->sync($categories);
//
//                // Handle variants
//                if ($hasVariants && $request->variants) {
//                    $existingVariantIds = [];
//
//                    foreach ($request->variants as $variantData) {
//                        $variantData['is_active'] = isset($variantData['is_active']) && $variantData['is_active'];
//
//                        // Update existing variant
//                        if (!empty($variantData['id'])) {
//                            $variant = ProductVariant::where('id', $variantData['id'])
//                                ->where('product_id', $product->id)
//                                ->first();
//
//                            if ($variant) {
//                                // Check if SKU changed and ensure uniqueness
//                                $newSku = $product->sku . '-' . $variantData['sku_suffix'];
//                                if ($variant->sku !== $newSku) {
//                                    $counter = 1;
//                                    $originalSku = $newSku;
//                                    while (ProductVariant::where('sku', $newSku)
//                                        ->where('id', '!=', $variant->id)
//                                        ->exists()) {
//                                        $newSku = $originalSku . '-' . $counter++;
//                                    }
//                                }
//
//                                $variant->update([
//                                    'size' => $variantData['size'],
//                                    'color' => $variantData['color'],
//                                    'sku' => $newSku,
//                                    'price' => $variantData['price'],
//                                    'stock_quantity' => $variantData['stock_quantity'],
//                                    'is_active' => $variantData['is_active'],
//                                ]);
//
//                                $existingVariantIds[] = $variant->id;
//                            }
//                        } else {
//                            // Create new variant
//                            $variantSku = $product->sku . '-' . $variantData['sku_suffix'];
//                            $counter = 1;
//                            $originalSku = $variantSku;
//                            while (ProductVariant::where('sku', $variantSku)->exists()) {
//                                $variantSku = $originalSku . '-' . $counter++;
//                            }
//
//                            $newVariant = ProductVariant::create([
//                                'product_id' => $product->id,
//                                'size' => $variantData['size'],
//                                'color' => $variantData['color'],
//                                'sku' => $variantSku,
//                                'price' => $variantData['price'],
//                                'stock_quantity' => $variantData['stock_quantity'],
//                                'is_active' => $variantData['is_active'],
//                            ]);
//
//                            $existingVariantIds[] = $newVariant->id;
//                        }
//                    }
//
//                    // Delete variants marked for deletion
//                    if ($request->filled('delete_variants')) {
//                        ProductVariant::whereIn('id', $request->delete_variants)
//                            ->where('product_id', $product->id)
//                            ->delete();
//                    }
//
//                    // Delete variants that were removed from the form
//                    ProductVariant::where('product_id', $product->id)
//                        ->whereNotIn('id', $existingVariantIds)
//                        ->delete();
//                } elseif (!$hasVariants) {
//                    // If variants disabled, delete all variants
//                    $product->variants()->delete();
//                }
//
//                DB::commit();
//
//                return redirect()
//                    ->route('admin.products.show', $product)
//                    ->with('success', 'Product updated successfully!');
//
//            } catch (\Exception $e) {
//                DB::rollBack();
//
//                return redirect()
//                    ->back()
//                    ->withInput()
//                    ->with('error', 'Failed to update product: ' . $e->getMessage());
//            }
//        }
//
//
//        public function destroy(Product $product)
//        {
//            // Delete featured image
//            if (!empty($product->featured_image)) {
//                \App\Helpers\ImageStorageHelper::delete($product->featured_image);
//            }
//
//            // Delete gallery images
//            if (is_array($product->gallery)) {
//                foreach ($product->gallery as $path) {
//                    \App\Helpers\ImageStorageHelper::delete($path);
//                }
//            }
//
//            $product->delete();
//
//            return redirect()
//                ->route('admin.products.index')
//                ->with('success', 'Product deleted successfully!');
//        }
//
//        public function toggleStatus(Product $product)
//        {
//            $product->update(['is_active' => !$product->is_active]);
//
//            $status = $product->is_active ? 'activated' : 'deactivated';
//
//            return response()->json([
//                'success' => true,
//                'message' => "Product {$status} successfully!",
//                'is_active' => $product->is_active
//            ]);
//        }
//
//        public function toggleFeatured(Product $product)
//        {
//            $product->update(['is_featured' => !$product->is_featured]);
//
//            $status = $product->is_featured ? 'featured' : 'unfeatured';
//
//            return response()->json([
//                'success' => true,
//                'message' => "Product {$status} successfully!",
//                'is_featured' => $product->is_featured
//            ]);
//        }
//
//
//        public function bulkAction(Request $request)
//        {
//            $request->validate([
//                'action' => 'required|in:activate,deactivate,delete,feature,unfeature',
//                'products' => 'required|array',
//                'products.*' => 'exists:products,id'
//            ]);
//
//            $products = Product::whereIn('id', $request->products);
//            $count = $products->count();
//
//            switch ($request->action) {
//                case 'activate':
//                    $products->update(['is_active' => true]);
//                    $message = "{$count} products activated successfully!";
//                    break;
//                case 'deactivate':
//                    $products->update(['is_active' => false]);
//                    $message = "{$count} products deactivated successfully!";
//                    break;
//                case 'feature':
//                    $products->update(['is_featured' => true]);
//                    $message = "{$count} products featured successfully!";
//                    break;
//                case 'unfeature':
//                    $products->update(['is_featured' => false]);
//                    $message = "{$count} products unfeatured successfully!";
//                    break;
//                case 'delete':
//                    $productsToDelete = $products->get();
//                    foreach ($productsToDelete as $product) {
//                        $product->clearMediaCollection('images');
//                        $product->clearMediaCollection('gallery');
//                    }
//                    $products->delete();
//                    $message = "{$count} products deleted successfully!";
//                    break;
//            }
//
//            return redirect()
//                ->route('admin.products.index')
//                ->with('success', $message);
//        }
//    }


namespace App\Http\Controllers\Admin;

use App\Helpers\ImageStorageHelper;
use Illuminate\Support\Facades\DB;
use App\Models\{Product, Category, ProductVariant};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductController extends AdminController
{
    public function index(Request $request)
    {
        $query = Product::with(['categories']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('sku', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category);
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Stock filter
        if ($request->filled('stock')) {
            switch ($request->stock) {
                case 'in_stock':
                    $query->where('manage_stock', false)
                        ->orWhere(function ($q) {
                            $q->where('manage_stock', true)
                                ->where('stock_quantity', '>', 0);
                        });
                    break;
                case 'low_stock':
                    $query->where('manage_stock', true)
                        ->whereRaw('stock_quantity <= COALESCE(min_stock_level, 5)')
                        ->where('stock_quantity', '>', 0);
                    break;
                case 'out_of_stock':
                    $query->where('manage_stock', true)
                        ->where('stock_quantity', '<=', 0);
                    break;
            }
        }

        // Sort
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $products = $query->paginate(20)->withQueryString();
        $categories = Category::where('is_active', true)->get();

        return view('admin.products.index', array_merge(
            $this->getAdminViewData(),
            compact('products', 'categories')
        ));
    }

    public function create()
    {
        // Fetch parent categories as groups with their active children
        $categoryGroups = Category::active()
            ->parent()
            ->with(['children' => function ($q) {
                $q->active()->orderBy('name');
            }])
            ->orderBy('name')
            ->get();

        return view('admin.products.create', array_merge(
            $this->getAdminViewData(),
            compact('categoryGroups')
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'sku' => 'required|string|max:100|unique:products,sku',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'manage_stock' => 'boolean',
            'stock_quantity' => 'nullable|integer|min:0',
            'min_stock_level' => 'nullable|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|string|max:100',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            // Variant validation
            'has_variants' => 'boolean',
            'variants' => 'nullable|array',
            'variants.*.size' => 'required_with:variants|string|max:50',
            'variants.*.color' => 'required_with:variants|string|max:50',
            'variants.*.sku_suffix' => 'required_with:variants|string|max:50',
            'variants.*.price' => 'required_with:variants|numeric|min:0',
            'variants.*.stock_quantity' => 'required_with:variants|integer|min:0',
            'variants.*.is_active' => 'boolean',
            'variants.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
            $base = $validated['slug'];
            $i = 1;
            while (Product::where('slug', $validated['slug'])->exists()) {
                $validated['slug'] = $base . '-' . $i++;
            }
        }

        // Prepare data
        $categories = [$validated['category_id']];
        unset($validated['category_id']);

        // Handle featured image
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = ImageStorageHelper::store(
                $request->file('featured_image'),
                'products'
            );
        }

        // Initialize gallery array
        $gallery = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $gallery[] = ImageStorageHelper::store($img, 'products');
            }
        }

        // Persist product with gallery array
        $validated['gallery'] = $gallery;

        // Remove variant-specific fields from product data
        $productData = collect($validated)->except(['has_variants', 'variants'])->toArray();
        $product = Product::create($productData);

        // Attach categories
        $product->categories()->attach($categories);

        // Create variants if enabled
        if ($request->has_variants && $request->variants) {
            foreach ($request->variants as $index => $variantData) {
                $variantData['is_active'] = isset($variantData['is_active']) ? true : false;

                // Generate full SKU for variant
                $variantSku = $product->sku . '-' . $variantData['sku_suffix'];
                $counter = 1;
                $originalSku = $variantSku;
                while (ProductVariant::where('sku', $variantSku)->exists()) {
                    $variantSku = $originalSku . '-' . $counter++;
                }

                // Handle variant image
                $variantImages = [];
                if (isset($variantData['image']) && $request->hasFile("variants.{$index}.image")) {
                    $variantImages[] = ImageStorageHelper::store(
                        $request->file("variants.{$index}.image"),
                        'variant_images'
                    );
                }

                ProductVariant::create([
                    'product_id' => $product->id,
                    'size' => $variantData['size'],
                    'color' => $variantData['color'],
                    'sku' => $variantSku,
                    'price' => $variantData['price'],
                    'stock_quantity' => $variantData['stock_quantity'],
                    'is_active' => $variantData['is_active'],
                    'images' => $variantImages,
                ]);
            }

            $successMessage = 'Product created successfully with ' . count($request->variants) . ' variants!';
        } else {
            $successMessage = 'Product created successfully!';
        }

        return redirect()
            ->route('admin.products.show', $product)
            ->with('success', $successMessage);
    }

    public function show(Product $product)
    {
        $product->load(['categories', 'reviews.user', 'variants']);

        $variantsData = $product->variants->map(function($variant) {
            return [
                'id' => $variant->id,
                'color' => $variant->color,
                'size' => $variant->size,
                'price' => $variant->effective_price,
                'stock' => $variant->stock_quantity,
                'image' => $variant->primary_image_url
            ];
        })->toArray();

        return view('admin.products.show', array_merge(
            $this->getAdminViewData(),
            compact('product')
        ));
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        $product->load(['categories', 'variants']);

        return view('admin.products.edit', array_merge(
            $this->getAdminViewData(),
            compact('product', 'categories')
        ));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('products')->ignore($product)],
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'sku' => ['required', 'string', 'max:100', Rule::unique('products')->ignore($product)],
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:categories,id',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'manage_stock' => 'boolean',
            'stock_quantity' => 'nullable|integer|min:0',
            'min_stock_level' => 'nullable|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|string|max:100',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'status' => 'required|in:draft,published,inactive',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',

            // Image handling
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'remove_featured_image' => 'nullable|boolean',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'remove_gallery_images' => 'nullable|array',
            'remove_gallery_images.*' => 'integer|min:0',
            'remove_media_images' => 'nullable|array',
            'remove_media_images.*' => 'integer',

            // Variant handling
            'has_variants' => 'boolean',
            'variants' => 'nullable|array',
            'variants.*.id' => 'nullable|exists:product_variants,id',
            'variants.*.size' => 'required_with:variants|string|max:50',
            'variants.*.color' => 'required_with:variants|string|max:50',
            'variants.*.sku_suffix' => 'required_with:variants|string|max:50',
            'variants.*.price' => 'required_with:variants|numeric|min:0',
            'variants.*.stock_quantity' => 'required_with:variants|integer|min:0',
            'variants.*.is_active' => 'boolean',
            'variants.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'variants.*.remove_image' => 'nullable|boolean',
            'delete_variants' => 'nullable|array',
            'delete_variants.*' => 'exists:product_variants,id',
        ]);

        DB::beginTransaction();

        try {
            // Generate slug if empty
            if (empty($validated['slug'])) {
                $validated['slug'] = Str::slug($validated['name']);
                $base = $validated['slug'];
                $i = 1;
                while (Product::where('slug', $validated['slug'])
                    ->where('id', '!=', $product->id)->exists()) {
                    $validated['slug'] = $base . '-' . $i++;
                }
            }

            // Handle featured image
            if ($request->has('remove_featured_image') && $request->remove_featured_image) {
                if (!empty($product->featured_image)) {
                    ImageStorageHelper::delete($product->featured_image);
                }
                $validated['featured_image'] = null;
            } elseif ($request->hasFile('featured_image')) {
                if (!empty($product->featured_image)) {
                    ImageStorageHelper::delete($product->featured_image);
                }
                $validated['featured_image'] = ImageStorageHelper::store(
                    $request->file('featured_image'),
                    'products'
                );
            } else {
                // Keep existing featured image
                unset($validated['featured_image']);
            }

            // Handle gallery images
            $gallery = is_array($product->gallery) ? $product->gallery : [];

            // Remove selected gallery images by index
            if ($request->filled('remove_gallery_images')) {
                $indicesToRemove = array_map('intval', $request->input('remove_gallery_images'));
                rsort($indicesToRemove); // Remove from end to maintain indices

                foreach ($indicesToRemove as $idx) {
                    if (isset($gallery[$idx])) {
                        ImageStorageHelper::delete($gallery[$idx]);
                        unset($gallery[$idx]);
                    }
                }
                $gallery = array_values($gallery); // Reindex
            }

            // Remove legacy media library images if they exist
            if ($request->filled('remove_media_images')) {
                foreach ($request->input('remove_media_images') as $mediaId) {
                    $media = $product->media()->where('id', $mediaId)->first();
                    if ($media) {
                        $media->delete();
                    }
                }
            }

            // Add new gallery images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $img) {
                    // Only process valid file uploads
                    if ($img && $img->isValid()) {
                        $gallery[] = ImageStorageHelper::store($img, 'products');
                    }
                }
            }

            $validated['gallery'] = $gallery;

            // Extract categories for separate handling
            $categories = $validated['categories'];
            unset($validated['categories']);

            // Handle variants
            $hasVariants = $validated['has_variants'] ?? false;
            unset($validated['has_variants'], $validated['variants'], $validated['delete_variants']);

            // Update product
            $product->update($validated);

            // Sync categories
            $product->categories()->sync($categories);

            // Handle variants
            if ($hasVariants && $request->variants) {
                $existingVariantIds = [];

                foreach ($request->variants as $index => $variantData) {
                    $variantData['is_active'] = isset($variantData['is_active']) && $variantData['is_active'];

                    // Update existing variant
                    if (!empty($variantData['id'])) {
                        $variant = ProductVariant::where('id', $variantData['id'])
                            ->where('product_id', $product->id)
                            ->first();

                        if ($variant) {
                            // Check if SKU changed and ensure uniqueness
                            $newSku = $product->sku . '-' . $variantData['sku_suffix'];
                            if ($variant->sku !== $newSku) {
                                $counter = 1;
                                $originalSku = $newSku;
                                while (ProductVariant::where('sku', $newSku)
                                    ->where('id', '!=', $variant->id)
                                    ->exists()) {
                                    $newSku = $originalSku . '-' . $counter++;
                                }
                            }

                            // Handle variant image
                            $variantImages = is_array($variant->images) ? $variant->images : [];

                            if (isset($variantData['remove_image']) && $variantData['remove_image'] == '1') {
                                // Delete existing images
                                foreach ($variantImages as $image) {
                                    ImageStorageHelper::delete($image);
                                }
                                $variantImages = [];
                            }

                            if ($request->hasFile("variants.{$index}.image")) {
                                // Delete old images if new image is uploaded
                                foreach ($variantImages as $image) {
                                    ImageStorageHelper::delete($image);
                                }
                                $variantImages = [ImageStorageHelper::store(
                                    $request->file("variants.{$index}.image"),
                                    'variant_images'
                                )];
                            }

                            $variant->update([
                                'size' => $variantData['size'],
                                'color' => $variantData['color'],
                                'sku' => $newSku,
                                'price' => $variantData['price'],
                                'stock_quantity' => $variantData['stock_quantity'],
                                'is_active' => $variantData['is_active'],
                                'images' => $variantImages,
                            ]);

                            $existingVariantIds[] = $variant->id;
                        }
                    } else {
                        // Create new variant
                        $variantSku = $product->sku . '-' . $variantData['sku_suffix'];
                        $counter = 1;
                        $originalSku = $variantSku;
                        while (ProductVariant::where('sku', $variantSku)->exists()) {
                            $variantSku = $originalSku . '-' . $counter++;
                        }

                        // Handle variant image for new variant
                        $variantImages = [];
                        if ($request->hasFile("variants.{$index}.image")) {
                            $variantImages[] = ImageStorageHelper::store(
                                $request->file("variants.{$index}.image"),
                                'variant_images'
                            );
                        }

                        $newVariant = ProductVariant::create([
                            'product_id' => $product->id,
                            'size' => $variantData['size'],
                            'color' => $variantData['color'],
                            'sku' => $variantSku,
                            'price' => $variantData['price'],
                            'stock_quantity' => $variantData['stock_quantity'],
                            'is_active' => $variantData['is_active'],
                            'images' => $variantImages,
                        ]);

                        $existingVariantIds[] = $newVariant->id;
                    }
                }

                // Delete variants marked for deletion
                if ($request->filled('delete_variants')) {
                    $variantsToDelete = ProductVariant::whereIn('id', $request->delete_variants)
                        ->where('product_id', $product->id)
                        ->get();
                    foreach ($variantsToDelete as $variant) {
                        if (is_array($variant->images)) {
                            foreach ($variant->images as $image) {
                                ImageStorageHelper::delete($image);
                            }
                        }
                        $variant->delete();
                    }
                }

                // Delete variants that were removed from the form
                $removedVariants = ProductVariant::where('product_id', $product->id)
                    ->whereNotIn('id', $existingVariantIds)
                    ->get();

                foreach ($removedVariants as $variant) {
                    if (is_array($variant->images)) {
                        foreach ($variant->images as $image) {
                            ImageStorageHelper::delete($image);
                        }
                    }
                    $variant->delete();
                }
            } elseif (!$hasVariants) {
                // If variants disabled, delete all variants and their images
                $variants = $product->variants()->get();
                foreach ($variants as $variant) {
                    if (is_array($variant->images)) {
                        foreach ($variant->images as $image) {
                            ImageStorageHelper::delete($image);
                        }
                    }
                    $variant->delete();
                }
            }

            DB::commit();

            return redirect()
                ->route('admin.products.show', $product)
                ->with('success', 'Product updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update product: ' . $e->getMessage());
        }
    }

    public function destroy(Product $product)
    {
        // Delete featured image
        if (!empty($product->featured_image)) {
            ImageStorageHelper::delete($product->featured_image);
        }

        // Delete gallery images
        if (is_array($product->gallery)) {
            foreach ($product->gallery as $path) {
                ImageStorageHelper::delete($path);
            }
        }

        // Delete variant images
        foreach ($product->variants as $variant) {
            if (is_array($variant->images)) {
                foreach ($variant->images as $image) {
                    ImageStorageHelper::delete($image);
                }
            }
        }

        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product deleted successfully!');
    }

    public function toggleStatus(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);

        $status = $product->is_active ? 'activated' : 'deactivated';

        return response()->json([
            'success' => true,
            'message' => "Product {$status} successfully!",
            'is_active' => $product->is_active
        ]);
    }

    public function toggleFeatured(Product $product)
    {
        $product->update(['is_featured' => !$product->is_featured]);

        $status = $product->is_featured ? 'featured' : 'unfeatured';

        return response()->json([
            'success' => true,
            'message' => "Product {$status} successfully!",
            'is_featured' => $product->is_featured
        ]);
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete,feature,unfeature',
            'products' => 'required|array',
            'products.*' => 'exists:products,id'
        ]);

        $products = Product::whereIn('id', $request->products);
        $count = $products->count();

        switch ($request->action) {
            case 'activate':
                $products->update(['is_active' => true]);
                $message = "{$count} products activated successfully!";
                break;
            case 'deactivate':
                $products->update(['is_active' => false]);
                $message = "{$count} products deactivated successfully!";
                break;
            case 'feature':
                $products->update(['is_featured' => true]);
                $message = "{$count} products featured successfully!";
                break;
            case 'unfeature':
                $products->update(['is_featured' => false]);
                $message = "{$count} products unfeatured successfully!";
                break;
            case 'delete':
                $productsToDelete = $products->get();
                foreach ($productsToDelete as $product) {
                    // Delete featured image
                    if (!empty($product->featured_image)) {
                        ImageStorageHelper::delete($product->featured_image);
                    }

                    // Delete gallery images
                    if (is_array($product->gallery)) {
                        foreach ($product->gallery as $path) {
                            ImageStorageHelper::delete($path);
                        }
                    }

                    // Delete variant images
                    foreach ($product->variants as $variant) {
                        if (is_array($variant->images)) {
                            foreach ($variant->images as $image) {
                                ImageStorageHelper::delete($image);
                            }
                        }
                    }

                    $product->delete();
                }
                $message = "{$count} products deleted successfully!";
                break;
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success', $message);
    }
}
