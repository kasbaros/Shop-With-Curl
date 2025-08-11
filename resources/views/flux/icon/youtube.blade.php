{{-- Custom YouTube icon (rounded rectangle with play triangle) --}}

@props([
    'variant' => 'outline',
])

@php
$classes = Flux::classes('shrink-0')
    ->add(match($variant) {
        'outline' => '[:where(&)]:size-6',
        'solid' => '[:where(&)]:size-6',
        'mini' => '[:where(&)]:size-5',
        'micro' => '[:where(&)]:size-4',
    });
@endphp

<?php switch ($variant): case ('outline'): ?>
<svg {{ $attributes->class($classes) }} data-flux-icon xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon">
  <rect x="3" y="6.5" width="18" height="11" rx="3" ry="3" />
  <path d="M11 10l4 2-4 2v-4z" fill="currentColor" stroke="none" />
</svg>
        <?php break; ?>

    <?php case ('solid'): ?>
<svg {{ $attributes->class($classes) }} data-flux-icon xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" data-slot="icon">
  <rect x="3" y="6" width="18" height="12" rx="4" ry="4" />
  <path d="M10 10l5 2.5L10 15v-5z" fill="#fff" />
</svg>
        <?php break; ?>

    <?php case ('mini'): ?>
<svg {{ $attributes->class($classes) }} data-flux-icon xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
  <rect x="2.5" y="5" width="15" height="10" rx="3" ry="3" />
  <path d="M9 8.5l4 2-4 2v-4z" fill="#fff" />
</svg>
        <?php break; ?>

    <?php case ('micro'): ?>
<svg {{ $attributes->class($classes) }} data-flux-icon xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" data-slot="icon">
  <rect x="2" y="4" width="12" height="8" rx="2.5" ry="2.5" />
  <path d="M7.5 7l3 1.5-3 1.5V7z" fill="#fff" />
</svg>
        <?php break; ?>
<?php endswitch; ?>
