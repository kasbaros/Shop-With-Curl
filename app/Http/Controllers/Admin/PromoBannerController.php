<?php

    namespace App\Http\Controllers\Admin;

    use App\Helpers\ImageStorageHelper;
    use App\Models\PromoBanner;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Storage;

    class PromoBannerController extends AdminController
    {
        protected $model = PromoBanner::class;

        public function index()
        {
            $promoBanners = PromoBanner::orderBy('priority')
                ->orderBy('created_at', 'desc')
                ->get();

            return view('admin.promo-banners.index', compact('promoBanners'));
        }

        public function create()
        {
            return view('admin.promo-banners.create');
        }

//        public function store(Request $request)
//        {
//            $request->validate([
//                'heading' => 'required|string|max:255',
//                'subtitle' => 'nullable|string|max:255',
//                'features' => 'nullable|string',
//                'cta_text' => 'nullable|string|max:100',
//                'cta_link' => 'nullable|url|max:255',
//                'price_badge' => 'nullable|string|max:100',
//                'image_desktop' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
//                'image_mobile' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
//                'active' => 'boolean',
//                'priority' => 'nullable|integer|min:0',
//                'starts_at' => 'nullable|date',
//                'ends_at' => 'nullable|date|after:starts_at',
//            ]);
//
//            $data = $request->only([
//                'heading', 'subtitle', 'cta_text', 'cta_link', 'price_badge', 'priority'
//            ]);
//
//            // Handle features
//            if ($request->features) {
//                $features = array_map('trim', explode(',', $request->features));
//                $data['features'] = array_filter($features);
//            }
//
//            $data['active'] = $request->boolean('active', true);
//            $data['starts_at'] = $request->starts_at ? now()->parse($request->starts_at) : null;
//            $data['ends_at'] = $request->ends_at ? now()->parse($request->ends_at) : null;
//
//            // Handle image uploads
//            if ($request->hasFile('image_desktop')) {
//                $desktopImage = $request->file('image_desktop');
//                $desktopFilename = time() . '_desktop_promo.' . $desktopImage->getClientOriginalExtension();
//                $desktopPath = $desktopImage->storeAs('promo-banners', $desktopFilename, 'public');
//                $data['image_desktop'] = '/storage/' . $desktopPath;
//            }
//
//            if ($request->hasFile('image_mobile')) {
//                $mobileImage = $request->file('image_mobile');
//                $mobileFilename = time() . '_mobile_promo.' . $mobileImage->getClientOriginalExtension();
//                $mobilePath = $mobileImage->storeAs('promo-banners', $mobileFilename, 'public');
//                $data['image_mobile'] = '/storage/' . $mobilePath;
//            }
//
//            PromoBanner::create($data);
//
//            return redirect()->route('admin.promo-banners.index')
//                ->with('success', 'Promo banner created successfully.');
//        }

        public function store(Request $request)
        {
            $data = $request->validate([
                'heading' => 'required|string|max:255',
                'subtitle' => 'nullable|string|max:255',
                'features' => 'nullable|array',
                'features.*' => 'string|max:255',
                'cta_text' => 'nullable|string|max:100',
                'cta_link' => 'nullable|url|max:255',
                'price_badge' => 'nullable|string|max:50',
                'image_desktop' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:6144',
                'image_mobile' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:6144',
                'active' => 'boolean',
                'priority' => 'nullable|integer|min:0',
                'starts_at' => 'nullable|date',
                'ends_at' => 'nullable|date|after_or_equal:starts_at',
            ]);

            if ($request->hasFile('image_desktop')) {
                $data['image_desktop'] = ImageStorageHelper::store($request->file('image_desktop'), 'promobanners');
            }
            if ($request->hasFile('image_mobile')) {
                $data['image_mobile'] = ImageStorageHelper::store($request->file('image_mobile'), 'promobanners');
            }

            $data['active'] = $request->boolean('active', true);

            $promo = PromoBanner::create($data);

            return redirect()->route('admin.promo-banners.show', $promo)
                ->with('success', 'Promo banner created successfully.');
        }

        public function update(Request $request, PromoBanner $promoBanner)
        {
            $data = $request->validate([
                'heading' => 'required|string|max:255',
                'subtitle' => 'nullable|string|max:255',
                'features' => 'nullable|array',
                'features.*' => 'string|max:255',
                'cta_text' => 'nullable|string|max:100',
                'cta_link' => 'nullable|url|max:255',
                'price_badge' => 'nullable|string|max:50',
                'image_desktop' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:6144',
                'image_mobile' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:6144',
                'active' => 'boolean',
                'priority' => 'nullable|integer|min:0',
                'starts_at' => 'nullable|date',
                'ends_at' => 'nullable|date|after_or_equal:starts_at',
            ]);

            if ($request->hasFile('image_desktop')) {
                if (!empty($promoBanner->image_desktop)) {
                    ImageStorageHelper::delete($promoBanner->image_desktop);
                }
                $data['image_desktop'] = ImageStorageHelper::store($request->file('image_desktop'), 'promobanners');
            }

            if ($request->hasFile('image_mobile')) {
                if (!empty($promoBanner->image_mobile)) {
                    ImageStorageHelper::delete($promoBanner->image_mobile);
                }
                $data['image_mobile'] = ImageStorageHelper::store($request->file('image_mobile'), 'promobanners');
            }

            $data['active'] = $request->boolean('active', $promoBanner->active);

            $promoBanner->update($data);

            return redirect()->route('admin.promo-banners.show', $promoBanner)
                ->with('success', 'Promo banner updated successfully.');
        }

        public function destroy(PromoBanner $promoBanner)
        {
            if (!empty($promoBanner->image_desktop)) {
                ImageStorageHelper::delete($promoBanner->image_desktop);
            }
            if (!empty($promoBanner->image_mobile)) {
                ImageStorageHelper::delete($promoBanner->image_mobile);
            }

            $promoBanner->delete();

            return redirect()->route('admin.promo-banners.index')
                ->with('success', 'Promo banner deleted successfully.');
        }


        public function show(PromoBanner $promoBanner)
        {
            return view('admin.promo-banners.show', compact('promoBanner'));
        }

        public function edit(PromoBanner $promoBanner)
        {
            return view('admin.promo-banners.edit', compact('promoBanner'));
        }

//        public function update(Request $request, PromoBanner $promoBanner)
//        {
//            $request->validate([
//                'heading' => 'required|string|max:255',
//                'subtitle' => 'nullable|string|max:255',
//                'features' => 'nullable|string',
//                'cta_text' => 'nullable|string|max:100',
//                'cta_link' => 'nullable|url|max:255',
//                'price_badge' => 'nullable|string|max:100',
//                'image_desktop' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
//                'image_mobile' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
//                'active' => 'boolean',
//                'priority' => 'nullable|integer|min:0',
//                'starts_at' => 'nullable|date',
//                'ends_at' => 'nullable|date|after:starts_at',
//            ]);
//
//            $data = $request->only([
//                'heading', 'subtitle', 'cta_text', 'cta_link', 'price_badge', 'priority'
//            ]);
//
//            // Handle features
//            if ($request->features) {
//                $features = array_map('trim', explode(',', $request->features));
//                $data['features'] = array_filter($features);
//            }
//
//            $data['active'] = $request->boolean('active');
//            $data['starts_at'] = $request->starts_at ? now()->parse($request->starts_at) : null;
//            $data['ends_at'] = $request->ends_at ? now()->parse($request->ends_at) : null;
//
//            // Handle image updates
//            if ($request->hasFile('image_desktop')) {
//                // Delete old image
//                if ($promoBanner->image_desktop) {
//                    Storage::disk('public')->delete(str_replace('/storage/', '', $promoBanner->image_desktop));
//                }
//
//                $desktopImage = $request->file('image_desktop');
//                $desktopFilename = time() . '_desktop_promo.' . $desktopImage->getClientOriginalExtension();
//                $desktopPath = $desktopImage->storeAs('promo-banners', $desktopFilename, 'public');
//                $data['image_desktop'] = '/storage/' . $desktopPath;
//            }
//
//            if ($request->hasFile('image_mobile')) {
//                // Delete old image
//                if ($promoBanner->image_mobile) {
//                    Storage::disk('public')->delete(str_replace('/storage/', '', $promoBanner->image_mobile));
//                }
//
//                $mobileImage = $request->file('image_mobile');
//                $mobileFilename = time() . '_mobile_promo.' . $mobileImage->getClientOriginalExtension();
//                $mobilePath = $mobileImage->storeAs('promo-banners', $mobileFilename, 'public');
//                $data['image_mobile'] = '/storage/' . $mobilePath;
//            }
//
//            $promoBanner->update($data);
//
//            return redirect()->route('admin.promo-banners.index')
//                ->with('success', 'Promo banner updated successfully.');
//        }

//        public function destroy(PromoBanner $promoBanner)
//        {
//            // Delete images
//            if ($promoBanner->image_desktop) {
//                Storage::disk('public')->delete(str_replace('/storage/', '', $promoBanner->image_desktop));
//            }
//            if ($promoBanner->image_mobile) {
//                Storage::disk('public')->delete(str_replace('/storage/', '', $promoBanner->image_mobile));
//            }
//
//            $promoBanner->delete();
//
//            return redirect()->route('admin.promo-banners.index')
//                ->with('success', 'Promo banner deleted successfully.');
//        }

        public function toggleStatus(PromoBanner $promoBanner)
        {
            $promoBanner->update(['active' => !$promoBanner->active]);

            $status = $promoBanner->active ? 'activated' : 'deactivated';
            return back()->with('success', "Promo banner {$status} successfully.");
        }
    }
