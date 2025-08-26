<?php
//
//    namespace App\Http\Controllers\Admin;
//
//    use App\Models\GalleryItem;
//    use App\Models\Product;
//    use Illuminate\Http\Request;
//    use Illuminate\Support\Facades\Storage;
//    use Illuminate\Support\Str;
//
//    class GalleryController extends AdminController
//    {
//        protected $model = GalleryItem::class;
//
//        public function index()
//        {
//            $galleryItems = GalleryItem::with('product')
//                ->ordered()
//                ->get();
//
//            return view('admin.gallery.index', compact('galleryItems'));
//        }
//
//        public function create()
//        {
//            $products = Product::active()
//                ->select('id', 'name', 'slug')
//                ->orderBy('name')
//                ->get();
//
//            return view('admin.gallery.create', compact('products'));
//        }
//
//        public function store(Request $request)
//        {
//            $request->validate([
//                'image' => 'required_if:source_type,upload|nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
//                'image_url' => 'required_if:source_type,instagram,customer|nullable|url',
//                'caption' => 'nullable|string|max:255',
//                'hashtags' => 'nullable|string',
//                'link' => 'nullable|url',
//                'product_id' => 'nullable|exists:products,id',
//                'source_type' => 'required|in:upload,instagram,customer',
//                'is_featured' => 'boolean',
//                'is_active' => 'boolean',
//                'sort_order' => 'nullable|integer|min:0',
//            ]);
//
//            $data = $request->only([
//                'caption', 'link', 'product_id', 'source_type', 'sort_order'
//            ]);
//
//            // Handle hashtags
//            if ($request->hashtags) {
//                $hashtags = array_map('trim', explode(',', $request->hashtags));
//                $hashtags = array_map(fn($tag) => ltrim($tag, '#'), $hashtags);
//                $data['hashtags'] = array_filter($hashtags);
//            }
//
//            $data['is_featured'] = $request->boolean('is_featured', false);
//            $data['is_active'] = $request->boolean('is_active', true);
//
//            // Handle image
//            if ($request->source_type === 'upload' && $request->hasFile('image')) {
//                $image = $request->file('image');
//                $filename = time() . '_gallery.' . $image->getClientOriginalExtension();
//                $path = $image->storeAs('gallery', $filename, 'public');
//                $data['image'] = $path;
//            } else {
//                $data['image'] = $request->image_url;
//            }
//
//            GalleryItem::create($data);
//
//            return redirect()->route('admin.gallery.index')
//                ->with('success', 'Gallery item created successfully.');
//        }
//
//        public function show(GalleryItem $galleryItem)
//        {
//            $galleryItem->load('product');
//            return view('admin.gallery.show', compact('galleryItem'));
//        }
//
//        public function edit(GalleryItem $galleryItem)
//        {
//            $products = Product::active()
//                ->select('id', 'name', 'slug')
//                ->orderBy('name')
//                ->get();
//
//            return view('admin.gallery.edit', compact('galleryItem', 'products'));
//        }
//
//        public function update(Request $request, GalleryItem $galleryItem)
//        {
//            $request->validate([
//                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
//                'image_url' => 'nullable|url',
//                'caption' => 'nullable|string|max:255',
//                'hashtags' => 'nullable|string',
//                'link' => 'nullable|url',
//                'product_id' => 'nullable|exists:products,id',
//                'source_type' => 'required|in:upload,instagram,customer',
//                'is_featured' => 'boolean',
//                'is_active' => 'boolean',
//                'sort_order' => 'nullable|integer|min:0',
//            ]);
//
//            $data = $request->only([
//                'caption', 'link', 'product_id', 'source_type', 'sort_order'
//            ]);
//
//            // Handle hashtags
//            if ($request->hashtags) {
//                $hashtags = array_map('trim', explode(',', $request->hashtags));
//                $hashtags = array_map(fn($tag) => ltrim($tag, '#'), $hashtags);
//                $data['hashtags'] = array_filter($hashtags);
//            }
//
//            $data['is_featured'] = $request->boolean('is_featured');
//            $data['is_active'] = $request->boolean('is_active');
//
//            // Handle image update
//            if ($request->hasFile('image')) {
//                // Delete old image if it's local
//                if ($galleryItem->image && !filter_var($galleryItem->image, FILTER_VALIDATE_URL)) {
//                    Storage::disk('public')->delete($galleryItem->image);
//                }
//
//                $image = $request->file('image');
//                $filename = time() . '_gallery.' . $image->getClientOriginalExtension();
//                $path = $image->storeAs('gallery', $filename, 'public');
//                $data['image'] = $path;
//            } elseif ($request->image_url) {
//                $data['image'] = $request->image_url;
//            }
//
//            $galleryItem->update($data);
//
//            return redirect()->route('admin.gallery.index')
//                ->with('success', 'Gallery item updated successfully.');
//        }
//
//        public function destroy(GalleryItem $galleryItem)
//        {
//            // Delete image file if it's local
//            if ($galleryItem->image && !filter_var($galleryItem->image, FILTER_VALIDATE_URL)) {
//                Storage::disk('public')->delete($galleryItem->image);
//            }
//
//            $galleryItem->delete();
//
//            return redirect()->route('admin.gallery.index')
//                ->with('success', 'Gallery item deleted successfully.');
//        }
//
//        public function toggleStatus(GalleryItem $galleryItem)
//        {
//            $galleryItem->update(['is_active' => !$galleryItem->is_active]);
//
//            $status = $galleryItem->is_active ? 'activated' : 'deactivated';
//            return back()->with('success', "Gallery item {$status} successfully.");
//        }
//
//        public function toggleFeatured(GalleryItem $galleryItem)
//        {
//            $galleryItem->update(['is_featured' => !$galleryItem->is_featured]);
//
//            $status = $galleryItem->is_featured ? 'marked as featured' : 'unmarked as featured';
//            return back()->with('success', "Gallery item {$status} successfully.");
//        }
//    }


