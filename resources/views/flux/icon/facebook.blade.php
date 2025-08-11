{{-- Custom Facebook icon (simple fallback with letter) --}}

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
  <rect x="3.5" y="3.5" width="17" height="17" rx="3" />
  <text x="12" y="14" font-size="10" text-anchor="middle" fill="currentColor" stroke="none" font-family="ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial">f</text>
</svg>
        <?php break; ?>

    <?php case ('solid'): ?>
<svg {{ $attributes->class($classes) }} data-flux-icon xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" data-slot="icon">
  <rect x="3" y="3" width="18" height="18" rx="3" />
  <text x="12" y="14" font-size="10" text-anchor="middle" fill="#fff" stroke="none" font-family="ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial">f</text>
</svg>
        <?php break; ?>

    <?php case ('mini'): ?>
<svg {{ $attributes->class($classes) }} data-flux-icon xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
  <rect x="2.5" y="2.5" width="15" height="15" rx="3" />
  <text x="10" y="12" font-size="8" text-anchor="middle" fill="#fff" stroke="none" font-family="ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial">f</text>
</svg>
        <?php break; ?>

    <?php case ('micro'): ?>
<svg {{ $attributes->class($classes) }} data-flux-icon xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" data-slot="icon">
  <rect x="2" y="2" width="12" height="12" rx="2.5" />
  <text x="8" y="10" font-size="6" text-anchor="middle" fill="#fff" stroke="none" font-family="ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial">f</text>
</svg>
        <?php break; ?>
<?php endswitch; ?>
