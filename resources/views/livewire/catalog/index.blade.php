<?php

use function Livewire\Volt\{state, uses, with, layout};
use App\Models\Product;
use App\Models\Category;
use Mary\Traits\Toast;
use Illuminate\Database\Eloquent\Builder;
use App\Facades\Cart;

uses([Toast::class]);
layout('components.layouts.app');

state(['search' => '', 'category_filter' => '', 'price_min' => 0, 'price_max' => 10000000]); 
state(['quantities' => []]);

$add = function ($productId, $unitType) {
    // Determine quantity: use selected/input value or defaults (200g for kg, 1 for unit)
    $qty = (int) ($this->quantities[$productId] ?? ($unitType === 'kg' ? 200 : 1));
    
    // Ensure at least 1 for unit types if input was cleared/empty
    if ($unitType !== 'kg' && $qty < 1) {
        $qty = 1;
    }

    Cart::add($productId, $qty, $unitType);
    
    $this->dispatch('cart-updated'); // Update Drawer & Navbar Badge

    $product = Product::find($productId);
    $qtyLabel = $unitType === 'kg' ? "{$qty}g" : "{$qty} u.";
    $this->success("{$product->name} ({$qtyLabel}) agregado al pedido.");
};

with(fn () => [
    'products' => Product::query()
        ->with('category') // Eager load category
        ->when($this->search, fn (Builder $q) => $q->where('name', 'like', "%{$this->search}%"))
        ->when($this->category_filter, fn (Builder $q) => $q->where('category_id', $this->category_filter))
        ->orderBy('name')
        ->paginate(10),
    'categories' => Category::orderBy('name')->get(), // Fetch actual categories
]);

?>

