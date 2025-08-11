{{-- Custom X (Twitter) icon --}}

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
  <path stroke-linecap="round" stroke-linejoin="round" d="M4 4l16 16M20 4L4 20" />
</svg>
        <?php break; ?>

    <?php case ('solid'): ?>
<svg {{ $attributes->class($classes) }} data-flux-icon xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" data-slot="icon">
  <path fill-rule="evenodd" d="M3.47 3.47a.75.75 0 0 1 1.06 0L12 10.94l7.47-7.47a.75.75 0 1 1 1.06 1.06L13.06 12l7.47 7.47a.75.75 0 1 1-1.06 1.06L12 13.06l-7.47 7.47a.75.75 0 0 1-1.06-1.06L10.94 12 3.47 4.53a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
</svg>
        <?php break; ?>

    <?php case ('mini'): ?>
<svg {{ $attributes->class($classes) }} data-flux-icon xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
  <path fill-rule="evenodd" d="M3.22 3.22a.75.75 0 0 1 1.06 0L10 8.94l5.72-5.72a.75.75 0 1 1 1.06 1.06L11.06 10l5.72 5.72a.75.75 0 1 1-1.06 1.06L10 11.06l-5.72 5.72a.75.75 0 0 1-1.06-1.06L8.94 10 3.22 4.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
</svg>
        <?php break; ?>

    <?php case ('micro'): ?>
<svg {{ $attributes->class($classes) }} data-flux-icon xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" data-slot="icon">
  <path fill-rule="evenodd" d="M2.47 2.47a.75.75 0 0 1 1.06 0L8 6.94l4.47-4.47a.75.75 0 1 1 1.06 1.06L9.06 8l4.47 4.47a.75.75 0 1 1-1.06 1.06L8 9.06l-4.47 4.47a.75.75 0 0 1-1.06-1.06L6.94 8 2.47 3.53a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
</svg>
        <?php break; ?>
<?php endswitch; ?>
