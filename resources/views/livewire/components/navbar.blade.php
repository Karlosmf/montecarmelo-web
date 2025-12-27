<?php

use function Livewire\Volt\{state};

state(['cartCount' => 0]);

?>

<nav class="absolute top-0 w-full z-50 bg-gradient-to-b from-black/80 to-transparent py-4">
    <div class="container mx-auto px-4 flex justify-between items-center">
        
        {{-- Mobile: Drawer Toggle & Logo Centered (Approximated by flex behavior) --}}
        <div class="flex items-center lg:hidden w-full justify-between">
            <label for="main-drawer" class="text-white">
                <x-mary-icon name="o-bars-3" class="w-6 h-6 cursor-pointer" />
            </label>
            <a href="/" class="text-2xl font-serif font-bold text-primary tracking-widest uppercase">
                Monte Carmelo
            </a>
            <div class="w-6"></div> {{-- Spacer to center logo roughly --}}
        </div>

        {{-- Desktop: Logo Left --}}
        <div class="hidden lg:flex items-center">
            <a href="/" class="text-3xl font-serif font-bold text-primary tracking-widest uppercase">
                Monte Carmelo
            </a>
        </div>

        {{-- Desktop: Links Right --}}
        <div class="hidden lg:flex items-center gap-8">
            <a href="/" class="nav-link">Inicio</a>
            <a href="/products" class="nav-link">Productos</a>
            <a href="#" class="nav-link">Nosotros</a>
            <a href="/contact" class="nav-link">Contacto</a>
        </div>

        {{-- Actions (Desktop only shown here for cleaner mobile, or adapted) --}}
        <div class="hidden lg:flex items-center gap-4 text-white">
            <x-mary-button icon="o-magnifying-glass" class="btn-ghost btn-sm btn-circle text-white hover:text-primary" />
            
             <div class="indicator">
                <span class="indicator-item badge badge-primary badge-xs mr-2 mt-2">{{ $cartCount }}</span> 
                <x-mary-button icon="o-shopping-bag" class="btn-ghost btn-sm btn-circle text-white hover:text-primary" />
            </div>
        </div>
    </div>
</nav>
