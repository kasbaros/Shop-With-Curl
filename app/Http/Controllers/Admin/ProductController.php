<?php

    namespace App\Http\Controllers\Admin;

    use App\Helpers\ImageStorageHelper;
    use App\Models\{Product, Category};
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
//                'images' => 'nullable|array',
//                'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
//            ]);
//
//            // Generate slug if not provided
//            if (empty($validated['slug'])) {
//                $validated['slug'] = Str::slug($validated['name']);
//
//                // Ensure unique slug
//                $originalSlug = $validated['slug'];
//                $counter = 1;
//                while (Product::where('slug', $validated['slug'])->exists()) {
//                    $validated['slug'] = $originalSlug . '-' . $counter++;
//                }
//            }
//
//            // Transform single category_id into array for attaching
//            $categories = [$validated['category_id']];
//            unset($validated['category_id']);
//
//            $product = Product::create($validated);
//
//            // Attach categories
//            $product->categories()->attach($categories);
//
//            // Handle image uploads
//            if ($request->hasFile('images')) {
//                foreach ($request->file('images') as $image) {
//                    $product->addMedia($image)->toMediaCollection('images');
//                }
//            }
//
//            return redirect()
//                ->route('admin.products.show', $product)
//                ->with('success', 'Product created successfully!');
//        }

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

            // Handle featured image (stored under /public_html/storage/products)
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
            $product = Product::create($validated);

            // Attach categories
            $product->categories()->attach($categories);

            return redirect()
                ->route('admin.products.show', $product)
                ->with('success', 'Product created successfully!');
        }

        public function show(Product $product)
        {
            $product->load(['categories', 'reviews.user']);

            return view('admin.products.show', array_merge(
                $this->getAdminViewData(),
                compact('product')
            ));
        }

        public function edit(Product $product)
        {
            $categories = Category::where('is_active', true)->get();
            $product->load(['categories']);

            return view('admin.products.edit', array_merge(
                $this->getAdminViewData(),
                compact('product', 'categories')
            ));
        }

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
//                'images' => 'nullable|array',
//                'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
//            ]);
//
//            // Generate slug if not provided
//            if (empty($validated['slug'])) {
//                $validated['slug'] = Str::slug($validated['name']);
//
//                // Ensure unique slug
//                $originalSlug = $validated['slug'];
//                $counter = 1;
//                while (Product::where('slug', $validated['slug'])
//                    ->where('id', '!=', $product->id)
//                    ->exists()) {
//                    $validated['slug'] = $originalSlug . '-' . $counter++;
//                }
//            }
//
//            // Remove categories from validated data as it's handled separately
//            $categories = $validated['categories'];
//            unset($validated['categories']);
//
//            $product->update($validated);
//
//            // Sync categories
//            $product->categories()->sync($categories);
//
//            // Remove selected existing images
//            if ($request->filled('remove_images')) {
//                foreach ($request->input('remove_images') as $mediaId) {
//                    $media = $product->media()->where('id', $mediaId)->first();
//                    if ($media) {
//                        $media->delete();
//                    }
//                }
//            }
//
//            // Handle new image uploads
//            if ($request->hasFile('images')) {
//                foreach ($request->file('images') as $image) {
//                    $product->addMedia($image)->toMediaCollection('images');
//                }
//            }
//
//            return redirect()
//                ->route('admin.products.show', $product)
//                ->with('success', 'Product updated successfully!');
//        }



//        public function destroy(Product $product)
//        {
//            // Remove all media files
//            $product->clearMediaCollection('images');
//            $product->clearMediaCollection('gallery');
//
//            $product->delete();
//
//            return redirect()
//                ->route('admin.products.index')
//                ->with('success', 'Product deleted successfully!');
//        }

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
                'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
                'images' => 'nullable|array',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:4096',
                'remove_images' => 'nullable|array',
                'remove_images.*' => 'integer|min:0',
            ]);

            if (empty($validated['slug'])) {
                $validated['slug'] = Str::slug($validated['name']);
                $base = $validated['slug'];
                $i = 1;
                while (Product::where('slug', $validated['slug'])
                    ->where('id', '!=', $product->id)->exists()) {
                    $validated['slug'] = $base . '-' . $i++;
                }
            }

            // Handle featured image replacement
            if ($request->hasFile('featured_image')) {
                if (!empty($product->featured_image)) {
                    \App\Helpers\ImageStorageHelper::delete($product->featured_image);
                }
                $validated['featured_image'] = ImageStorageHelper::store(
                    $request->file('featured_image'),
                    'products'
                );
            }

            // Pull current gallery as array
            $gallery = is_array($product->gallery) ? $product->gallery : [];

            // Remove images by index if requested
            if ($request->filled('remove_images')) {
                foreach ($request->input('remove_images') as $idx) {
                    if (isset($gallery[$idx])) {
                        \App\Helpers\ImageStorageHelper::delete($gallery[$idx]);
                        unset($gallery[$idx]);
                    }
                }
                // Reindex array
                $gallery = array_values($gallery);
            }

            // Add new gallery images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $img) {
                    $gallery[] = ImageStorageHelper::store($img, 'products');
                }
            }

            // Move categories out of validated data to update separately
            $categories = $validated['categories'];
            unset($validated['categories']);

            // Save updated fields + gallery
            $validated['gallery'] = $gallery;
            $product->update($validated);

            // Sync categories
            $product->categories()->sync($categories);

            return redirect()
                ->route('admin.products.show', $product)
                ->with('success', 'Product updated successfully!');
        }

        public function destroy(Product $product)
        {
            // Delete featured image
            if (!empty($product->featured_image)) {
                \App\Helpers\ImageStorageHelper::delete($product->featured_image);
            }

            // Delete gallery images
            if (is_array($product->gallery)) {
                foreach ($product->gallery as $path) {
                    \App\Helpers\ImageStorageHelper::delete($path);
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
                        $product->clearMediaCollection('images');
                        $product->clearMediaCollection('gallery');
                    }
                    $products->delete();
                    $message = "{$count} products deleted successfully!";
                    break;
            }

            return redirect()
                ->route('admin.products.index')
                ->with('success', $message);
        }
    }
