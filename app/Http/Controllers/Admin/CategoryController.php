<?php

    namespace App\Http\Controllers\Admin;

    use App\Helpers\ImageStorageHelper;
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
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
                'parent_id' => 'nullable|exists:categories,id',
                'sort_order' => 'nullable|integer|min:0',
                'is_active' => 'boolean',
                'meta_title' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string|max:500',
            ]);

            if (empty($validated['slug'])) {
                $base = \Str::slug($validated['name']);
                $validated['slug'] = $base;
                $i = 1;
                while (Category::where('slug', $validated['slug'])->exists()) {
                    $validated['slug'] = $base . '-' . $i++;
                }
            }

            if ($request->hasFile('image')) {
                $validated['image'] = ImageStorageHelper::store($request->file('image'), 'categories');
            }

            $validated['is_active'] = $request->boolean('is_active', true);

            $category = Category::create($validated);

            return redirect()->route('admin.categories.show', $category)
                ->with('success', 'Category created successfully.');
        }

        public function show(Category $category)
        {
            $category->loadCount('products')->load('media');
            $recentProducts = $category->products()
                ->with('media')
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
                'slug' => ['nullable','string','max:255', Rule::unique('categories')->ignore($category)],
                'description' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
                'parent_id' => 'nullable|exists:categories,id',
                'sort_order' => 'nullable|integer|min:0',
                'is_active' => 'boolean',
                'meta_title' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string|max:500',
            ]);

            if (empty($validated['slug'])) {
                $base = \Str::slug($validated['name']);
                $validated['slug'] = $base;
                $i = 1;
                while (Category::where('slug', $validated['slug'])->where('id','!=',$category->id)->exists()) {
                    $validated['slug'] = $base . '-' . $i++;
                }
            }

            if ($request->hasFile('image')) {
                if (!empty($category->image)) {
                    ImageStorageHelper::delete($category->image);
                }
                $validated['image'] = ImageStorageHelper::store($request->file('image'), 'categories');
            }

            $validated['is_active'] = $request->boolean('is_active', $category->is_active);

            $category->update($validated);

            return redirect()->route('admin.categories.show', $category)
                ->with('success', 'Category updated successfully.');
        }

        public function destroy(Category $category)
        {
            if (!empty($category->image)) {
                ImageStorageHelper::delete($category->image);
            }

            $category->delete();

            return redirect()->route('admin.categories.index')
                ->with('success', 'Category deleted successfully.');
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