<div class="min-h-screen pt-24 relative">
    
    {{-- HEADER WITH FILTERS --}}
    <div class="container mx-auto px-6 py-12 flex flex-col md:flex-row justify-between items-end gap-8 relative z-10 mb-12">
        <div class="space-y-2">
            <h1 class="text-5xl lg:text-7xl font-serif font-bold uppercase tracking-widest text-gold-gradient">
                Nuestros Productos
            </h1>
            <p class="text-text-muted font-light tracking-[0.2em] uppercase text-xs">Selección exclusiva de charcutería artesanal</p>
        </div>
        
        <div class="flex flex-col sm:flex-row items-center gap-6 w-full md:w-auto">
             <div class="relative w-full md:w-64 group">
                <input type="text" wire:model.live.debounce="search" placeholder="BUSCAR PRODUCTO..." class="input-modern !py-2 !text-xs tracking-[0.2em] group-hover:border-primary/50 transition-all" />
                <x-mary-icon name="o-magnifying-glass" class="absolute right-0 top-2 w-4 h-4 text-primary opacity-50" />
             </div>
             
             <select wire:model.live="category_filter" class="bg-transparent border-0 border-b border-white/20 text-xs uppercase tracking-[0.2em] text-text-muted focus:outline-none focus:border-primary w-full sm:w-auto py-2 cursor-pointer transition-colors">
                <option value="">Todas las Categorías</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" class="bg-[#0a0a0a]">{{ $category->name }}</option>
                @endforeach
             </select>
        </div>
    </div>

    {{-- ZIG-ZAG PRODUCTS LIST --}}
    <div class="flex flex-col w-full relative z-10">
        
        @if($products->isEmpty())
            <div class="min-h-[40vh] flex flex-col items-center justify-center text-center px-4">
                <h3 class="font-serif text-2xl text-text-muted mb-6 uppercase tracking-widest">No se encontraron piezas únicas</h3>
                <button wire:click="$set('search', '')" class="btn-luxury">Ver Catálogo Completo</button>
            </div>
        @endif

        @foreach($products as $product)
            {{-- ROW CONTAINER --}}
            <div class="grid grid-cols-1 md:grid-cols-2 min-h-[70vh] group">
                
                {{-- TEXT SECTION --}}
                <div class="
                    flex flex-col justify-center px-8 py-20 md:px-16 lg:px-24 relative
                    {{ $loop->even ? 'md:order-last' : 'md:order-first' }}
                    order-last
                ">
                    {{-- Glass Background for Content --}}
                    <div class="absolute inset-0 glass-panel opacity-40 -z-10 group-hover:opacity-60 transition-opacity duration-700"></div>

                    <span class="text-[10px] font-bold tracking-[0.5em] text-primary uppercase mb-8 block">
                        {{ $product->category->name ?? 'Colección Privada' }}
                    </span>
                    
                    <h2 class="font-serif text-4xl lg:text-6xl font-bold text-white tracking-wide mb-8 leading-tight uppercase group-hover:text-gold-gradient transition-all duration-500">
                        {{ $product->name }}
                    </h2>
                    
                    <p class="body-text mb-12 max-w-md opacity-80 leading-relaxed font-light">
                        {{ $product->description ?: 'Producto artesanal de maduración prolongada, pieza fundamental de nuestra cava premium.' }}
                    </p>

                    {{-- Features --}}
                    <div class="mb-12 space-y-4">
                        <div class="flex items-center gap-4 group/item">
                            <div class="w-8 h-[1px] bg-primary/30 group-hover/item:w-12 transition-all duration-500"></div>
                            <span class="text-[10px] text-text-muted uppercase tracking-[0.3em]">Curado artesanal en cava</span>
                        </div>
                        <div class="flex items-center gap-4 group/item">
                            <div class="w-8 h-[1px] bg-primary/30 group-hover/item:w-12 transition-all duration-500"></div>
                            <span class="text-[10px] text-text-muted uppercase tracking-[0.3em]">Materia prima seleccionada</span>
                        </div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-10 mt-auto">
                        <div class="flex flex-col">
                            <span class="text-[10px] text-primary uppercase tracking-[0.2em] mb-1">Inversión</span>
                            <span class="text-4xl font-serif text-white group-hover:scale-105 transition-transform origin-left">
                                ${{ number_format($product->price / 100, 0, ',', '.') }}
                            </span>
                        </div>
                        
                        <div class="flex items-center gap-6 w-full sm:w-auto">
                             @if($product->unit_type === 'kg')
                                <select wire:model="quantities.{{ $product->id }}" class="bg-transparent border-0 border-b border-white/20 text-xs uppercase tracking-widest text-text-main rounded-none focus:outline-none focus:border-primary w-full sm:w-24 py-2 cursor-pointer">
                                    <option value="200" class="bg-[#0a0a0a]">200g</option>
                                    <option value="500" class="bg-[#0a0a0a]">500g</option>
                                    <option value="1000" class="bg-[#0a0a0a]">1kg</option>
                                </select>
                            @else
                                <div class="flex items-center border-b border-white/20 py-1">
                                    <span class="text-text-muted text-[10px] tracking-widest mr-3">CANT.</span>
                                    <input type="number" min="1" max="10" wire:model="quantities.{{ $product->id }}" placeholder="1" class="bg-transparent border-0 text-white focus:outline-none text-center w-12 font-mono" />
                                </div>
                            @endif

                            <button wire:click="add({{ $product->id }}, '{{ $product->unit_type }}')" class="btn-luxury !px-10">
                                AGREGAR
                            </button>
                        </div>
                    </div>
                </div>

                {{-- IMAGE SECTION --}}
                <div class="
                    relative overflow-hidden h-[60vh] md:h-full w-full bg-[#050505]
                    {{ $loop->even ? 'md:order-first' : 'md:order-last' }}
                    order-first
                ">
                     @if($product->image_path)
                         <img src="{{ asset('storage/' . $product->image_path) }}" 
                              alt="{{ $product->name }}" 
                              class="w-full h-full object-cover grayscale-[30%] group-hover:grayscale-0 group-hover:scale-105 transition-all duration-1000 ease-out shadow-inner"
                              onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';" 
                         />
                         {{-- Fallback --}}
                         <div class="hidden w-full h-full flex items-center justify-center">
                             <span class="text-9xl font-serif text-white/5">{{ substr($product->name, 0, 1) }}</span>
                         </div>
                     @else
                        {{-- Fallback --}}
                         <div class="w-full h-full flex items-center justify-center">
                             <span class="text-9xl font-serif text-white/5 opacity-10">{{ substr($product->name, 0, 1) }}</span>
                         </div>
                     @endif

                     {{-- Overlay Vignette --}}
                     <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-60"></div>
                </div>

            </div>
        @endforeach

        {{-- PAGINATION --}}
        <div class="py-24 px-6 flex justify-center">
            {{ $products->links() }}
        </div>
    </div>
</div>

        {{-- PAGINATION --}}
        <div class="py-16 px-6 flex justify-center border-t border-primary/10">
            {{ $products->links() }}
        </div>
    </div>
</div>