<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Builder;

    class Review extends Model
    {
        use HasFactory;

        protected $fillable = [
            'product_id',
            'user_id',
            'rating',
            'title',
            'comment',
            'is_approved',
            'is_verified_purchase',
            'helpful_count',
            'unhelpful_count',
        ];

        protected $casts = [
            'is_approved' => 'boolean',
            'is_verified_purchase' => 'boolean',
            'rating' => 'integer',
            'helpful_count' => 'integer',
            'unhelpful_count' => 'integer',
        ];

        /**
         * Get the product that owns the review
         */
        public function product()
        {
            return $this->belongsTo(Product::class);
        }

        /**
         * Get the user that wrote the review
         */
        public function user()
        {
            return $this->belongsTo(User::class);
        }

        /**
         * Get the replies for the review
         */
        public function replies()
        {
            return $this->hasMany(ReviewReply::class);
        }

        /**
         * Scope for approved reviews
         */
        public function scopeApproved(Builder $query): Builder
        {
            return $query->where('is_approved', true);
        }

        /**
         * Scope for pending reviews
         */
        public function scopePending(Builder $query): Builder
        {
            return $query->where('is_approved', false);
        }

        /**
         * Scope for verified purchases
         */
        public function scopeVerifiedPurchase(Builder $query): Builder
        {
            return $query->where('is_verified_purchase', true);
        }

        /**
         * Get rating as stars
         */
        public function getRatingStarsAttribute(): string
        {
            return str_repeat('★', $this->rating) . str_repeat('☆', 5 - $this->rating);
        }

        /**
         * Check if review is helpful
         */
        public function getHelpfulnessRatioAttribute(): float
        {
            $total = $this->helpful_count + $this->unhelpful_count;
            return $total > 0 ? ($this->helpful_count / $total) * 100 : 0;
        }
    }
