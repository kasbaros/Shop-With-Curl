<?php

    namespace Database\Seeders;

    use App\Models\Category;
    use Illuminate\Database\Seeder;
    use Illuminate\Support\Str;

    class CategoryTypeSeeder extends Seeder
    {
        public function run(): void
        {
            $types = [
                'By Occasion' => [
                    'Everyday Comfort',
                    'Special Occasion',
                    'Sports & Active',
                    'Sleep & Lounge',
                ],
                'Collections' => [
                    'Luxury Lace',
                    'Seamless Collection',
                    'Designer Series',
                ],
                'By Style' => [
                    'Minimalist Essentials',
                    'Streetwear Core',
                    'Athleisure',
                    'Classic Tailored',
                    'Bold & Graphic',
                    'Oversized Fits',
                    'Seamless Basics',
                    'Ribbed Knit',
                    'Compression Fit',
                    'Breathable Mesh',
                ],
            ];

            $order = 1;
            foreach ($types as $typeName => $children) {
                $type = Category::firstOrCreate(
                    ['slug' => Str::slug($typeName)],
                    [
                        'name' => $typeName,
                        'parent_id' => null,
                        'sort_order' => $order++,
                        'is_active' => true,
                        // Optional: 'image_url' => asset('images/placeholder-category.jpg'),
                    ]
                );

                $childOrder = 1;
                foreach ($children as $child) {
                    Category::firstOrCreate(
                        ['slug' => Str::slug($child)],
                        [
                            'name' => $child,
                            'parent_id' => $type->id,
                            'sort_order' => $childOrder++,
                            'is_active' => true,
                            // Optional: 'image_url' => asset('images/placeholder-category.jpg'),
                        ]
                    );
                }
            }
        }
    }
