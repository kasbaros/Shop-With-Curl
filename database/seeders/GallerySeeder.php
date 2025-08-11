<?php

    namespace Database\Seeders;

    use App\Models\GalleryItem;
    use App\Models\Product;
    use Illuminate\Database\Seeder;

    class GallerySeeder extends Seeder
    {
        public function run(): void
        {
            $galleryItems = [
                [
                    'image' => 'images/products/shop_with_carl-10.jpg',
                    'caption' => 'Perfect workout companion',
                    'hashtags' => ['shopwithcarl', 'activewear', 'fitness'],
                    'link' => null,
                    'product_id' => Product::first()?->id,
                    'source_type' => 'customer',
                    'is_featured' => true,
                    'is_active' => true,
                    'sort_order' => 1,
                ],
                [
                    'image' => 'images/products/shop_with_carl-4.jpg',
                    'caption' => 'Comfort meets style',
                    'hashtags' => ['shopwithcarl', 'lifestyle', 'comfort'],
                    'link' => null,
                    'product_id' => Product::skip(1)->first()?->id,
                    'source_type' => 'customer',
                    'is_featured' => true,
                    'is_active' => true,
                    'sort_order' => 2,
                ],
                [
                    'image' => 'images/products/shop_with_carl-6.jpg',
                    'caption' => 'Elevate your workout',
                    'hashtags' => ['shopwithcarl', 'workout', 'motivation'],
                    'link' => null,
                    'product_id' => Product::skip(2)->first()?->id,
                    'source_type' => 'customer',
                    'is_featured' => true,
                    'is_active' => true,
                    'sort_order' => 3,
                ],
                [
                    'image' => 'images/products/shop_with_carl-36.jpg',
                    'caption' => 'Unleash your potential',
                    'hashtags' => ['shopwithcarl', 'strength', 'performance'],
                    'link' => null,
                    'product_id' => Product::skip(3)->first()?->id,
                    'source_type' => 'customer',
                    'is_featured' => true,
                    'is_active' => true,
                    'sort_order' => 4,
                ],
                [
                    'image' => 'images/products/shop_with_carl-39.jpg',
                    'caption' => 'Beyond limits',
                    'hashtags' => ['shopwithcarl', 'limitless', 'athletic'],
                    'link' => null,
                    'product_id' => Product::skip(4)->first()?->id,
                    'source_type' => 'customer',
                    'is_featured' => true,
                    'is_active' => true,
                    'sort_order' => 5,
                ],
            ];

            foreach ($galleryItems as $item) {
                GalleryItem::create($item);
            }
        }
    }
