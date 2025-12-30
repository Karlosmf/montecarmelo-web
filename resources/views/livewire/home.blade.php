<?php

use function Livewire\Volt\{state, layout, uses};
use App\Models\Product;
// Removed Toast usage as it is now in the child component, 
// though if this parent component needs to show toasts it should be kept.
// Keeping it simpler.
use Mary\Traits\Toast;

// uses([Toast::class]); // Not needed here anymore if logic moved.
layout('components.layouts.app');

state(['featuredProducts' => fn() => Product::where('is_featured', true)->take(3)->get()]);

?>

<div class="font-sans text-text-main bg-background-main overflow-x-hidden">

    {{-- 1. HERO SLIDER SECTION --}}
    <x-home.hero />

    {{-- 2. SECTION "SOMOS" --}}
    <x-home.about />

    {{-- 3. SECTION "STORYTELLING" --}}
    <x-home.story />

    {{-- 4. SECTION "GALLERY" --}}
    <x-home.gallery />

    {{-- 5. SECTION "CONTACT FOOTER" - Volt Component --}}
    <livewire:components.home-contact-form />

</div>