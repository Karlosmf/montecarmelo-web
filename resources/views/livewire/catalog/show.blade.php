<?php

use function Livewire\Volt\{state, mount, layout};
use App\Models\Product;
use App\Facades\Cart;

layout('components.layouts.app');

state(['product', 'relatedProducts', 'quantity' => null]);

mount(function (string $slug) {
    $this->product = Product::where('slug', $slug)->firstOrFail();
    
    // Set default quantity
    $this->quantity = $this->product->unit_type === 'kg' ? 200 : 1;

    // Load related products (same category first, fill with picks)
    $related = Product::where('category_id', $this->product->category_id)
        ->where('id', '!=', $this->product->id)
        ->inRandomOrder()
        ->get();

    $extra = Product::whereNotIn('id', $related->pluck('id')->push($this->product->id))
        ->inRandomOrder()
        ->take(max(0, 12 - $related->count()))
        ->get();

    $this->relatedProducts = $related->concat($extra)->take(12);
});

$add = function () {
    $qty = (int) $this->quantity;
    if ($this->product->unit_type !== 'kg' && $qty < 1) $qty = 1;
    
    Cart::add($this->product->id, $qty, $this->product->unit_type);
    $this->dispatch('cart-updated');
    
    $qtyLabel = $this->product->unit_type === 'kg' ? "{$qty}g" : "{$qty} u.";
    $this->dispatch('toast', message: "{$this->product->name} ({$qtyLabel}) agregado al pedido.", type: 'success');
};

$incrementQty = function () {
    if ($this->product->unit_type === 'kg') {
        if ($this->quantity < 1000) $this->quantity += 100;
    } else {
        $this->quantity++;
    }
};

$decrementQty = function () {
    if ($this->product->unit_type === 'kg') {
        if ($this->quantity > 100) $this->quantity -= 100;
    } else {
        if ($this->quantity > 1) $this->quantity--;
    }
};
?>

@section('title', $product->name . ' - Catálogo')
@section('meta_description', $product->description)

