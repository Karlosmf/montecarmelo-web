<?php

use function Livewire\Volt\{state, layout, uses};
use App\Models\Product;
use App\Models\GalleryImage;
// Removed Toast usage as it is now in the child component, 
// though if this parent component needs to show toasts it should be kept.
// Keeping it simpler.
use Mary\Traits\Toast;

// uses([Toast::class]); // Not needed here anymore if logic moved.
layout('components.layouts.app');

state([
    'masterPicks' => fn() => \App\Models\MasterPick::with('product')
        ->where('is_active', true)
        ->orderBy('order')
        ->get(),
    'slides' => fn() => \App\Models\Slide::where('is_active', true)->orderBy('order')->get(),
    'galleryImages' => fn() => GalleryImage::where('is_active', true)->orderBy('order')->get(),
]);

?>

<div class="font-sans text-text-main overflow-x-hidden">

    {{-- 1. HERO SLIDER SECTION --}}
    <x-home.hero :slides="$slides" />

    {{-- 2. SECTION "NUESTRA HISTORIA" --}}
    <x-home.nuestra-historia />

    {{-- 3. SECTION "SELECCIÓN DEL MAESTRO" --}}
    <x-home.featured-products :picks="$masterPicks" />

    {{-- 4. SECTION "GALLERY" --}}
    <x-home.gallery :images="$galleryImages" />

    {{-- 5. SECTION "CONTACT FOOTER" - Volt Component --}}
    <livewire:components.home-contact-form />

</div>