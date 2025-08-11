<?php

    namespace Database\Seeders;

    use App\Models\Banner;
    use Illuminate\Database\Seeder;

    class BannerSeeder extends Seeder
    {
        public function run(): void
        {
            $banners = [
                [
                    'title' => "Living\nBeyond\nLimits",
                    'subtitle' => 'Premium Collection',
                    'description' => 'Experience unparalleled comfort and revolutionary design that transforms your active lifestyle into an extraordinary journey.',
                    'image' => 'banners/default-banner-1.jpg', // You'll need to add these images
                    'button_text' => 'Explore Collection',
                    'button_link' => '/shop',
                    'secondary_button_text' => 'Watch Story',
                    'secondary_button_link' => '/about',
                    'sort_order' => 1,
                    'is_active' => true,
                ],
                [
                    'title' => "Unleash\nYour\nPotential",
                    'subtitle' => 'Performance Series',
                    'description' => 'Engineered for peak performance with cutting-edge technology that adapts to your every movement and amplifies your strength.',
                    'image' => 'banners/default-banner-2.jpg',
                    'button_text' => 'Discover Power',
                    'button_link' => '/shop',
                    'secondary_button_text' => 'Learn More',
                    'secondary_button_link' => '/products',
                    'sort_order' => 2,
                    'is_active' => true,
                ],
                [
                    'title' => "Redefine\nThe\nFuture",
                    'subtitle' => 'Innovation Lab',
                    'description' => 'Step into tomorrow with groundbreaking innovations that merge style, function, and sustainability for the conscious athlete.',
                    'image' => 'banners/default-banner-3.jpg',
                    'button_text' => 'Join Revolution',
                    'button_link' => '/shop',
                    'secondary_button_text' => 'See Innovation',
                    'secondary_button_link' => '/innovation',
                    'sort_order' => 3,
                    'is_active' => true,
                ],
            ];

            foreach ($banners as $banner) {
                Banner::create($banner);
            }
        }
    }
