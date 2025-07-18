@props([
    'href' => '#',
    'active' => false,
    'icon' => null,
    'method' => 'GET',
    'as' => 'a',
])

@php
    $classes = 'flex items-center w-full px-3 py-2 text-left text-sm text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800 focus:outline-none focus:bg-zinc-50 dark:focus:bg-zinc-800 transition duration-150 ease-in-out rounded-md';

    if ($active) {
        $classes .= ' bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100';
    }
@endphp

@if ($as === 'button' || $method !== 'GET')
    <form method="POST" action="{{ $href }}" class="w-full">
        @csrf
        @if ($method !== 'POST')
            @method($method)
        @endif
        <button type="submit" {{ $attributes->merge(['class' => $classes]) }}>
            @if ($icon)
                <flux:icon :icon="$icon" class="mr-2 size-4" />
            @endif
            {{ $slot }}
        </button>
    </form>
@else
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if ($icon)
            <flux:icon :icon="$icon" class="mr-2 size-4" />
        @endif
        {{ $slot }}
    </a>
@endif
