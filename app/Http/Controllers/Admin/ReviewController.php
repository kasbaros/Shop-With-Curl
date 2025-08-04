<?php

    namespace App\Http\Controllers\Admin;

    use App\Models\Review;
    use App\Models\Product;
    use App\Models\User;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;

    class ReviewController extends AdminController
    {
        public function index(Request $request)
        {
            $query = Review::with(['product', 'user']);

            // Search
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('comment', 'like', "%{$search}%")
                        ->orWhereHas('product', function($pq) use ($search) {
                            $pq->where('name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('user', function($uq) use ($search) {
                            $uq->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            }

            // Filter by status
            if ($request->filled('status')) {
                if ($request->status === 'approved') {
                    $query->where('is_approved', true);
                } elseif ($request->status === 'pending') {
                    $query->where('is_approved', false);
                }
            }

            // Filter by rating
            if ($request->filled('rating')) {
                $query->where('rating', $request->rating);
            }

            // Filter by product
            if ($request->filled('product_id')) {
                $query->where('product_id', $request->product_id);
            }

            // Sort
            $sortBy = $request->get('sort', 'created_at');
            $sortOrder = $request->get('order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            $reviews = $query->paginate(15);

            $stats = [
                'total' => Review::count(),
                'approved' => Review::where('is_approved', true)->count(),
                'pending' => Review::where('is_approved', false)->count(),
                'average_rating' => Review::where('is_approved', true)->avg('rating') ?: 0,
            ];

            $products = Product::where('status', 'published')->get(['id', 'name']);

            return view('admin.reviews.index', array_merge(
                compact('reviews', 'stats', 'products'),
                $this->getAdminViewData()
            ));
        }

        public function show(Review $review)
        {
            $review->load(['product', 'user', 'replies']);

            return view('admin.reviews.show', array_merge(
                compact('review'),
                $this->getAdminViewData()
            ));
        }

        public function approve(Review $review)
        {
            $review->update(['is_approved' => true]);

            // Update product average rating
            $this->updateProductRating($review->product_id);

            return back()->with('success', 'Review approved successfully!');
        }

        public function reject(Review $review)
        {
            $review->update(['is_approved' => false]);

            // Update product average rating
            $this->updateProductRating($review->product_id);

            return back()->with('success', 'Review rejected successfully!');
        }

        public function destroy(Review $review)
        {
            $productId = $review->product_id;
            $review->delete();

            // Update product average rating
            $this->updateProductRating($productId);

            return back()->with('success', 'Review deleted successfully!');
        }

        public function bulkAction(Request $request)
        {
            $request->validate([
                'action' => ['required', 'in:approve,reject,delete'],
                'review_ids' => ['required', 'array'],
                'review_ids.*' => ['exists:reviews,id'],
            ]);

            $reviews = Review::whereIn('id', $request->review_ids);
            $productIds = $reviews->pluck('product_id')->unique();

            switch ($request->action) {
                case 'approve':
                    $reviews->update(['is_approved' => true]);
                    $message = 'Reviews approved successfully!';
                    break;
                case 'reject':
                    $reviews->update(['is_approved' => false]);
                    $message = 'Reviews rejected successfully!';
                    break;
                case 'delete':
                    $reviews->delete();
                    $message = 'Reviews deleted successfully!';
                    break;
            }

            // Update product ratings for affected products
            foreach ($productIds as $productId) {
                $this->updateProductRating($productId);
            }

            return back()->with('success', $message);
        }

        public function reply(Request $request, Review $review)
        {
            $request->validate([
                'reply' => ['required', 'string', 'max:1000'],
            ]);

            $review->replies()->create([
                'content' => $request->reply,
                'user_id' => auth()->id(),
                'is_admin_reply' => true,
            ]);

            return back()->with('success', 'Reply added successfully!');
        }

        public function analytics()
        {
            $stats = [
                'total_reviews' => Review::count(),
                'approved_reviews' => Review::where('is_approved', true)->count(),
                'pending_reviews' => Review::where('is_approved', false)->count(),
                'average_rating' => Review::where('is_approved', true)->avg('rating') ?: 0,
            ];

            // Reviews by rating
            $ratingDistribution = Review::where('is_approved', true)
                ->select('rating', DB::raw('count(*) as count'))
                ->groupBy('rating')
                ->orderBy('rating', 'desc')
                ->get()
                ->keyBy('rating');

            // Reviews over time (last 30 days)
            $reviewsOverTime = Review::where('created_at', '>=', now()->subDays(30))
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            // Top reviewed products
            $topReviewedProducts = Product::withCount(['reviews' => function($query) {
                $query->where('is_approved', true);
            }])
                ->having('reviews_count', '>', 0)
                ->orderBy('reviews_count', 'desc')
                ->take(10)
                ->get();

            // Recent reviews
            $recentReviews = Review::with(['product', 'user'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            return view('admin.reviews.analytics', array_merge(
                compact('stats', 'ratingDistribution', 'reviewsOverTime', 'topReviewedProducts', 'recentReviews'),
                $this->getAdminViewData()
            ));
        }

        protected function updateProductRating($productId)
        {
            $product = Product::find($productId);
            if (!$product) return;

            $avgRating = Review::where('product_id', $productId)
                ->where('is_approved', true)
                ->avg('rating');

            $reviewCount = Review::where('product_id', $productId)
                ->where('is_approved', true)
                ->count();

            $product->update([
                'average_rating' => $avgRating ?: 0,
                'review_count' => $reviewCount,
            ]);
        }
    }
