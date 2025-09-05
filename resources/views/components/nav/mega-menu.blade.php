@php
    use App\Models\Category;

    // Get all top-level categories
    $topLevelCategories = Category::whereNull('parent_id')->active()->orderBy('sort_order')->get();
@endphp

<div class="mega-menu">
    <div class="mega-menu-grid">
        @foreach($topLevelCategories as $category)
            @php
                $childItems = $category->children()->active()->orderBy('sort_order')->get();
            @endphp
            @if($childItems->count())
                <div class="mega-menu-section">
                    <h4>{{ $category->name }}</h4>
                    @foreach($childItems as $cat)
                        <a href="{{ route('products.category', $cat->slug) }}"
                           class="mega-menu-item"
                           wire:navigate>
                            {{ $cat->name }}
                        </a>
                    @endforeach
                    @if($category->name === 'Bras')
                        <a href="{{ route('categories.index') }}"
                           class="mega-menu-item"
                           wire:navigate>
                            View All Categories
                        </a>
                    @endif
                </div>
            @endif
        @endforeach
    </div>
</div>
