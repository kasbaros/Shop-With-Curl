@props(['heading' => '', 'subheading' => ''])

<div class="mb-8">
    @if($heading)
        <h3 class="text-lg font-medium">{{ $heading }}</h3>
    @endif
    @if($subheading)
        <p class="mt-1 text-sm text-gray-500">{{ $subheading }}</p>
    @endif
    <div class="mt-5">
        {{ $slot }}
    </div>
</div>
