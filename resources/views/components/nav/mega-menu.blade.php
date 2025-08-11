{{-- resources/views/components/nav/mega-menu.blade.php --}}
@php
    use App\Models\Category;

    // Get the main category groups
    $byStyle = Category::where('name', 'By Style')->first();
    $byOccasion = Category::where('name', 'By Occasion')->first();
    $collections = Category::where('name', 'Collections')->first();

    // Get children for each group
    $styleItems = $byStyle ? $byStyle->children()->active()->orderBy('sort_order')->get() : collect();
    $occasionItems = $byOccasion ? $byOccasion->children()->active()->orderBy('sort_order')->get() : collect();
    $collectionItems = $collections ? $collections->children()->active()->orderBy('sort_order')->get() : collect();
@endphp

<div class="mega-menu">
    <div class="mega-menu-grid">
        @if($occasionItems->count())
            <div class="mega-menu-section">
                <h4>By Occasion</h4>
                @foreach($occasionItems as $cat)
                    <a href="{{ route('products.category', $cat->slug) }}"
                       class="mega-menu-item"
                       wire:navigate>
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>
        @endif

        @if($collectionItems->count())
            <div class="mega-menu-section">
                <h4>Collections</h4>
                @foreach($collectionItems as $cat)
                    <a href="{{ route('products.category', $cat->slug) }}"
                       class="mega-menu-item"
                       wire:navigate>
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>
        @endif

        @if($styleItems->count())
            <div class="mega-menu-section">
                <h4>By Style</h4>
                @foreach($styleItems as $cat)
                    <a href="{{ route('products.category', $cat->slug) }}"
                       class="mega-menu-item"
                       wire:navigate>
                        {{ $cat->name }}
                    </a>
                @endforeach
                <a href="{{ route('categories.index') }}"
                   class="mega-menu-item"
                   wire:navigate>
                    View All Categories
                </a>
            </div>
        @endif
    </div>
</div>
