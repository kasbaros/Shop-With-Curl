<?php

    namespace App\Http\Controllers\Admin;

    use App\Models\Lookbook;
    use App\Models\LookbookItem;
    use App\Models\Product;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Storage;

    class LookBookController extends AdminController
    {
        protected $model = Lookbook::class;

        public function index()
        {
            $lookbooks = Lookbook::withCount('items')
                ->orderBy('priority')
                ->orderBy('created_at', 'desc')
                ->get();

            return view('admin.lookbooks.index', compact('lookbooks'));
        }

        public function create()
        {
            $products = Product::active()
                ->select('id', 'name', 'slug', 'price', 'sale_price')
                ->orderBy('name')
                ->get();

            return view('admin.lookbooks.create', compact('products'));
        }

        public function store(Request $request)
        {
            $request->validate([
                'title' => 'required|string|max:255',
                'label' => 'nullable|string|max:100',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'active' => 'boolean',
                'priority' => 'nullable|integer|min:0',
                'starts_at' => 'nullable|date',
                'ends_at' => 'nullable|date|after:starts_at',
                'product_ids' => 'nullable|array',
                'product_ids.*' => 'exists:products,id',
            ]);

            $data = $request->only(['title', 'label', 'priority']);
            $data['active'] = $request->boolean('active', true);
            $data['starts_at'] = $request->starts_at ? now()->parse($request->starts_at) : null;
            $data['ends_at'] = $request->ends_at ? now()->parse($request->ends_at) : null;

            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '_lookbook.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('lookbooks', $filename, 'public');
                $data['image'] = Storage::url($path);
            }

            $lookbook = Lookbook::create($data);

            // Add selected products
            if ($request->product_ids) {
                foreach ($request->product_ids as $index => $productId) {
                    LookbookItem::create([
                        'lookbook_id' => $lookbook->id,
                        'product_id' => $productId,
                        'sort_order' => $index,
                    ]);
                }
            }

            return redirect()->route('admin.lookbooks.index')
                ->with('success', 'Lookbook created successfully.');
        }

        public function show(Lookbook $lookbook)
        {
            $lookbook->load('items.product');
            return view('admin.lookbooks.show', compact('lookbook'));
        }

        public function edit(Lookbook $lookbook)
        {
            $lookbook->load('items.product');
            $products = Product::active()
                ->select('id', 'name', 'slug', 'price', 'sale_price')
                ->orderBy('name')
                ->get();

            return view('admin.lookbooks.edit', compact('lookbook', 'products'));
        }

        public function update(Request $request, Lookbook $lookbook)
        {
            $request->validate([
                'title' => 'required|string|max:255',
                'label' => 'nullable|string|max:100',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'active' => 'boolean',
                'priority' => 'nullable|integer|min:0',
                'starts_at' => 'nullable|date',
                'ends_at' => 'nullable|date|after:starts_at',
                'product_ids' => 'nullable|array',
                'product_ids.*' => 'exists:products,id',
            ]);

            $data = $request->only(['title', 'label', 'priority']);
            $data['active'] = $request->boolean('active');
            $data['starts_at'] = $request->starts_at ? now()->parse($request->starts_at) : null;
            $data['ends_at'] = $request->ends_at ? now()->parse($request->ends_at) : null;

            // Handle image update
            if ($request->hasFile('image')) {
                // Delete old image
                if ($lookbook->image) {
                    Storage::disk('public')->delete(str_replace('/storage/', '', $lookbook->image));
                }

                $image = $request->file('image');
                $filename = time() . '_lookbook.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('lookbooks', $filename, 'public');
                $data['image'] = Storage::url($path);
            }

            $lookbook->update($data);

            // Update products
            $lookbook->items()->delete(); // Remove existing items
            if ($request->product_ids) {
                foreach ($request->product_ids as $index => $productId) {
                    LookbookItem::create([
                        'lookbook_id' => $lookbook->id,
                        'product_id' => $productId,
                        'sort_order' => $index,
                    ]);
                }
            }

            return redirect()->route('admin.lookbooks.index')
                ->with('success', 'Lookbook updated successfully.');
        }

        public function destroy(Lookbook $lookbook)
        {
            // Delete image
            if ($lookbook->image) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $lookbook->image));
            }

            $lookbook->delete();

            return redirect()->route('admin.lookbooks.index')
                ->with('success', 'Lookbook deleted successfully.');
        }

        public function toggleStatus(Lookbook $lookbook)
        {
            $lookbook->update(['active' => !$lookbook->active]);

            $status = $lookbook->active ? 'activated' : 'deactivated';
            return back()->with('success', "Lookbook {$status} successfully.");
        }
    }
