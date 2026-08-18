<?php

use Livewire\Volt\Component;
use Livewire\Attributes\On;
use App\Facades\Cart;

new class extends Component {
    public int $cartCount = 0;

    public function mount()
    {
        $this->cartCount = Cart::count();
    }

    #[On('update-cart-badge')]
    public function updateCartCount($count)
    {
        $this->cartCount = $count;
    }
    
    public function openCart()
    {
        $this->dispatch('toggle-cart');
    }
}; ?>

<nav data-navbar class="fixed top-0 w-full z-50 bg-gradient-to-b from-black/85 via-black/55 to-black/20 transition-all duration-500">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="flex items-center justify-between gap-6 h-16 lg:h-20">

            {{-- Mobile: Hamburger + Logo centrado + Carrito --}}
            <div class="flex lg:hidden items-center justify-between w-full">
                <label for="main-drawer" class="text-text-main hover:text-primary transition-colors cursor-pointer" aria-label="Abrir menú">
                    <i class="ph ph-list text-2xl leading-none" aria-hidden="true"></i>
                </label>

                <x-logo class="h-9" />

                <div class="relative cursor-pointer text-text-main hover:text-primary transition-colors" wire:click="openCart" aria-label="Abrir carrito">
                    @if($cartCount > 0)
                        <span class="cart-badge">{{ $cartCount }}</span>
                    @endif
                    <i class="ph ph-shopping-bag text-2xl leading-none" aria-hidden="true"></i>
                </div>
            </div>

            {{-- Desktop: Logo a la izquierda --}}
            <x-logo class="hidden lg:block h-11" />

            {{-- Desktop: Navegación centrada --}}
            <nav class="hidden lg:flex flex-1 justify-center" aria-label="Principal">
                <ul class="flex items-center gap-10">
                    <li><a href="/" wire:navigate class="nav-link">Inicio</a></li>
                    <li><a href="/products" wire:navigate class="nav-link">Productos</a></li>
                    <li><a href="/#nuestra-historia" wire:navigate class="nav-link">Nosotros</a></li>
                    <li><a href="/contact" wire:navigate class="nav-link">Contacto</a></li>
                </ul>
            </nav>

            {{-- Desktop: Iconos a la derecha --}}
            <div class="hidden lg:flex items-center gap-6 text-text-main">
                <a href="/products" aria-label="Buscar productos" class="hover:text-primary transition-colors duration-300">
                    <i class="ph ph-magnifying-glass text-xl leading-none" aria-hidden="true"></i>
                </a>

                <div class="relative indicator cursor-pointer hover:text-primary transition-colors duration-300" wire:click="openCart" aria-label="Abrir carrito">
                    @if($cartCount > 0)
                        <span class="cart-badge">{{ $cartCount }}</span>
                    @endif
                    <i class="ph ph-shopping-bag text-xl leading-none" aria-hidden="true"></i>
                </div>

                <a href="/admin/dashboard" aria-label="Administración" class="hover:text-primary transition-colors duration-300">
                    <i class="ph ph-user text-xl leading-none" aria-hidden="true"></i>
                </a>
            </div>

        </div>
    </div>
</nav>