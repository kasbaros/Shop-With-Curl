<?php

namespace App\Livewire\Products;

use App\Models\Product;
use App\Models\ProductReview;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class ProductReviews extends Component
{
    use WithPagination;

    public Product $product;
    public $rating = 5;
    public $title = '';
    public $review = '';
    public $showReviewForm = false;

    protected $rules = [
        'rating' => 'required|integer|min:1|max:5',
        'title' => 'required|string|max:255',
        'review' => 'required|string|min:10|max:1000',
    ];

    public function mount(Product $product)
    {
        $this->product = $product;
    }

    public function toggleReviewForm()
    {
        if (!Auth::check()) {
            $this->dispatch('show-notification',
                type: 'error',
                message: 'Please login to write a review'
            );
            return;
        }

        $this->showReviewForm = !$this->showReviewForm;
    }

    public function submitReview()
    {
        if (!Auth::check()) {
            $this->dispatch('show-notification',
                type: 'error',
                message: 'Please login to write a review'
            );
            return;
        }

        $this->validate();

        // Check if user already reviewed this product
        $existingReview = ProductReview::where('product_id', $this->product->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingReview) {
            $this->addError('review', 'You have already reviewed this product.');
            return;
        }

        // Check if user has purchased this product
        $hasPurchased = Auth::user()->orders()
            ->whereHas('items', function ($query) {
                $query->where('product_id', $this->product->id);
            })
            ->where('status', 'delivered')
            ->exists();

        ProductReview::create([
            'product_id' => $this->product->id,
            'user_id' => Auth::id(),
            'rating' => $this->rating,
            'title' => $this->title,
            'review' => $this->review,
            'is_verified_purchase' => $hasPurchased,
        ]);

        $this->dispatch('show-notification',
            type: 'success',
            message: 'Review submitted successfully! It will be visible after approval.'
        );

        $this->reset(['rating', 'title', 'review', 'showReviewForm']);
        $this->resetPage();
    }

    public function getReviewsProperty()
    {
        return $this->product->reviews()
            ->approved()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function getRatingsSummaryProperty()
    {
        $reviews = $this->product->reviews()->approved();
        $totalReviews = $reviews->count();

        if ($totalReviews === 0) {
            return null;
        }

        $ratingCounts = [];
        for ($i = 1; $i <= 5; $i++) {
            $count = $reviews->where('rating', $i)->count();
            $ratingCounts[$i] = [
                'count' => $count,
                'percentage' => round(($count / $totalReviews) * 100, 1),
            ];
        }

        return [
            'average' => round($reviews->avg('rating'), 1),
            'total' => $totalReviews,
            'ratings' => $ratingCounts,
        ];
    }

    public function render()
    {
        return view('livewire.products.product-reviews');
    }
}
