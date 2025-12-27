<?php

use function Livewire\Volt\{state};

state(['cartCount' => 0]);

?>

<x-mary-nav sticky full-width class="bg-base-100/90 backdrop-blur-md border-b border-base-200">
    <x-slot:brand>
        {{-- Drawer toggle for mobile --}}
        <label for="main-drawer" class="lg:hidden mr-3">
            <x-mary-icon name="o-bars-3" class="cursor-pointer" />
        </label>
        
        {{-- Brand Logo --}}
        <a href="/" class="text-2xl font-serif font-bold text-primary tracking-widest hover:text-primary-focus transition">
            MONTE CARMELO
        </a>
    </x-slot:brand>

    <x-slot:actions>
        {{-- Desktop Menu --}}
        <div class="hidden lg:flex gap-6 mr-8 text-sm uppercase tracking-wide font-semibold text-base-content/80">
            <a href="/" class="hover:text-primary transition">Inicio</a>
            <a href="/products" class="hover:text-primary transition">Productos</a>
            <a href="#" class="hover:text-primary transition">Nosotros</a>
            <a href="#" class="hover:text-primary transition">Mayoristas</a>
        </div>

        {{-- Action Icons --}}
        <div class="flex items-center gap-3">
            <x-mary-button icon="o-magnifying-glass" class="btn-ghost btn-sm btn-circle" />
            <x-mary-button icon="o-user" class="btn-ghost btn-sm btn-circle" />
            
            <div class="indicator">
                <span class="indicator-item badge badge-secondary badge-xs mr-2 mt-2">{{ $cartCount }}</span> 
                <x-mary-button icon="o-shopping-bag" class="btn-ghost btn-sm btn-circle" />
            </div>
        </div>
    </x-slot:actions>
</x-mary-nav>
