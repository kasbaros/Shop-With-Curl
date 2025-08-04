<?php

    namespace App\Http\Controllers\Admin;

    use App\Models\Category;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
    use Illuminate\Validation\Rule;

    class CategoryController extends AdminController
    {
        public function index(Request $request)
        {
            $query = Category::withCount('products');

            // Search
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'ILIKE', "%{$search}%")
                        ->orWhere('description', 'ILIKE', "%{$search}%");
                });
            }

            // Status filter
            if ($request->filled('status')) {
                $query->where('is_active', $request->status === 'active');
            }

            // Sort
            $sortField = $request->get('sort', 'created_at');
            $sortDirection = $request->get('direction', 'desc');
            $query->orderBy($sortField, $sortDirection);

            $categories = $query->paginate(15)->withQueryString();

            return view('admin.categories.index', array_merge(
                $this->getAdminViewData(),
                compact('categories')
            ));
        }

        public function create()
        {
            return view('admin.categories.create', $this->getAdminViewData());
        }

        public function store(Request $request)
        {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'slug' => 'nullable|string|max:255|unique:categories,slug',
                'description' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'is_active' => 'boolean',
                'meta_title' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string|max:500',
                'sort_order' => 'nullable|integer|min:0',
            ]);

            // Generate slug if not provided
            if (empty($validated['slug'])) {
                $validated['slug'] = Str::slug($validated['name']);

                // Ensure unique slug
                $originalSlug = $validated['slug'];
                $counter = 1;
                while (Category::where('slug', $validated['slug'])->exists()) {
                    $validated['slug'] = $originalSlug . '-' . $counter++;
                }
            }

            // Handle image upload
            if ($request->hasFile('image')) {
                $validated['image_path'] = $request->file('image')->store('categories', 'public');
            }

            $category = Category::create($validated);

            return redirect()
                ->route('admin.categories.show', $category)
                ->with('success', 'Category created successfully!');
        }

        public function show(Category $category)
        {
            $category->loadCount('products');
            $recentProducts = $category->products()
                ->with('images')
                ->latest()
                ->limit(8)
                ->get();

            return view('admin.categories.show', array_merge(
                $this->getAdminViewData(),
                compact('category', 'recentProducts')
            ));
        }

        public function edit(Category $category)
        {
            return view('admin.categories.edit', array_merge(
                $this->getAdminViewData(),
                compact('category')
            ));
        }

        public function update(Request $request, Category $category)
        {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'slug' => ['nullable', 'string', 'max:255', Rule::unique('categories')->ignore($category)],
                'description' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'is_active' => 'boolean',
                'meta_title' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string|max:500',
                'sort_order' => 'nullable|integer|min:0',
                'remove_image' => 'boolean',
            ]);

            // Generate slug if not provided
            if (empty($validated['slug'])) {
                $validated['slug'] = Str::slug($validated['name']);

                // Ensure unique slug
                $originalSlug = $validated['slug'];
                $counter = 1;
                while (Category::where('slug', $validated['slug'])
                    ->where('id', '!=', $category->id)
                    ->exists()) {
                    $validated['slug'] = $originalSlug . '-' . $counter++;
                }
            }

            // Handle image removal
            if ($request->boolean('remove_image') && $category->image_path) {
                Storage::disk('public')->delete($category->image_path);
                $validated['image_path'] = null;
            }

            // Handle new image upload
            if ($request->hasFile('image')) {
                // Remove old image
                if ($category->image_path) {
                    Storage::disk('public')->delete($category->image_path);
                }
                $validated['image_path'] = $request->file('image')->store('categories', 'public');
            }

            $category->update($validated);

            return redirect()
                ->route('admin.categories.show', $category)
                ->with('success', 'Category updated successfully!');
        }

        public function destroy(Category $category)
        {
            // Check if category has products
            if ($category->products()->count() > 0) {
                return redirect()
                    ->route('admin.categories.index')
                    ->with('error', 'Cannot delete category with existing products. Please move or delete all products first.');
            }

            // Remove image
            if ($category->image_path) {
                Storage::disk('public')->delete($category->image_path);
            }

            $category->delete();

            return redirect()
                ->route('admin.categories.index')
                ->with('success', 'Category deleted successfully!');
        }

        public function toggleStatus(Category $category)
        {
            $category->update(['is_active' => !$category->is_active]);

            $status = $category->is_active ? 'activated' : 'deactivated';

            return response()->json([
                'success' => true,
                'message' => "Category {$status} successfully!",
                'is_active' => $category->is_active
            ]);
        }
    }
