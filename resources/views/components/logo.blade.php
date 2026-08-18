@props(['class' => '', 'style' => ''])

<a href="/" wire:navigate
   {{ $attributes->merge(['class' => 'logo-gold ' . $class]) }}
   style="aspect-ratio: 1629/815; {{ $style }}"
   aria-label="Monte Carmelo"
   title="Monte Carmelo"></a>