{{-- Custom Instagram icon (rounded square with circle) --}}

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
  <rect x="3.5" y="3.5" width="17" height="17" rx="4.5" ry="4.5" />
  <circle cx="12" cy="12" r="4" />
  <circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none" />
</svg>
        <?php break; ?>

    <?php case ('solid'): ?>
<svg {{ $attributes->class($classes) }} data-flux-icon xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" data-slot="icon">
  <rect x="3" y="3" width="18" height="18" rx="5" ry="5" />
  <circle cx="12" cy="12" r="5" fill="#fff" fill-opacity="0.25" />
  <circle cx="17.5" cy="6.5" r="1.2" />
</svg>
        <?php break; ?>

    <?php case ('mini'): ?>
<svg {{ $attributes->class($classes) }} data-flux-icon xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
  <rect x="2.5" y="2.5" width="15" height="15" rx="4" ry="4" />
  <circle cx="10" cy="10" r="4" fill="#fff" fill-opacity="0.25" />
  <circle cx="14.5" cy="5.5" r="1" />
</svg>
        <?php break; ?>

    <?php case ('micro'): ?>
<svg {{ $attributes->class($classes) }} data-flux-icon xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" data-slot="icon">
  <rect x="2" y="2" width="12" height="12" rx="3" ry="3" />
  <circle cx="8" cy="8" r="3" fill="#fff" fill-opacity="0.25" />
  <circle cx="11.5" cy="4.5" r=".8" />
</svg>
        <?php break; ?>
<?php endswitch; ?>
