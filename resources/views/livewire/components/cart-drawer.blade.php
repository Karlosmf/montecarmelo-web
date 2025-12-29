<?php

use Livewire\Volt\Component;
use App\Facades\Cart;
use Livewire\Attributes\On;

new class extends Component {
    public bool $showCart = false;
    public $items = [];
    public float $total = 0;

    public function mount()
    {
        $this->refreshCart();
    }

    #[On('toggle-cart')] 
    public function toggleCart()
    {
        $this->showCart = !$this->showCart;
        if ($this->showCart) {
            $this->refreshCart();
        }
    }

    #[On('cart-updated')]
    public function refreshCart()
    {
        $this->items = Cart::getDetails();
        $this->total = Cart::total() / 100; // Convert cents to main currency
        
        // Dispatch event to update navbar badge
        $this->dispatch('update-cart-badge', count: Cart::count()); 
    }

    public function removeItem($productId)
    {
        Cart::remove($productId);
        $this->refreshCart();
        // Feedback toast could go here
    }

    // Since we don't have update quantity in Service yet explicitly with simple methods (only add which overwrites or we need to check logic), 
    // let's stick to remove for now or implementing a simple add (+1) / remove/subtract logic if `add` handles replacement.
    // Looking at CartService::add, it overwrites: $cart[$productId] = ...
    // So to increment, we need current qty + 1.
    
    public function updateQuantity($productId, $change)
    {
        // Find item in current items to get details
        $item = $this->items->firstWhere('id', $productId);
        
        if (!$item) return;
        
        $newQty = $item->qty + $change;
        
        if ($newQty <= 0) {
            $this->removeItem($productId);
            return;
        }
        
        // We need to know unit type to call add again.
        Cart::add($productId, $newQty, $item->unit_type);
        $this->refreshCart();
    }

    public function openWhatsApp()
    {
        // We can't use window.open in PHP, but we can return the link or redirect.
        // Better: bind the link to the button `href` or use an action to get it.
        // Since the link changes with cart content, we can compute it on render or fetch it.
        // Let's use a computed property or just a method that returns it, but for a button href, a public property is easier if dynamic.
    }
    
    public function with()
    {
        return [
            'whatsappLink' => Cart::getWhatsAppLink(), 
        ];
    }
}; ?>

<div>
    <x-mary-drawer 
        wire:model="showCart" 
        class="w-11/12 lg:w-96 bg-base-100 text-base-content"
        right
    >
        {{-- HEADER --}}
        <div class="flex justify-between items-center p-4 border-b border-base-300">
            <h2 class="font-serif text-2xl text-primary font-bold tracking-widest">TU PEDIDO</h2>
            <x-mary-button icon="o-x-mark" class="btn-ghost btn-circle" wire:click="toggleCart" />
        </div>

        {{-- CONTENT --}}
        <div class="flex-1 overflow-y-auto p-4 space-y-4">
            @if($items->isEmpty())
                <div class="flex flex-col items-center justify-center h-full text-gray-500">
                    <x-mary-icon name="o-shopping-bag" class="w-16 h-16 mb-4 opacity-50"/>
                    <p class="text-lg">Tu carrito está vacío</p>
                    <x-mary-button class="btn-primary btn-outline mt-4" label="Ver Productos" link="/products" @click="$wire.showCart = false" />
                </div>
            @else
                @foreach($items as $item)
                    <div class="flex gap-4 p-3 bg-base-200 rounded-lg border border-base-300 relative group">
                        {{-- Image --}}
                        <div class="w-20 h-20 flex-shrink-0 bg-base-300 rounded-md overflow-hidden">
                             @if($item->image_path)
                                <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-500 bg-gray-200">
                                    <x-mary-icon name="o-photo" class="w-8 h-8"/>
                                </div>
                            @endif
                        </div>
                        
                        {{-- Details --}}
                        <div class="flex-1 flex flex-col justify-between">
                            <div>
                                <h3 class="font-bold text-lg leading-tight">{{ $item->name }}</h3>
                                <p class="text-xs text-gray-500 uppercase tracking-wide">{{ $item->category }}</p>
                            </div>
                            
                            <div class="flex justify-between items-end">
                                <div class="text-sm">
                                    @if($item->unit_type == 'kg')
                                        {{ $item->qty }}g
                                    @else
                                        {{ $item->qty }} un.
                                    @endif
                                    
                                    <span class="text-primary font-bold ml-2">
                                        ${{ number_format($item->subtotal / 100, 2) }}
                                    </span>
                                </div>
                                
                                {{-- Controls --}}
                                <div class="flex items-center gap-1">
                                    <button wire:click="updateQuantity({{ $item->id }}, -1)" class="btn btn-xs btn-circle btn-ghost text-gray-500 hover:text-error">
                                        <x-mary-icon name="o-minus" class="w-3 h-3"/>
                                    </button>
                                    
                                    {{-- Trash (if qty 1 or just explicit remove) --}}
                                    <button wire:click="removeItem({{ $item->id }})" class="btn btn-xs btn-circle btn-ghost text-error">
                                        <x-mary-icon name="o-trash" class="w-4 h-4"/>
                                    </button>
                                    
                                     <button wire:click="updateQuantity({{ $item->id }}, 1)" class="btn btn-xs btn-circle btn-ghost text-gray-500 hover:text-primary">
                                        <x-mary-icon name="o-plus" class="w-3 h-3"/>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        {{-- FOOTER --}}
        @if(!$items->isEmpty())
        <div class="p-4 border-t border-base-300 bg-base-200/50">
            <div class="flex justify-between items-center mb-4">
                <span class="text-gray-500 uppercase text-sm tracking-wider">Total Estimado</span>
                <span class="text-2xl font-serif font-bold text-primary">${{ number_format($total, 2) }}</span>
            </div>
            
            <a href="{{ $whatsappLink }}" target="_blank" class="btn btn-primary w-full text-primary-content font-bold tracking-widest">
                <x-mary-icon name="o-chat-bubble-left-right" class="w-5 h-5 mr-2"/>
                FINALIZAR EN WHATSAPP
            </a>
             <p class="text-xs text-center text-gray-500 mt-2">
                El pedido se coordinará vía WhatsApp.
            </p>
        </div>
        @endif
    </x-mary-drawer>
</div>
