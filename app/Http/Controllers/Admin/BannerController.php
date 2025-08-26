<?php
//
//    namespace App\Http\Controllers\Admin;
//
//    use App\Models\Banner;
//    use Illuminate\Http\Request;
//    use Illuminate\Support\Facades\Storage;
//    use Illuminate\Support\Str;
//
//    class BannerController extends AdminController
//    {
//        protected $model = Banner::class;
//
//        public function index()
//        {
//            $banners = Banner::ordered()->get();
//            return view('admin.banners.index', compact('banners'));
//        }
//
//        public function create()
//        {
//            return view('admin.banners.create');
//        }
//
//        public function store(Request $request)
//        {
//            $request->validate([
//                'title' => 'required|string|max:255',
//                'subtitle' => 'nullable|string|max:255',
//                'description' => 'nullable|string',
//                'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
//                'button_text' => 'nullable|string|max:100',
//                'button_link' => 'nullable|url|max:255',
//                'secondary_button_text' => 'nullable|string|max:100',
//                'secondary_button_link' => 'nullable|url|max:255',
//                'sort_order' => 'nullable|integer|min:0',
//                'is_active' => 'boolean'
//            ]);
//
//            $data = $request->only([
//                'title', 'subtitle', 'description', 'button_text', 'button_link',
//                'secondary_button_text', 'secondary_button_link', 'sort_order'
//            ]);
//
//            $data['is_active'] = $request->boolean('is_active', true);
//
//            // Handle image upload
//            if ($request->hasFile('image')) {
//                $image = $request->file('image');
//                $filename = time() . '_' . Str::slug($request->title) . '.' . $image->getClientOriginalExtension();
//                $path = $image->storeAs('banners', $filename, 'public');
//                $data['image'] = $path;
//            }
//
//            Banner::create($data);
//
//            return redirect()->route('admin.banners.index')
//                ->with('success', 'Banner created successfully.');
//        }
//
//        public function show(Banner $banner)
//        {
//            return view('admin.banners.show', compact('banner'));
//        }
//
//        public function edit(Banner $banner)
//        {
//            return view('admin.banners.edit', compact('banner'));
//        }
//
//        public function update(Request $request, Banner $banner)
//        {
//            $request->validate([
//                'title' => 'required|string|max:255',
//                'subtitle' => 'nullable|string|max:255',
//                'description' => 'nullable|string',
//                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
//                'button_text' => 'nullable|string|max:100',
//                'button_link' => 'nullable|url|max:255',
//                'secondary_button_text' => 'nullable|string|max:100',
//                'secondary_button_link' => 'nullable|url|max:255',
//                'sort_order' => 'nullable|integer|min:0',
//                'is_active' => 'boolean'
//            ]);
//
//            $data = $request->only([
//                'title', 'subtitle', 'description', 'button_text', 'button_link',
//                'secondary_button_text', 'secondary_button_link', 'sort_order'
//            ]);
//
//            $data['is_active'] = $request->boolean('is_active', true);
//
//            // Handle image upload
//            if ($request->hasFile('image')) {
//                // Delete old image
//                if ($banner->image) {
//                    Storage::disk('public')->delete($banner->image);
//                }
//
//                $image = $request->file('image');
//                $filename = time() . '_' . Str::slug($request->title) . '.' . $image->getClientOriginalExtension();
//                $path = $image->storeAs('banners', $filename, 'public');
//                $data['image'] = $path;
//            }
//
//            $banner->update($data);
//
//            return redirect()->route('admin.banners.index')
//                ->with('success', 'Banner updated successfully.');
//        }
//
//        public function destroy(Banner $banner)
//        {
//            // Delete image file
//            if ($banner->image) {
//                Storage::disk('public')->delete($banner->image);
//            }
//
//            $banner->delete();
//
//            return redirect()->route('admin.banners.index')
//                ->with('success', 'Banner deleted successfully.');
//        }
//
//        public function toggleStatus(Banner $banner)
//        {
//            $banner->update(['is_active' => !$banner->is_active]);
//
//            $status = $banner->is_active ? 'activated' : 'deactivated';
//            return back()->with('success', "Banner {$status} successfully.");
//        }
//    }


namespace App\Http\Controllers\Admin;

use App\Helpers\ImageStorageHelper;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BannerController extends AdminController
{
    protected $model = Banner::class;

    public function index()
    {
        $banners = Banner::ordered()->get();
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|url|max:255',
            'secondary_button_text' => 'nullable|string|max:100',
            'secondary_button_link' => 'nullable|url|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean'
        ]);

        $data = $request->only([
            'title', 'subtitle', 'description', 'button_text', 'button_link',
            'secondary_button_text', 'secondary_button_link', 'sort_order'
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        // Handle image upload using our new storage helper
        if ($request->hasFile('image')) {
            $data['image'] = ImageStorageHelper::store($request->file('image'), 'banners');
        }

        Banner::create($data);

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner created successfully.');
    }

    public function show(Banner $banner)
    {
        return view('admin.banners.show', compact('banner'));
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|url|max:255',
            'secondary_button_text' => 'nullable|string|max:100',
            'secondary_button_link' => 'nullable|url|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean'
        ]);

        $data = $request->only([
            'title', 'subtitle', 'description', 'button_text', 'button_link',
            'secondary_button_text', 'secondary_button_link', 'sort_order'
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        // Handle image upload using our new storage helper
        if ($request->hasFile('image')) {
            // Delete old image
            if ($banner->image) {
                ImageStorageHelper::delete($banner->image);
            }

            $data['image'] = ImageStorageHelper::store($request->file('image'), 'banners');
        }

        $banner->update($data);

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner updated successfully.');
    }

    public function destroy(Banner $banner)
    {
        // Delete image file using our new helper
        if ($banner->image) {
            ImageStorageHelper::delete($banner->image);
        }

        $banner->delete();

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner deleted successfully.');
    }

    public function toggleStatus(Banner $banner)
    {
        $banner->update(['is_active' => !$banner->is_active]);

        $status = $banner->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Banner {$status} successfully.");
    }
}
