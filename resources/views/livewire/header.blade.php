<button
    wire:click="toggleCartDrawer"
    class="relative flex items-center p-2 text-gray-700 hover:text-blue-600 transition-colors"
>
    <x-heroicon-o-shopping-bag class="w-6 h-6" />

    @if($itemCount > 0)
        <span class="absolute -top-1 -right-1 bg-blue-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
            {{ $itemCount }}
        </span>
    @endif

    <span class="ml-2 text-sm font-medium">
        @if($itemCount > 0)
            ${{ number_format($total, 2) }}
        @else
            Cart
        @endif
    </span>
</button>
