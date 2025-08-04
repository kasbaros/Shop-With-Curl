<div class="relative">
    <div class="relative">
        <input
            type="text"
            wire:model.live.debounce.300ms="query"
            wire:focus="showResults"
            wire:blur="hideResults"
            placeholder="Search products..."
            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
        >

        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <x-heroicon-o-magnifying-glass class="w-5 h-5 text-gray-400" />
        </div>

        @if($query)
            <button
                wire:click="$set('query', '')"
                class="absolute inset-y-0 right-0 pr-3 flex items-center"
            >
                <x-heroicon-m-x-mark class="w-5 h-5 text-gray-400 hover:text-gray-600" />
            </button>
        @endif
    </div>

    <!-- Search Results Dropdown -->
    @if($showResults && !empty($results))
        <div class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-80 overflow-y-auto">
            @foreach($results as $result)
                <button
                    wire:click="selectResult('{{ $result['slug'] }}')"
                    class="w-full px-4 py-3 text-left hover:bg-gray-50 flex items-center space-x-3 border-b border-gray-100 last:border-b-0"
                >
                    <img
                        src="{{ $result['image'] ?: '/images/placeholder.png' }}"
                        alt="{{ $result['name'] }}"
                        class="w-12 h-12 object-cover rounded-md"
                    >
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">
                            {{ $result['name'] }}
                        </p>
                        <p class="text-sm text-gray-500">
                            {{ $result['category'] }}
                        </p>
                        <p class="text-sm font-medium text-gray-900">
                            ${{ number_format($result['price'], 2) }}
                        </p>
                    </div>
                </button>
            @endforeach

            @if(strlen($query) >= 2)
                <div class="px-4 py-3 border-t border-gray-100">
                    <button
                        wire:click="search"
                        class="text-sm text-blue-600 hover:text-blue-700"
                    >
                        View all results for "{{ $query }}"
                    </button>
                </div>
            @endif
        </div>
    @endif
</div>
