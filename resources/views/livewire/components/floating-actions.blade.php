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

    public function whatsappUrl(): string
    {
        $phone = preg_replace('/\D/', '', \App\Support\Settings::get('contact.phone', '+54 9 3482 53-5220'));

        return 'https://wa.me/' . $phone;
    }
};
?>

<div class="fixed bottom-5 right-5 z-40 flex flex-col items-end gap-3">
    {{-- Carrito flotante --}}
    <button wire:click="openCart" aria-label="Abrir carrito"
        class="relative w-14 h-14 rounded-full bg-primary text-black shadow-lg shadow-primary/30 flex items-center justify-center hover:bg-primary-hover transition-all duration-300 cursor-pointer">
        <i class="ph ph-shopping-bag-open text-2xl leading-none" aria-hidden="true"></i>
        @if($cartCount > 0)
            <span class="cart-badge cart-badge--wine">{{ $cartCount }}</span>
        @endif
    </button>

    {{-- WhatsApp flotante --}}
    <a href="{{ $this->whatsappUrl() }}" target="_blank" rel="noopener" aria-label="Escribinos por WhatsApp"
        class="relative w-14 h-14 rounded-full bg-[#25D366] text-white shadow-lg shadow-green-500/30 flex items-center justify-center hover:scale-105 transition-transform duration-300">
        <i class="ph ph-whatsapp-logo text-3xl leading-none" aria-hidden="true"></i>
    </a>
</div>