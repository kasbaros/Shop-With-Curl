<?php

    namespace App\Http\Controllers\Admin;

    use App\Models\Coupon;
    use App\Models\Product;
    use App\Models\Category;
    use Illuminate\Http\Request;
    use Illuminate\Support\Str;
    use Illuminate\Validation\Rule;

    class CouponController extends AdminController
    {
        public function index(Request $request)
        {
            $query = Coupon::query();

            // Search
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('code', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            }

            // Filter by type
            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }

            // Filter by status
            if ($request->filled('status')) {
                if ($request->status === 'active') {
                    $query->where('is_active', true);
                } elseif ($request->status === 'inactive') {
                    $query->where('is_active', false);
                } elseif ($request->status === 'expired') {
                    $query->where('expires_at', '<', now());
                } elseif ($request->status === 'upcoming') {
                    $query->where('starts_at', '>', now());
                }
            }

            $coupons = $query->orderBy('created_at', 'desc')->paginate(15);

            $stats = [
                'total' => Coupon::count(),
                'active' => Coupon::where('is_active', true)->count(),
                'expired' => Coupon::where('expires_at', '<', now())->count(),
                'total_usage' => Coupon::sum('used_count'),
            ];

            return view('admin.coupons.index', array_merge(
                compact('coupons', 'stats'),
                $this->getAdminViewData()
            ));
        }

        public function create()
        {
            $products = Product::where('status', 'published')->get(['id', 'name']);
            $categories = Category::where('is_active', true)->get(['id', 'name']);

            return view('admin.coupons.create', array_merge(
                compact('products', 'categories'),
                $this->getAdminViewData()
            ));
        }

        public function store(Request $request)
        {
            $request->validate([
                'code' => ['required', 'string', 'max:50', 'unique:coupons,code'],
                'name' => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'type' => ['required', 'in:percentage,fixed_amount,free_shipping'],
                'value' => ['required', 'numeric', 'min:0'],
                'minimum_amount' => ['nullable', 'numeric', 'min:0'],
                'maximum_discount' => ['nullable', 'numeric', 'min:0'],
                'usage_limit' => ['nullable', 'integer', 'min:1'],
                'usage_limit_per_user' => ['nullable', 'integer', 'min:1'],
                'starts_at' => ['nullable', 'date', 'after_or_equal:today'],
                'expires_at' => ['nullable', 'date', 'after:starts_at'],
                'applicable_products' => ['nullable', 'array'],
                'applicable_categories' => ['nullable', 'array'],
            ]);

            $data = $request->all();
            $data['code'] = strtoupper($data['code']);

            // Convert percentage to decimal for storage if needed
            if ($data['type'] === 'percentage' && $data['value'] > 1) {
                $data['value'] = $data['value'] / 100;
            }

            Coupon::create($data);

            return redirect()->route('admin.coupons.index')
                ->with('success', 'Coupon created successfully!');
        }

        public function show(Coupon $coupon)
        {
            $coupon->load(['orders' => function($query) {
                $query->latest()->take(10);
            }]);

            $usage_stats = [
                'total_orders' => $coupon->orders()->count(),
                'total_discount_given' => $coupon->orders()->sum('discount_amount'),
                'average_order_value' => $coupon->orders()->avg('total_amount'),
            ];

            return view('admin.coupons.show', array_merge(
                compact('coupon', 'usage_stats'),
                $this->getAdminViewData()
            ));
        }

        public function edit(Coupon $coupon)
        {
            $products = Product::where('status', 'published')->get(['id', 'name']);
            $categories = Category::where('is_active', true)->get(['id', 'name']);

            return view('admin.coupons.edit', array_merge(
                compact('coupon', 'products', 'categories'),
                $this->getAdminViewData()
            ));
        }

        public function update(Request $request, Coupon $coupon)
        {
            $request->validate([
                'code' => ['required', 'string', 'max:50', Rule::unique('coupons')->ignore($coupon)],
                'name' => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'type' => ['required', 'in:percentage,fixed_amount,free_shipping'],
                'value' => ['required', 'numeric', 'min:0'],
                'minimum_amount' => ['nullable', 'numeric', 'min:0'],
                'maximum_discount' => ['nullable', 'numeric', 'min:0'],
                'usage_limit' => ['nullable', 'integer', 'min:1'],
                'usage_limit_per_user' => ['nullable', 'integer', 'min:1'],
                'starts_at' => ['nullable', 'date'],
                'expires_at' => ['nullable', 'date', 'after:starts_at'],
                'applicable_products' => ['nullable', 'array'],
                'applicable_categories' => ['nullable', 'array'],
            ]);

            $data = $request->all();
            $data['code'] = strtoupper($data['code']);

            $coupon->update($data);

            return redirect()->route('admin.coupons.index')
                ->with('success', 'Coupon updated successfully!');
        }

        public function destroy(Coupon $coupon)
        {
            if ($coupon->used_count > 0) {
                return back()->with('error', 'Cannot delete coupon that has been used.');
            }

            $coupon->delete();

            return redirect()->route('admin.coupons.index')
                ->with('success', 'Coupon deleted successfully!');
        }

        public function toggle(Coupon $coupon)
        {
            $coupon->update(['is_active' => !$coupon->is_active]);

            $status = $coupon->is_active ? 'activated' : 'deactivated';
            return back()->with('success', "Coupon {$status} successfully!");
        }

        public function generateCode()
        {
            do {
                $code = strtoupper(Str::random(8));
            } while (Coupon::where('code', $code)->exists());

            return response()->json(['code' => $code]);
        }

        public function bulkAction(Request $request)
        {
            $request->validate([
                'action' => ['required', 'in:activate,deactivate,delete'],
                'coupon_ids' => ['required', 'array'],
                'coupon_ids.*' => ['exists:coupons,id'],
            ]);

            $coupons = Coupon::whereIn('id', $request->coupon_ids);

            switch ($request->action) {
                case 'activate':
                    $coupons->update(['is_active' => true]);
                    $message = 'Coupons activated successfully!';
                    break;
                case 'deactivate':
                    $coupons->update(['is_active' => false]);
                    $message = 'Coupons deactivated successfully!';
                    break;
                case 'delete':
                    $coupons->where('used_count', 0)->delete();
                    $message = 'Unused coupons deleted successfully!';
                    break;
            }

            return back()->with('success', $message);
        }
    }
