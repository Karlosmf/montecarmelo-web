<?php

use Livewire\Volt\Component;
use App\Facades\Cart;
use Livewire\Attributes\On;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

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
        $this->total = Cart::total() / 100;
        $this->dispatch('update-cart-badge', count: Cart::count());
    }

    public function removeItem($productId)
    {
        Cart::remove($productId);
        $this->refreshCart();
    }

    public function updateQuantity($productId, $change)
    {
        $item = $this->items->firstWhere('id', $productId);
        if (!$item)
            return;

        $newQty = $item->qty + $change;

        if ($newQty <= 0) {
            $this->removeItem($productId);
            return;
        }

        Cart::add($productId, $newQty, $item->unit_type);
        $this->refreshCart();
    }

    public function checkout()
    {
        if ($this->items->isEmpty())
            return;

        // Create order in DB and send email notification via Event
        $order = Cart::createOrder([
            'name' => 'Guest / WhatsApp User',
            'phone' => null
        ]);

        // Get parameterized WhatsApp link with Order ID
        $link = Cart::getWhatsAppLink($order);

        $this->showCart = false;
        $this->refreshCart(); // Cart is now empty

        // Redirect to WhatsApp
        $this->redirect($link, navigate: false);
    }
}; ?>

<div>
    <x-mary-drawer wire:model="showCart" class="w-11/12 lg:w-96 bg-base-100 text-base-content" right>
        {{-- HEADER --}}
        <div class="flex justify-between items-center p-4 border-b border-base-300">
            <h2 class="font-serif text-2xl text-primary font-bold tracking-widest">TU PEDIDO</h2>
            <x-mary-button icon="o-x-mark" class="btn-ghost btn-circle" wire:click="toggleCart" />
        </div>

        {{-- CONTENT --}}
        <div class="flex-1 overflow-y-auto p-4 space-y-4">
            @if(empty($items) || (is_object($items) && $items->isEmpty()))
                <div class="flex flex-col items-center justify-center h-full text-gray-500">
                    <x-mary-icon name="o-shopping-bag" class="w-16 h-16 mb-4 opacity-50" />
                    <p class="text-lg">Tu carrito está vacío</p>
                    <x-mary-button class="btn-primary btn-outline mt-4" label="Ver Productos" link="/products"
                        @click="$wire.showCart = false" />
                </div>
            @else
                @foreach($items as $item)
                    <div class="flex gap-4 p-3 bg-base-200 rounded-lg border border-base-300 relative group">
                        {{-- Image --}}
                        <div class="w-20 h-20 flex-shrink-0 bg-base-300 rounded-md overflow-hidden">
                            @if($item->image_path)
                                <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}"
                                    class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-500 bg-gray-200">
                                    <x-mary-icon name="o-photo" class="w-8 h-8" />
                                </div>
                            @endif
                        </div>

                        {{-- Details --}}
                        <div class="flex-1 flex flex-col justify-between">
                            <div>
                                <h3 class="font-bold text-lg leading-tight">{{ $item->name }}</h3>
                                <p class="text-xs text-gray-500 uppercase tracking-wide">
                                    {{ $item->category->name ?? $item->category }}</p>
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
                                    <button wire:click="updateQuantity({{ $item->id }}, -1)"
                                        class="btn btn-xs btn-circle btn-ghost text-gray-500 hover:text-error">
                                        <x-mary-icon name="o-minus" class="w-3 h-3" />
                                    </button>

                                    <button wire:click="removeItem({{ $item->id }})"
                                        class="btn btn-xs btn-circle btn-ghost text-error">
                                        <x-mary-icon name="o-trash" class="w-4 h-4" />
                                    </button>

                                    <button wire:click="updateQuantity({{ $item->id }}, 1)"
                                        class="btn btn-xs btn-circle btn-ghost text-gray-500 hover:text-primary">
                                        <x-mary-icon name="o-plus" class="w-3 h-3" />
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        {{-- FOOTER --}}
        @if(!empty($items) && (is_object($items) && !$items->isEmpty()))
            <div class="p-4 border-t border-base-300 bg-base-200/50">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-gray-500 uppercase text-sm tracking-wider">Total Estimado</span>
                    <span class="text-2xl font-serif font-bold text-primary">${{ number_format($total, 2) }}</span>
                </div>

                <button wire:click="checkout" class="btn btn-primary w-full text-primary-content font-bold tracking-widest"
                    wire:loading.attr="disabled">
                    <x-mary-icon name="o-chat-bubble-left-right" class="w-5 h-5 mr-2" />
                    <span wire:loading.remove target="checkout">FINALIZAR EN WHATSAPP</span>
                    <span wire:loading target="checkout">PROCESANDO...</span>
                </button>
                <p class="text-xs text-center text-gray-500 mt-2">
                    Se guardará tu pedido y serás redirigido.
                </p>
            </div>
        @endif
    </x-mary-drawer>
</div>