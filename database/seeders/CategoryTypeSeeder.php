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
                'Bras' => [
                    'subcategories' => [
                        'Wireless non-padded',
                        'Pushup',
                        'Strapless',
                        'Seamless',
                        'Lace',
                        'Sports bra',
                        'Mastectomy bras'
                    ],
                    'image' => 'images/categories/bras.jpg'
                ],
                'Shape wears' => [
                    'subcategories' => [
                        'Post surgical',
                        'Casual',
                        'Medical',
                        'Waist trainers',
                        'Body suits',
                        'Seamless',
                        'Padded'
                    ],
                    'image' => 'images/categories/shapewear.jpg'
                ],
                'Active wears' => [
                    'subcategories' => [
                        'Jumpsuits & Romper',
                        '2pc set',
                        '3pc set',
                        'Travel suits',
                        'Shorts',
                        'Leggings'
                    ],
                    'image' => 'images/categories/activewear.jpg'
                ],
                'Lounge wears' => [
                    'subcategories' => [
                        'Wool blend',
                        'Luxury'
                    ],
                    'image' => 'images/categories/loungewear.jpg'
                ],
                'Panties' => [
                    'subcategories' => [
                        'Seamless',
                        'Cotton',
                        'Lace'
                    ],
                    'image' => 'images/categories/panties.jpg'
                ],
            ];

            $order = 1;
            foreach ($types as $typeName => $typeData) {
                $type = Category::updateOrCreate(
                    ['slug' => Str::slug($typeName)],
                    [
                        'name' => $typeName,
                        'parent_id' => null,
                        'sort_order' => $order++,
                        'is_active' => true,
                        'image' => $typeData['image'],
                    ]
                );

                $childOrder = 1;
                foreach ($typeData['subcategories'] as $child) {
                    Category::updateOrCreate(
                        ['slug' => Str::slug($child)],
                        [
                            'name' => $child,
                            'parent_id' => $type->id,
                            'sort_order' => $childOrder++,
                            'is_active' => true,
                            'image' => 'images/placeholder-category.svg',
                        ]
                    );
                }
            }
        }
    }
