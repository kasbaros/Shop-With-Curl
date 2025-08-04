<div class="space-y-8">
    <!-- Reviews Header -->
    <div class="flex items-center justify-between">
        <h3 class="text-xl font-semibold text-gray-900">Customer Reviews</h3>
        @auth
            <button
                wire:click="toggleReviewForm"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors"
            >
                Write a Review
            </button>
        @endauth
    </div>

    <!-- Rating Summary -->
    @if($this->ratingsSummary)
        <div class="bg-gray-50 rounded-lg p-6">
            <div class="flex items-center space-x-6">
                <!-- Average Rating -->
                <div class="text-center">
                    <div class="text-4xl font-bold text-gray-900">
                        {{ $this->ratingsSummary['average'] }}
                    </div>
                    <div class="flex items-center justify-center mt-1">
                        @for($i = 1; $i <= 5; $i++)
                            <x-heroicon-s-star class="w-5 h-5 {{ $i <= $this->ratingsSummary['average'] ? 'text-yellow-400' : 'text-gray-300' }}" />
                        @endfor
                    </div>
                    <div class="text-sm text-gray-600 mt-1">
                        {{ $this->ratingsSummary['total'] }} reviews
                    </div>
                </div>

                <!-- Rating Breakdown -->
                <div class="flex-1 space-y-2">
                    @for($i = 5; $i >= 1; $i--)
                        <div class="flex items-center space-x-2">
                            <span class="text-sm font-medium text-gray-700 w-3">{{ $i }}</span>
                            <x-heroicon-s-star class="w-4 h-4 text-yellow-400" />
                            <div class="flex-1 bg-gray-200 rounded-full h-2">
                                <div
                                    class="bg-yellow-400 h-2 rounded-full"
                                    style="width: {{ $this->ratingsSummary['ratings'][$i]['percentage'] }}%"
                                ></div>
                            </div>
                            <span class="text-sm text-gray-600 w-8">
                                {{ $this->ratingsSummary['ratings'][$i]['count'] }}
                            </span>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    @endif

    <!-- Review Form -->
    @if($showReviewForm)
        <div class="bg-white border rounded-lg p-6">
            <h4 class="text-lg font-medium text-gray-900 mb-4">Write a Review</h4>

            <form wire:submit="submitReview" class="space-y-4">
                <!-- Rating -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                    <div class="flex items-center space-x-1">
                        @for($i = 1; $i <= 5; $i++)
                            <button
                                type="button"
                                wire:click="$set('rating', {{ $i }})"
                                class="text-2xl {{ $i <= $rating ? 'text-yellow-400' : 'text-gray-300' }} hover:text-yellow-400 transition-colors"
                            >
                                <x-heroicon-s-star class="w-6 h-6" />
                            </button>
                        @endfor
                    </div>
                    @error('rating') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Title -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Review Title</label>
                    <input
                        type="text"
                        wire:model="title"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Give your review a title"
                    >
                    @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Review Text -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Review</label>
                    <textarea
                        wire:model="review"
                        rows="4"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Tell others about your experience with this product"
                    ></textarea>
                    @error('review') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Submit Buttons -->
                <div class="flex space-x-3">
                    <button
                        type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors"
                    >
                        Submit Review
                    </button>
                    <button
                        type="button"
                        wire:click="toggleReviewForm"
                        class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors"
                    >
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    @endif

    <!-- Reviews List -->
    @if($this->reviews->isNotEmpty())
        <div class="space-y-6">
            @foreach($this->reviews as $review)
                <div class="bg-white border rounded-lg p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <!-- Review Header -->
                            <div class="flex items-center space-x-4 mb-2">
                                <div class="flex items-center">
                                    @for($i = 1; $i <= 5; $i++)
                                        <x-heroicon-s-star class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" />
                                    @endfor
                                </div>
                                <h4 class="font-medium text-gray-900">{{ $review->title }}</h4>
                                @if($review->is_verified_purchase)
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">
                                        Verified Purchase
                                    </span>
                                @endif
                            </div>

                            <!-- Review Content -->
                            <p class="text-gray-700 mb-3">{{ $review->review }}</p>

                            <!-- Review Meta -->
                            <div class="flex items-center space-x-4 text-sm text-gray-500">
                                <span>{{ $review->user->name }}</span>
                                <span>{{ $review->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $this->reviews->links() }}
        </div>
    @else
        <div class="text-center py-8">
            <p class="text-gray-500">No reviews yet. Be the first to review this product!</p>
        </div>
    @endif
</div>
