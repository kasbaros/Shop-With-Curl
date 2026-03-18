<?php

    namespace App\Http\Controllers\Admin;

    use App\Helpers\ImageStorageHelper;
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
                ->with('media')
                ->orderBy('name')
                ->get();

            return view('admin.lookbooks.create', compact('products'));
        }


        public function store(Request $request)
        {
            $data = $request->validate([
                'title' => 'required|string|max:255',
                'label' => 'nullable|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:6144',
                'active' => 'boolean',
                'priority' => 'nullable|integer|min:0',
                'product_ids' => 'nullable|array',
                'product_ids.*' => 'exists:products,id',
            ]);

            if ($request->hasFile('image')) {
                $data['image'] = ImageStorageHelper::store($request->file('image'), 'lookbooks');
            }

            $data['active'] = $request->boolean('active', true);
            $data['starts_at'] = $request->input('starts_at') ?: null;
            $data['ends_at'] = $request->input('ends_at') ?: null;

            $lookbook = Lookbook::create($data);

            // Add products to lookbook
            if ($request->has('product_ids')) {
                $sortOrder = 0;
                foreach ($request->product_ids as $productId) {
                    LookbookItem::create([
                        'lookbook_id' => $lookbook->id,
                        'product_id' => $productId,
                        'sort_order' => $sortOrder++,
                    ]);
                }
            }

            return redirect()->route('admin.lookbooks.show', $lookbook)
                ->with('success', 'Lookbook created successfully.');
        }

        public function update(Request $request, Lookbook $lookbook)
        {
            $data = $request->validate([
                'title' => 'required|string|max:255',
                'label' => 'nullable|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:6144',
                'active' => 'boolean',
                'priority' => 'nullable|integer|min:0',
                'product_ids' => 'nullable|array',
                'product_ids.*' => 'exists:products,id',
            ]);

            if ($request->hasFile('image')) {
                if (!empty($lookbook->image)) {
                    ImageStorageHelper::delete($lookbook->image);
                }
                $data['image'] = ImageStorageHelper::store($request->file('image'), 'lookbooks');
            }

            $data['active'] = $request->boolean('active', $lookbook->active);
            $data['starts_at'] = $request->input('starts_at') ?: null;
            $data['ends_at'] = $request->input('ends_at') ?: null;

            $lookbook->update($data);

            // Update products in lookbook
            if ($request->has('product_ids')) {
                // Delete existing items
                LookbookItem::where('lookbook_id', $lookbook->id)->delete();

                // Add new items
                $sortOrder = 0;
                foreach ($request->product_ids as $productId) {
                    LookbookItem::create([
                        'lookbook_id' => $lookbook->id,
                        'product_id' => $productId,
                        'sort_order' => $sortOrder++,
                    ]);
                }
            } else {
                // If no products selected, remove all existing items
                LookbookItem::where('lookbook_id', $lookbook->id)->delete();
            }

            return redirect()->route('admin.lookbooks.show', $lookbook)
                ->with('success', 'Lookbook updated successfully.');
        }

        public function destroy(Lookbook $lookbook)
        {
            if (!empty($lookbook->image)) {
                ImageStorageHelper::delete($lookbook->image);
            }

            $lookbook->delete();

            return redirect()->route('admin.lookbooks.index')
                ->with('success', 'Lookbook deleted successfully.');
        }



        public function show(Lookbook $lookbook)
        {
            $lookbook->load('items.product.media');
            return view('admin.lookbooks.show', compact('lookbook'));
        }

        public function edit(Lookbook $lookbook)
        {
            $lookbook->load('items.product.media');
            $products = Product::active()
                ->with('media')
                ->orderBy('name')
                ->get();

            return view('admin.lookbooks.edit', compact('lookbook', 'products'));
        }

        public function toggleStatus(Lookbook $lookbook)
        {
            $lookbook->update(['active' => !$lookbook->active]);

            $status = $lookbook->active ? 'activated' : 'deactivated';
            return back()->with('success', "Lookbook {$status} successfully.");
        }
    }