namespace App\Http\Controllers\Admin;

use App\Helpers\ImageStorageHelper;
use App\Models\GalleryItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GalleryController extends AdminController
{
    protected $model = GalleryItem::class;

    public function index()
    {
        $galleryItems = GalleryItem::with('product')
            ->ordered()
            ->get();

        return view('admin.gallery.index', compact('galleryItems'));
    }

    public function create()
    {
        $products = Product::active()
            ->select('id', 'name', 'slug')
            ->orderBy('name')
            ->get();

        return view('admin.gallery.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required_if:source_type,upload|nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'image_url' => 'required_if:source_type,instagram,customer|nullable|url',
            'caption' => 'nullable|string|max:255',
            'hashtags' => 'nullable|string',
            'link' => 'nullable|url',
            'product_id' => 'nullable|exists:products,id',
            'source_type' => 'required|in:upload,instagram,customer',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data = $request->only([
            'caption', 'link', 'product_id', 'source_type', 'sort_order'
        ]);

        // Handle hashtags
        if ($request->hashtags) {
            $hashtags = array_map('trim', explode(',', $request->hashtags));
            $hashtags = array_map(fn($tag) => ltrim($tag, '#'), $hashtags);
            $data['hashtags'] = array_filter($hashtags);
        }

        $data['is_featured'] = $request->boolean('is_featured', false);
        $data['is_active'] = $request->boolean('is_active', true);

        // Handle image using our new storage helper
        if ($request->source_type === 'upload' && $request->hasFile('image')) {
            $data['image'] = ImageStorageHelper::store($request->file('image'), 'gallery');
        } else {
            $data['image'] = $request->image_url;
        }

        GalleryItem::create($data);

        return redirect()->route('admin.gallery.index')
            ->with('success', 'Gallery item created successfully.');
    }

    public function show(GalleryItem $galleryItem)
    {
        $galleryItem->load('product');
        return view('admin.gallery.show', compact('galleryItem'));
    }

    public function edit(GalleryItem $galleryItem)
    {
        $products = Product::active()
            ->select('id', 'name', 'slug')
            ->orderBy('name')
            ->get();

        return view('admin.gallery.edit', compact('galleryItem', 'products'));
    }

    public function update(Request $request, GalleryItem $galleryItem)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'image_url' => 'nullable|url',
            'caption' => 'nullable|string|max:255',
            'hashtags' => 'nullable|string',
            'link' => 'nullable|url',
            'product_id' => 'nullable|exists:products,id',
            'source_type' => 'required|in:upload,instagram,customer',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data = $request->only([
            'caption', 'link', 'product_id', 'source_type', 'sort_order'
        ]);

        // Handle hashtags
        if ($request->hashtags) {
            $hashtags = array_map('trim', explode(',', $request->hashtags));
            $hashtags = array_map(fn($tag) => ltrim($tag, '#'), $hashtags);
            $data['hashtags'] = array_filter($hashtags);
        }

        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active');

        // Handle image update using our new storage helper
        if ($request->hasFile('image')) {
            // Delete old image if it's local
            if ($galleryItem->image && !filter_var($galleryItem->image, FILTER_VALIDATE_URL)) {
                ImageStorageHelper::delete($galleryItem->image);
            }

            $data['image'] = ImageStorageHelper::store($request->file('image'), 'gallery');
        } elseif ($request->image_url) {
            $data['image'] = $request->image_url;
        }

        $galleryItem->update($data);

        return redirect()->route('admin.gallery.index')
            ->with('success', 'Gallery item updated successfully.');
    }

    public function destroy(GalleryItem $galleryItem)
    {
        // Delete image file if it's local using our new helper
        if ($galleryItem->image && !filter_var($galleryItem->image, FILTER_VALIDATE_URL)) {
            ImageStorageHelper::delete($galleryItem->image);
        }

        $galleryItem->delete();

        return redirect()->route('admin.gallery.index')
            ->with('success', 'Gallery item deleted successfully.');
    }

    public function toggleStatus(GalleryItem $galleryItem)
    {
        $galleryItem->update(['is_active' => !$galleryItem->is_active]);

        $status = $galleryItem->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Gallery item {$status} successfully.");
    }

    public function toggleFeatured(GalleryItem $galleryItem)
    {
        $galleryItem->update(['is_featured' => !$galleryItem->is_featured]);

        $status = $galleryItem->is_featured ? 'marked as featured' : 'unmarked as featured';
        return back()->with('success', "Gallery item {$status} successfully.");
    }
}
