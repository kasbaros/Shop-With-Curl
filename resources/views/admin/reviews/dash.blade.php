@extends('admin.layouts.app')

@section('title', 'Review Analytics')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Review Analytics</h1>
            <p class="text-muted">Insights and trends from customer reviews</p>
        </div>
        <div>
            <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline-secondary btn-admin">
                <i class="bi bi-arrow-left"></i> Back to Reviews
            </a>
            <button class="btn btn-outline-primary btn-admin" onclick="exportAnalytics()">
                <i class="bi bi-download"></i> Export Report
            </button>
        </div>
    </div>

    <!-- Overview Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="table-admin">
                <div class="p-4 text-center">
                    <div class="h2 mb-1 text-primary">{{ number_format($stats['total_reviews']) }}</div>
                    <div class="text-muted small">Total Reviews</div>
                    <div class="progress mt-2" style="height: 4px;">
                        <div class="progress-bar bg-primary" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="table-admin">
                <div class="p-4 text-center">
                    <div class="h2 mb-1 text-success">{{ number_format($stats['approved_reviews']) }}</div>
                    <div class="text-muted small">Approved Reviews</div>
                    <div class="progress mt-2" style="height: 4px;">
                        <div class="progress-bar bg-success"
                             style="width: {{ $stats['total_reviews'] > 0 ? ($stats['approved_reviews'] / $stats['total_reviews']) * 100 : 0 }}%"></div>
                    </div>
                    <small class="text-muted">
                        {{ $stats['total_reviews'] > 0 ? number_format(($stats['approved_reviews'] / $stats['total_reviews']) * 100, 1) : 0 }}% approved
                    </small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="table-admin">
                <div class="p-4 text-center">
                    <div class="h2 mb-1 text-warning">{{ number_format($stats['pending_reviews']) }}</div>
                    <div class="text-muted small">Pending Reviews</div>
                    <div class="progress mt-2" style="height: 4px;">
                        <div class="progress-bar bg-warning"
                             style="width: {{ $stats['total_reviews'] > 0 ? ($stats['pending_reviews'] / $stats['total_reviews']) * 100 : 0 }}%"></div>
                    </div>
                    <small class="text-muted">
                        {{ $stats['total_reviews'] > 0 ? number_format(($stats['pending_reviews'] / $stats['total_reviews']) * 100, 1) : 0 }}% pending
                    </small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="table-admin">
                <div class="p-4 text-center">
                    <div class="h2 mb-1 text-info">{{ number_format($stats['average_rating'], 1) }}</div>
                    <div class="text-muted small">Average Rating</div>
                    <div class="mt-2">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star{{ $i <= $stats['average_rating'] ? '-fill' : '' }} text-warning"></i>
                        @endfor
                    </div>
                    <small class="text-muted">Overall customer satisfaction</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-8">
            <!-- Rating Distribution -->
            <div class="table-admin mb-4">
                <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Rating Distribution</h5>
                    <small class="text-muted">How customers rate your products</small>
                </div>
                <div class="p-4">
                    @php $maxCount = $ratingDistribution->max('count') ?: 1; @endphp
                    @for($rating = 5; $rating >= 1; $rating--)
                        @php $count = $ratingDistribution->get($rating)->count ?? 0; @endphp
                        <div class="d-flex align-items-center mb-3">
                            <div class="me-3" style="width: 60px;">
                                <span class="fw-medium">{{ $rating }} Star</span>
                            </div>
                            <div class="flex-grow-1 me-3">
                                <div class="progress" style="height: 24px;">
                                    <div class="progress-bar bg-warning"
                                         style="width: {{ ($count / $maxCount) * 100 }}%">
                                        <span class="small text-dark">{{ $count }}</span>
                                    </div>
                                </div>
                            </div>
                            <div style="width: 60px;">
                            <span class="text-muted">
                                {{ $stats['approved_reviews'] > 0 ? number_format(($count / $stats['approved_reviews']) * 100, 1) : 0 }}%
                            </span>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>

            <!-- Reviews Over Time -->
            <div class="table-admin mb-4">
                <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Reviews Over Time</h5>
                    <small class="text-muted">Last 30 days</small>
                </div>
                <div class="p-4">
                    <canvas id="reviewsChart" height="80"></canvas>
                </div>
            </div>

            <!-- Top Reviewed Products -->
            <div class="table-admin mb-4">
                <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Top Reviewed Products</h5>
                    <small class="text-muted">Products with most reviews</small>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th>Reviews</th>
                            <th>Average Rating</th>
                            <th>Latest Review</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($topReviewedProducts as $product)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($product->images->first())
                                            <img src="{{ Storage::url($product->images->first()->image_path) }}"
                                                 class="rounded me-2" width="40" height="40" style="object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center"
                                                 style="width: 40px; height: 40px;">
                                                <i class="bi bi-image text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <a href="{{ route('admin.products.show', $product) }}"
                                               class="text-decoration-none fw-medium">
                                                {{ Str::limit($product->name, 40) }}
                                            </a>
                                            <div class="small text-muted">{{ $product->sku }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $product->reviews_count }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="me-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="bi bi-star{{ $i <= $product->average_rating ? '-fill' : '' }} text-warning small"></i>
                                            @endfor
                                        </div>
                                        <span class="text-muted small">{{ number_format($product->average_rating ?: 0, 1) }}</span>
                                    </div>
                                </td>
                                <td>
                                    @if($product->reviews->first())
                                        <div class="text-muted small">
                                            {{ $product->reviews->first()->created_at->diffForHumans() }}
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    No reviewed products found
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            <!-- Review Quality Metrics -->
            <div class="table-admin mb-4">
                <div class="p-3 border-bottom">
                    <h5 class="mb-0">Review Quality</h5>
                </div>
                <div class="p-4">
                    @php
                        $verifiedCount = Review::where('is_verified_purchase', true)->where('is_approved', true)->count();
                        $withTitleCount = Review::whereNotNull('title')->where('title', '!=', '')->where('is_approved', true)->count();
                        $avgLength = Review::where('is_approved', true)->avg(DB::raw('LENGTH(comment)')) ?: 0;
                    @endphp

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-medium">Verified Purchases</span>
                            <span class="text-muted">{{ $verifiedCount }}</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-success"
                                 style="width: {{ $stats['approved_reviews'] > 0 ? ($verifiedCount / $stats['approved_reviews']) * 100 : 0 }}%"></div>
                        </div>
                        <small class="text-muted">
                            {{ $stats['approved_reviews'] > 0 ? number_format(($verifiedCount / $stats['approved_reviews']) * 100, 1) : 0 }}% of reviews
                        </small>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-medium">Reviews with Titles</span>
                            <span class="text-muted">{{ $withTitleCount }}</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-info"
                                 style="width: {{ $stats['approved_reviews'] > 0 ? ($withTitleCount / $stats['approved_reviews']) * 100 : 0 }}%"></div>
                        </div>
                        <small class="text-muted">
                            {{ $stats['approved_reviews'] > 0 ? number_format(($withTitleCount / $stats['approved_reviews']) * 100, 1) : 0 }}% have titles
                        </small>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-medium">Average Review Length</span>
                            <span class="text-muted">{{ number_format($avgLength) }} chars</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-warning" style="width: {{ min(($avgLength / 500) * 100, 100) }}%"></div>
                        </div>
                        <small class="text-muted">Detailed reviews help customers</small>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="table-admin mb-4">
                <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Reviews</h5>
                    <a href="{{ route('admin.reviews.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="p-4">
                    @forelse($recentReviews as $review)
                        <div class="d-flex align-items-start mb-3 {{ !$loop->last ? 'pb-3 border-bottom' : '' }}">
                            <div class="me-3">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }} text-warning small"></i>
                                @endfor
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-medium small">{{ Str::limit($review->product->name, 30) }}</div>
                                <div class="text-muted small">
                                    {{ Str::limit($review->comment, 60) }}
                                </div>
                                <div class="d-flex align-items-center mt-1">
                                    <small class="text-muted">{{ $review->user->name ?? 'Anonymous' }}</small>
                                    <small class="text-muted ms-2">{{ $review->created_at->diffForHumans() }}</small>
                                    @if(!$review->is_approved)
                                        <span class="badge bg-warning badge-sm ms-2">Pending</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted">
                            <i class="bi bi-star display-4 d-block mb-2"></i>
                            <div>No recent reviews</div>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Review Response Time -->
            <div class="table-admin mb-4">
                <div class="p-3 border-bottom">
                    <h5 class="mb-0">Response Metrics</h5>
                </div>
                <div class="p-4">
                    @php
                        $reviewsWithReplies = Review::whereHas('replies')->where('is_approved', true)->count();
                        $avgResponseTime = Review::whereHas('replies')
                            ->join('review_replies', 'reviews.id', '=', 'review_replies.review_id')
                            ->where('review_replies.is_admin_reply', true)
                            ->selectRaw('AVG(EXTRACT(EPOCH FROM (review_replies.created_at - reviews.created_at))/86400) as avg_days')
                            ->value('avg_days') ?: 0;
                    @endphp

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-medium">Response Rate</span>
                            <span class="text-primary fw-bold">
                            {{ $stats['approved_reviews'] > 0 ? number_format(($reviewsWithReplies / $stats['approved_reviews']) * 100, 1) : 0 }}%
                        </span>
                        </div>
                        <small class="text-muted">{{ $reviewsWithReplies }} of {{ $stats['approved_reviews'] }} reviews have replies</small>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-medium">Avg Response Time</span>
                            <span class="text-info fw-bold">{{ number_format($avgResponseTime, 1) }} days</span>
                        </div>
                        <small class="text-muted">Time to respond to reviews</small>
                    </div>

                    <div class="alert alert-info small mt-3">
                        <i class="bi bi-lightbulb me-1"></i>
                        <strong>Tip:</strong> Responding to reviews increases customer trust and improves SEO.
                    </div>
                </div>
            </div>

            <!-- Actionable Insights -->
            <div class="table-admin">
                <div class="p-3 border-bottom">
                    <h5 class="mb-0">Action Items</h5>
                </div>
                <div class="p-4">
                    @if($stats['pending_reviews'] > 0)
                        <div class="alert alert-warning small mb-3">
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            <strong>{{ $stats['pending_reviews'] }} reviews</strong> waiting for approval.
                            <a href="{{ route('admin.reviews.index', ['status' => 'pending']) }}" class="alert-link">Review now</a>
                        </div>
                    @endif

                    @php $lowRatedProducts = Product::where('average_rating', '<', 3)->where('review_count', '>', 0)->count(); @endphp
                    @if($lowRatedProducts > 0)
                        <div class="alert alert-danger small mb-3">
                            <i class="bi bi-star me-1"></i>
                            <strong>{{ $lowRatedProducts }} products</strong> have ratings below 3 stars.
                            <a href="{{ route('admin.products.index') }}" class="alert-link">Investigate</a>
                        </div>
                    @endif

                    @if($reviewsWithReplies / max($stats['approved_reviews'], 1) < 0.5)
                        <div class="alert alert-info small mb-3">
                            <i class="bi bi-chat-dots me-1"></i>
                            Consider <strong>responding to more reviews</strong> to improve customer engagement.
                        </div>
                    @endif

                    @if($avgLength < 100)
                        <div class="alert alert-secondary small">
                            <i class="bi bi-chat-text me-1"></i>
                            Average review length is short. Consider <strong>encouraging detailed feedback</strong>.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Reviews Over Time Chart
            const ctx = document.getElementById('reviewsChart').getContext('2d');
            const reviewsData = @json($reviewsOverTime);

            // Prepare data for last 30 days
            const labels = [];
            const data = [];
            const today = new Date();

            for (let i = 29; i >= 0; i--) {
                const date = new Date(today);
                date.setDate(date.getDate() - i);
                const dateStr = date.toISOString().split('T')[0];
                labels.push(date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' }));

                const dayData = reviewsData.find(item => item.date === dateStr);
                data.push(dayData ? dayData.count : 0);
            }

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Reviews',
                        data: data,
                        borderColor: '#0d6efd',
                        backgroundColor: 'rgba(13, 110, 253, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        },
                        x: {
                            ticks: {
                                maxRotation: 45
                            }
                        }
                    }
                }
            });
        });

        function exportAnalytics() {
            // Create export data
            const exportData = {
                generated_at: new Date().toISOString(),
                total_reviews: {{ $stats['total_reviews'] }},
                approved_reviews: {{ $stats['approved_reviews'] }},
                pending_reviews: {{ $stats['pending_reviews'] }},
                average_rating: {{ $stats['average_rating'] }},
                rating_distribution: @json($ratingDistribution),
                top_products: @json($topReviewedProducts->take(10)),
                reviews_over_time: @json($reviewsOverTime)
            };

            // Convert to CSV or download as JSON
            const dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify(exportData, null, 2));
            const downloadAnchorNode = document.createElement('a');
            downloadAnchorNode.setAttribute("href", dataStr);
            downloadAnchorNode.setAttribute("download", `reviews-analytics-${new Date().toISOString().split('T')[0]}.json`);
            document.body.appendChild(downloadAnchorNode);
            downloadAnchorNode.click();
            downloadAnchorNode.remove();
        }
    </script>
@endpush
