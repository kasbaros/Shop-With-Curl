<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;

    class LookbookItem extends Model
    {
        protected $fillable = [
            'lookbook_id',
            'product_id',
            'sort_order',
            'preset_variant',
        ];

        protected $casts = [
            'preset_variant' => 'array',
        ];

        public function lookbook()
        {
            return $this->belongsTo(Lookbook::class);
        }

        public function product()
        {
            return $this->belongsTo(Product::class);
        }
    }
