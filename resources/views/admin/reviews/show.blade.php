@extends('admin.layouts.app')

@section('title', 'Review Details')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Review Details</h1>
            <p class="text-muted">
                Review for <strong>{{ $review->product->name }}</strong>
                @if($review->is_approved)
                    <span class="badge bg-success ms-2">Approved</span>
                @else
                    <span class="badge bg-warning ms-2">Pending Approval</span>
                @endif
            </p>
        </div>
        <div>
            @if(!$review->is_approved)
                <form action="{{ route('admin.reviews.approve', $review) }}" method="POST" class="d-inline me-2">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success btn-admin">
                        <i class="bi bi-check-circle"></i> Approve
                    </button>
                </form>
            @else
                <form action="{{ route('admin.reviews.reject', $review) }}" method="POST" class="d-inline me-2">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-warning btn-admin">
                        <i class="bi bi-x-circle"></i> Reject
                    </button>
                </form>
            @endif

            <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST"
                  class="d-inline me-2" onsubmit="return confirm('Are you sure you want to delete this review?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-admin">
                    <i class="bi bi-trash"></i> Delete
                </button>
            </form>

            <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline-secondary btn-admin">
                <i class="bi bi-arrow-left"></i> Back to Reviews
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Review Content -->
            <div class="table-admin mb-4">
                <div class="p-4">
                    <!-- Rating and Title -->
                    <div class="d-flex align-items-center mb-3">
                        <div class="rating me-3">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }} text-warning fs-4"></i>
                            @endfor
                        </div>
                        <div>
                            <span class="badge bg-primary fs-6">{{ $review->rating }}/5</span>
                            @if($review->is_verified_purchase)
                                <span class="badge bg-success ms-2">Verified Purchase</span>
                            @endif
                        </div>
                    </div>

                    @if($review->title)
                        <h4 class="mb-3">{{ $review->title }}</h4>
                    @endif

                    <!-- Review Comment -->
                    <div class="review-comment bg-light p-4 rounded mb-4">
                        <p class="mb-0 lh-lg">{{ $review->comment }}</p>
                    </div>

                    <!-- Helpfulness -->
                    @if($review->helpful_count > 0 || $review->unhelpful_count > 0)
                        <div class="helpfulness-stats mb-4">
                            <div class="d-flex align-items-center">
                                <span class="text-muted me-3">Was this review helpful?</span>
                                <div class="d-flex align-items-center">
                                <span class="badge bg-success me-2">
                                    <i class="bi bi-hand-thumbs-up me-1"></i>{{ $review->helpful_count }}
                                </span>
                                    <span class="badge bg-danger">
                                    <i class="bi bi-hand-thumbs-down me-1"></i>{{ $review->unhelpful_count }}
                                </span>
                                    <span class="text-muted ms-3">
                                    ({{ number_format($review->helpfulness_ratio, 1) }}% found helpful)
                                </span>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Admin Reply Form -->
                    <div class="admin-reply-form">
                        <h6 class="mb-3">Add Admin Reply</h6>
                        <form action="{{ route('admin.reviews.reply', $review) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                            <textarea class="form-control @error('reply') is-invalid @enderror"
                                      name="reply" rows="4"
                                      placeholder="Write a professional response to this review..."
                                      required>{{ old('reply') }}</textarea>
                                @error('reply')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary btn-admin">
                                <i class="bi bi-reply"></i> Post Reply
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Existing Replies -->
            @if($review->replies->count() > 0)
                <div class="table-admin mb-4">
                    <div class="p-3 border-bottom">
                        <h5 class="mb-0">Replies ({{ $review->replies->count() }})</h5>
                    </div>
                    <div class="p-4">
                        @foreach($review->replies as $reply)
                            <div class="reply-item border-start border-3 border-primary ps-4 mb-4">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="d-flex align-items-center">
                                        @if($reply->is_admin_reply)
                                            <span class="badge bg-primary me-2">Admin</span>
                                        @endif
                                        <strong>{{ $reply->user->name ?? 'Anonymous' }}</strong>
                                    </div>
                                    <small class="text-muted">{{ $reply->created_at->format('M j, Y g:i A') }}</small>
                                </div>
                                <p class="mb-0">{{ $reply->content }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Review Information -->
            <div class="table-admin mb-4">
                <div class="p-3 border-bottom">
                    <h5 class="mb-0">Review Information</h5>
                </div>
                <div class="p-4">
                    <div class="mb-3">
                        <strong>Status:</strong>
                        <div class="mt-1">
                            @if($review->is_approved)
                                <span class="badge bg-success">Approved</span>
                            @else
                                <span class="badge bg-warning">Pending Approval</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <strong>Submitted:</strong>
                        <div class="text-muted">
                            {{ $review->created_at->format('M j, Y g:i A') }}
                            <small class="d-block">({{ $review->created_at->diffForHumans() }})</small>
                        </div>
                    </div>

                    @if($review->updated_at != $review->created_at)
                        <div class="mb-3">
                            <strong>Last Updated:</strong>
                            <div class="text-muted">
                                {{ $review->updated_at->format('M j, Y g:i A') }}
                                <small class="d-block">({{ $review->updated_at->diffForHumans() }})</small>
                            </div>
                        </div>
                    @endif

                    <div class="mb-3">
                        <strong>Review ID:</strong>
                        <div class="text-muted font-monospace">#{{ $review->id }}</div>
                    </div>

                    @if($review->is_verified_purchase)
                        <div class="mb-3">
                    <span class="badge bg-success">
                        <i class="bi bi-patch-check me-1"></i>Verified Purchase
                    </span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Customer Information -->
            <div class="table-admin mb-4">
                <div class="p-3 border-bottom">
                    <h5 class="mb-0">Customer Information</h5>
                </div>
                <div class="p-4">
                    @if($review->user)
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar-circle bg-primary text-white me-3">
                                {{ strtoupper(substr($review->user->name, 0, 2)) }}
                            </div>
                            <div>
                                <div class="fw-medium">{{ $review->user->name }}</div>
                                <small class="text-muted">{{ $review->user->email }}</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <strong>Customer Since:</strong>
                            <div class="text-muted">{{ $review->user->created_at->format('M Y') }}</div>
                        </div>

                        <div class="mb-3">
                            <strong>Total Reviews:</strong>
                            <div class="text-muted">{{ $review->user->reviews()->count() }}</div>
                        </div>

                        <div class="mb-3">
                            <strong>Average Rating Given:</strong>
                            <div class="text-muted">
                                {{ number_format($review->user->reviews()->avg('rating') ?: 0, 1) }}/5
                            </div>
                        </div>

                        <a href="{{ route('admin.users.show', $review->user) }}" class="btn btn-outline-primary btn-sm w-100">
                            <i class="bi bi-person"></i> View Customer Profile
                        </a>
                    @else
                        <div class="text-center text-muted">
                            <i class="bi bi-person-x display-4 d-block mb-2"></i>
                            <div>Anonymous Review</div>
                            <small>Customer account not found</small>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Product Information -->
            <div class="table-admin mb-4">
                <div class="p-3 border-bottom">
                    <h5 class="mb-0">Product Information</h5>
                </div>
                <div class="p-4">
                    <div class="d-flex align-items-center mb-3">
                        @if($review->product->images->first())
                            <img src="{{ Storage::url($review->product->images->first()->image_path) }}"
                                 class="rounded me-3" width="60" height="60" style="object-fit: cover;">
                        @else
                            <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center"
                                 style="width: 60px; height: 60px;">
                                <i class="bi bi-image text-muted"></i>
                            </div>
                        @endif
                        <div class="flex-grow-1">
                            <div class="fw-medium">{{ $review->product->name }}</div>
                            <small class="text-muted">SKU: {{ $review->product->sku }}</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <strong>Category:</strong>
                        <div class="text-muted">{{ $review->product->category->name ?? 'Uncategorized' }}</div>
                    </div>

                    <div class="mb-3">
                        <strong>Price:</strong>
                        <div class="text-muted">
                            @if($review->product->sale_price)
                                <span class="text-decoration-line-through">${{ number_format($review->product->price, 2) }}</span>
                                <span class="text-danger fw-bold">${{ number_format($review->product->sale_price, 2) }}</span>
                            @else
                                ${{ number_format($review->product->price, 2) }}
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <strong>Product Rating:</strong>
                        <div class="d-flex align-items-center">
                            <div class="me-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= $review->product->average_rating ? '-fill' : '' }} text-warning"></i>
                                @endfor
                            </div>
                            <span class="text-muted">
                            {{ number_format($review->product->average_rating ?: 0, 1) }}
                            ({{ $review->product->review_count }} reviews)
                        </span>
                        </div>
                    </div>

                    <a href="{{ route('admin.products.show', $review->product) }}" class="btn btn-outline-primary btn-sm w-100">
                        <i class="bi bi-box"></i> View Product Details
                    </a>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="table-admin">
                <div class="p-3 border-bottom">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="p-4">
                    <div class="d-grid gap-2">
                        @if(!$review->is_approved)
                            <form action="{{ route('admin.reviews.approve', $review) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success btn-admin w-100">
                                    <i class="bi bi-check-circle"></i> Approve Review
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.reviews.reject', $review) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-warning btn-admin w-100">
                                    <i class="bi bi-x-circle"></i> Reject Review
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('admin.reviews.index', ['product_id' => $review->product_id]) }}"
                           class="btn btn-outline-info btn-admin w-100">
                            <i class="bi bi-list"></i> View Product Reviews
                        </a>

                        @if($review->user)
                            <a href="{{ route('admin.reviews.index', ['search' => $review->user->email]) }}"
                               class="btn btn-outline-secondary btn-admin w-100">
                                <i class="bi bi-person"></i> View Customer Reviews
                            </a>
                        @endif

                        <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST"
                              onsubmit="return confirm('Are you sure you want to delete this review?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-admin w-100">
                                <i class="bi bi-trash"></i> Delete Review
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .avatar-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.875rem;
        }

        .reply-item {
            border-left: 3px solid var(--bs-primary) !important;
        }
    </style>
@endpush
