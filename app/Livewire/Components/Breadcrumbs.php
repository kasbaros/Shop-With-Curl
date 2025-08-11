<?php

namespace App\Livewire\Components;

use Illuminate\Support\Str;
use Livewire\Component;

class Breadcrumbs extends Component
{
    public array $items = [];

    public function mount($items = [])
    {
        $this->items = $this->buildBreadcrumbs($items);
    }

    private function buildBreadcrumbs($customItems = []): array
    {
        if (!empty($customItems)) {
            return $this->formatItems($customItems);
        }

        // Auto-generate based on current route
        $breadcrumbs = [['label' => 'Home', 'url' => route('home')]];

        $routeName = request()->route()->getName();
        $routeParams = request()->route()->parameters();

        switch ($routeName) {
            case 'categories.index':
                $breadcrumbs[] = ['label' => 'Categories', 'url' => null];
                break;

            case 'categories.show':
                $category = $routeParams['category'] ?? null;
                $breadcrumbs[] = ['label' => 'Categories', 'url' => route('categories.index')];
                if ($category) {
                    $breadcrumbs[] = ['label' => $category->name, 'url' => null];
                }
                break;

            case 'shop.index':
                $breadcrumbs[] = ['label' => 'Shop', 'url' => null];
                break;

            case 'products.index':
                $breadcrumbs[] = ['label' => 'Products', 'url' => null];
                break;

            case 'products.category':
                $category = $routeParams['category'] ?? null;
                $breadcrumbs[] = ['label' => 'Products', 'url' => route('products.index')];
                if ($category) {
                    // Add parent category if exists
                    if ($category->parent) {
                        $breadcrumbs[] = ['label' => $category->parent->name, 'url' => route('products.category', $category->parent->slug)];
                    }
                    $breadcrumbs[] = ['label' => $category->name, 'url' => null];
                }
                break;

            case 'products.show':
                $product = $routeParams['product'] ?? null;
                $breadcrumbs[] = ['label' => 'Products', 'url' => route('products.index')];
                if ($product) {
                    // Add main category
                    $mainCategory = $product->categories->first();
                    if ($mainCategory) {
                        if ($mainCategory->parent) {
                            $breadcrumbs[] = ['label' => $mainCategory->parent->name, 'url' => route('products.category', $mainCategory->parent->slug)];
                        }
                        $breadcrumbs[] = ['label' => $mainCategory->name, 'url' => route('products.category', $mainCategory->slug)];
                    }
                    $breadcrumbs[] = ['label' => Str::limit($product->name, 30), 'url' => null];
                }
                break;

            case 'cart.index':
                $breadcrumbs[] = ['label' => 'Shopping Cart', 'url' => null];
                break;

            case 'checkout.index':
                $breadcrumbs[] = ['label' => 'Shopping Cart', 'url' => route('cart.index')];
                $breadcrumbs[] = ['label' => 'Checkout', 'url' => null];
                break;

            default:
                // Try to generate from route name
                $segments = explode('.', $routeName);
                $url = route('home');

                foreach ($segments as $segment) {
                    $label = Str::title(str_replace('-', ' ', $segment));
                    $breadcrumbs[] = ['label' => $label, 'url' => null];
                }
        }

        return $breadcrumbs;
    }

    private function formatItems($items): array
    {
        $formatted = [['label' => 'Home', 'url' => route('home')]];

        foreach ($items as $item) {
            if (is_string($item)) {
                $formatted[] = ['label' => $item, 'url' => null];
            } elseif (is_array($item)) {
                $formatted[] = [
                    'label' => $item['label'] ?? $item['title'] ?? $item['name'] ?? 'Unknown',
                    'url' => $item['url'] ?? $item['href'] ?? null
                ];
            }
        }

        return $formatted;
    }

    public function render()
    {
        return view('livewire.components.breadcrumbs');
    }
}