<div class="min-h-screen pt-24 pb-20 overflow-x-hidden">
    <div class="container mx-auto px-4 lg:px-8">
        
        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 text-xs uppercase tracking-widest text-text-muted mb-10 font-serif" data-reveal>
            <a href="/" wire:navigate class="hover:text-primary transition-colors">Inicio</a>
            <span>/</span>
            <a href="/products" wire:navigate class="hover:text-primary transition-colors">Catálogo</a>
            <span>/</span>
            <span class="text-primary">{{ $product->name }}</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-20">
            
            {{-- Image Left --}}
            <div class="lg:col-span-7 relative" data-reveal>
                <div class="img-halo w-[520px] h-[520px] top-1/3 left-1/2 -translate-x-1/2 opacity-60"></div>
                <div class="aspect-square lg:aspect-auto lg:h-[80vh] w-full bg-background-card rounded-[2px] overflow-hidden shadow-2xl relative">
                    @if($product->image_path)
                        <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}"
                            class="w-full h-full object-cover" 
                            style="view-transition-name: product-hero-{{ $product->id }}" />
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <span class="text-9xl font-serif text-white/5">{{ substr($product->name, 0, 1) }}</span>
                        </div>
                    @endif
                    <div class="absolute inset-4 border border-white/10 pointer-events-none z-10"></div>
                </div>
            </div>

            {{-- Product Info Right --}}
            <div class="lg:col-span-5 flex flex-col justify-center bg-background-card border border-border-subtle rounded-[2px] p-8 lg:p-12" data-reveal>
                
                @if($product->category)
                    <div class="mb-4">
                        <span class="text-xs uppercase tracking-[0.3em] text-primary/80 font-serif border-b border-primary/30 pb-1">
                            {{ $product->category->name }}
                        </span>
                    </div>
                @endif
                
                <h1 class="h1-hero text-3xl md:text-5xl !mb-6">{{ $product->name }}</h1>
                
                <p class="body-text text-lg text-text-muted leading-relaxed mb-10">
                    {{ $product->description }}
                </p>

                {{-- Specs --}}
                <div class="grid grid-cols-2 gap-y-4 mb-10 pb-10 border-b border-border-subtle">
                    <div class="flex flex-col">
                        <span class="text-[10px] text-text-placeholder uppercase tracking-widest mb-1">Origen</span>
                        <span class="text-sm text-text-main font-serif">{{ $product->origin ?? 'Santa Fe, Argentina' }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] text-text-placeholder uppercase tracking-widest mb-1">Curación</span>
                        <span class="text-sm text-text-main font-serif">{{ $product->curing_days ? $product->curing_days . ' Días' : 'Acabado artesanal' }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] text-text-placeholder uppercase tracking-widest mb-1">Presentación</span>
                        <span class="text-sm text-text-main font-serif">{{ $product->format ?? ($product->unit_type === 'kg' ? 'Al corte / Trozado' : 'Unidad entera') }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] text-text-placeholder uppercase tracking-widest mb-1">Disponibilidad</span>
                        <span class="text-sm text-primary font-serif">Stock permanente</span>
                    </div>
                </div>

                {{-- Price & Add to Cart --}}
                <div class="space-y-8">
                    <div>
                        <span class="text-[10px] text-primary uppercase tracking-[0.2em] mb-2 block">Valor por {{ $product->unit_type == 'kg' ? 'kg' : 'unidad' }}</span>
                        <span class="text-4xl font-serif text-text-main">
                            ${{ number_format($product->price / 100, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4">
                        {{-- Quantity Selector --}}
                        <div class="flex items-center justify-between border border-border-subtle rounded-[2px] p-2 w-full sm:w-48 bg-background-main h-14">
                            <button wire:click="decrementQty" class="w-10 h-10 flex items-center justify-center text-text-muted hover:text-primary transition-colors">
                                <x-mary-icon name="o-minus" class="w-4 h-4" />
                            </button>
                            
                            <div class="flex flex-col items-center">
                                <span class="text-lg font-serif text-text-main leading-none">{{ $quantity }}</span>
                                <span class="text-[10px] text-text-muted uppercase tracking-widest leading-none mt-1">{{ $product->unit_type === 'kg' ? 'gramos' : 'unidades' }}</span>
                            </div>
                            
                            <button wire:click="incrementQty" class="w-10 h-10 flex items-center justify-center text-text-muted hover:text-primary transition-colors">
                                <x-mary-icon name="o-plus" class="w-4 h-4" />
                            </button>
                        </div>
                        
                        <button wire:click="add" class="btn-luxury h-14 flex-1 flex justify-center items-center gap-2">
                            <x-mary-icon name="o-shopping-bag" class="w-5 h-5" />
                            AGREGAR AL PEDIDO
                        </button>
                    </div>
                    
                    {{-- WhatsApp CTA --}}
                    @php
                        $msg = urlencode("Hola Monte Carmelo, quisiera consultar por el producto: {$product->name}");
                        $whatsappUrl = "https://wa.me/" . preg_replace('/[^0-9]/', '', \App\Support\Settings::get('contact.phone', '5493482535220')) . "?text={$msg}";
                    @endphp
                    <a href="{{ $whatsappUrl }}" target="_blank" class="flex items-center justify-center gap-3 text-sm text-text-muted hover:text-[#25D366] transition-colors border border-border-subtle h-14 w-full rounded-[2px]">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/></svg>
                        Consultar por WhatsApp
                    </a>
                </div>

            </div>
        </div>

        {{-- Related Products — Marquee infinito --}}
        @if($relatedProducts->count() > 0)
            <div class="mt-32 pt-20 border-t border-border-subtle" data-reveal>
                <div class="flex items-center justify-between mb-14 gap-6">
                    <h3 class="h2-section tracking-[0.2em] text-2xl">MÁS DE ESTA SELECCIÓN</h3>
                    <a href="/products" wire:navigate class="reversed-link text-xs uppercase tracking-widest text-text-muted hover:text-primary whitespace-nowrap">
                        Ver catálogo completo
                    </a>
                </div>

                <div class="marquee" data-marquee>
                    <div class="marquee-track">

                        @foreach($relatedProducts as $product)
                            <a href="/products/{{ $product->slug }}" wire:navigate class="group block w-64 md:w-72 shrink-0" data-marquee-item>
                                <div class="featured-card border-beam bg-background-card rounded-sm overflow-hidden relative">
                                    <div class="aspect-[4/5] relative overflow-hidden">
                                        @if($product->image_path)
                                            <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}"
                                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" loading="lazy" />
                                        @else
                                            <div class="w-full h-full bg-background-card flex items-center justify-center">
                                                <i class="ph ph-image text-3xl text-text-muted" aria-hidden="true"></i>
                                            </div>
                                        @endif
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent"></div>
                                        <div class="absolute inset-3 border border-white/10 pointer-events-none"></div>
                                        <div class="absolute bottom-0 inset-x-0 p-6">
                                            <span class="text-[10px] uppercase tracking-[0.3em] text-primary/90 font-serif">
                                                {{ $product->category->name ?? 'Selección' }}
                                            </span>
                                            <h4 class="h3-product text-lg mt-1 mb-0.5 text-white group-hover:text-primary transition-colors">{{ $product->name }}</h4>
                                            <div class="text-primary font-serif italic tracking-wider text-lg">
                                                ${{ number_format($product->price / 100, 0, ',', '.') }}
                                                <span class="text-[10px] text-white/60 uppercase tracking-widest not-italic font-sans">
                                                    / {{ $product->unit_type === 'kg' ? 'kg' : 'un' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach

                        {{-- Duplicado para loop seamless --}}
                        @foreach($relatedProducts as $product)
                            <a href="/products/{{ $product->slug }}" wire:navigate aria-hidden="true" tabindex="-1" class="group block w-64 md:w-72 shrink-0" data-marquee-item>
                                <div class="featured-card border-beam bg-background-card rounded-sm overflow-hidden relative">
                                    <div class="aspect-[4/5] relative overflow-hidden">
                                        @if($product->image_path)
                                            <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}"
                                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" loading="lazy" />
                                        @else
                                            <div class="w-full h-full bg-background-card flex items-center justify-center">
                                                <i class="ph ph-image text-3xl text-text-muted" aria-hidden="true"></i>
                                            </div>
                                        @endif
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent"></div>
                                        <div class="absolute inset-3 border border-white/10 pointer-events-none"></div>
                                        <div class="absolute bottom-0 inset-x-0 p-6">
                                            <span class="text-[10px] uppercase tracking-[0.3em] text-primary/90 font-serif">
                                                {{ $product->category->name ?? 'Selección' }}
                                            </span>
                                            <h4 class="h3-product text-lg mt-1 mb-0.5 text-white group-hover:text-primary transition-colors">{{ $product->name }}</h4>
                                            <div class="text-primary font-serif italic tracking-wider text-lg">
                                                ${{ number_format($product->price / 100, 0, ',', '.') }}
                                                <span class="text-[10px] text-white/60 uppercase tracking-widest not-italic font-sans">
                                                    / {{ $product->unit_type === 'kg' ? 'kg' : 'un' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach

                    </div>
                </div>
            </div>
        @endif
        
    </div>
</div>
