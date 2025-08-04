<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class ReviewReply extends Model
    {
        use HasFactory;

        protected $fillable = [
            'review_id',
            'user_id',
            'content',
            'is_admin_reply',
        ];

        protected $casts = [
            'is_admin_reply' => 'boolean',
        ];

        /**
         * Get the review that owns the reply
         */
        public function review()
        {
            return $this->belongsTo(Review::class);
        }

        /**
         * Get the user that wrote the reply
         */
        public function user()
        {
            return $this->belongsTo(User::class);
        }
    }
