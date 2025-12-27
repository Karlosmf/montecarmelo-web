<?php

use function Livewire\Volt\{state, uses, with, layout};
use App\Models\Product;
use Mary\Traits\Toast;
use Illuminate\Database\Eloquent\Builder;

uses([Toast::class]);
layout('components.layouts.app');

state(['search' => '', 'category_filter' => '', 'price_min' => 0, 'price_max' => 10000000]); 
state(['quantities' => []]);

$add = function ($productId, $unitType) {
    $qty = $this->quantities[$productId] ?? ($unitType === 'kg' ? 200 : 1);
    $product = Product::find($productId);
    $qtyLabel = $unitType === 'kg' ? "{$qty}g" : "{$qty} u.";
    $this->success("{$product->name} ({$qtyLabel}) agregado al pedido.");
};

with(fn () => [
    'products' => Product::query()
        ->when($this->search, fn (Builder $q) => $q->where('name', 'like', "%{$this->search}%"))
        ->when($this->category_filter, fn (Builder $q) => $q->where('category', $this->category_filter))
        ->orderBy('name')
        ->paginate(10), // Adjust pagination for list view
    'categories' => Product::select('category')->distinct()->pluck('category'),
]);

?>

<div class="bg-base-100 min-h-screen">
    
    {{-- HEADER WITH FILTERS TOGGLE (Simplified for Editorial Look) --}}
    <div class="sticky top-0 z-40 bg-base-100/90 backdrop-blur-md border-b border-white/10 py-4 px-6 md:px-12 flex flex-col md:flex-row justify-between items-center gap-4">
        <h1 class="text-3xl font-serif font-bold text-primary tracking-widest">CATÁLOGO</h1>
        
        <div class="flex items-center gap-4 w-full md:w-auto">
             <div class="relative w-full md:w-64">
                <input type="text" wire:model.live.debounce="search" placeholder="Buscar..." class="input input-luxury w-full text-sm pl-0 text-base-content placeholder-gray-500" />
                <x-mary-icon name="o-magnifying-glass" class="absolute right-0 top-3 w-4 h-4 text-primary" />
             </div>
             
             {{-- Simple Category Filter Dropdown for Mobile/Desktop --}}
             <select wire:model.live="category_filter" class="select select-sm select-ghost text-xs uppercase tracking-wide border-b border-primary rounded-none focus:outline-none w-32 md:w-auto">
                <option value="">Todas las Categorías</option>
                @foreach($categories as $category)
                    <option value="{{ $category }}">{{ $category }}</option>
                @endforeach
             </select>
        </div>
    </div>

    {{-- ZIG-ZAG PRODUCTS LIST --}}
    <div class="flex flex-col">
        
        @if($products->isEmpty())
            <div class="min-h-[50vh] flex flex-col items-center justify-center text-center px-4">
                <h3 class="text-2xl font-serif text-gray-500 mb-2">No se encontraron productos</h3>
                <p class="text-gray-600">Intentá con otra búsqueda o categoría.</p>
                <button wire:click="$set('search', '')" class="btn btn-link text-primary mt-4">Limpiar búsqueda</button>
            </div>
        @endif

        @foreach($products as $product)
            {{-- 
                ROW CONTAINER 
                - Mobile: Flex Col (Image first, then text)
                - Desktop: Grid 2 cols
                - Min Height to ensure presence
            --}}
            <div class="grid grid-cols-1 md:grid-cols-2 min-h-[60vh] md:h-[70vh] border-b border-white/5 group">
                
                {{-- TEXT SECTION --}}
                <div class="
                    flex flex-col justify-center px-8 py-12 md:px-16 lg:px-24 bg-base-100 relative
                    {{ $loop->even ? 'md:order-last' : 'md:order-first' }}
                    order-last {{-- Mobile always text last (below image) --}}
                ">
                     {{-- Decorative number --}}
                    <span class="absolute top-8 left-8 text-6xl font-serif font-bold text-white/5 select-none pointer-events-none">
                        {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                    </span>

                    <span class="text-xs font-bold tracking-[0.2em] text-secondary uppercase mb-4 block">
                        {{ $product->category }}
                    </span>
                    
                    <h2 class="text-4xl lg:text-5xl font-serif font-bold text-primary tracking-wide mb-6 leading-tight">
                        {{ strtoupper($product->name) }}
                    </h2>
                    
                    <p class="text-gray-400 text-lg font-light leading-relaxed mb-8 max-w-md">
                        {{ $product->description ?: 'Producto artesanal de calidad premium, elaborado con las mejores materias primas seleccionadas.' }}
                    </p>

                    {{-- Technical Details (Mocked for style if not in DB) --}}
                    <ul class="space-y-2 mb-8 text-sm text-gray-500 font-mono">
                         <li class="flex items-center gap-3">
                            <span class="w-1.5 h-1.5 bg-primary rounded-full"></span>
                            <span>Precio por {{ $product->unit_type == 'kg' ? 'Kilo' : 'Unidad' }}</span>
                        </li>
                         <li class="flex items-center gap-3">
                            <span class="w-1.5 h-1.5 bg-primary rounded-full"></span>
                            <span>Calidad Premium</span>
                        </li>
                    </ul>
                    
                    <div class="flex items-center gap-8 mt-auto md:mt-0">
                        <span class="text-3xl font-serif text-white">
                            ${{ number_format($product->price / 100, 0, ',', '.') }}
                        </span>
                        
                        <div class="flex items-center gap-4">
                             @if($product->unit_type === 'kg')
                                <select wire:model="quantities.{{ $product->id }}" class="select select-xs select-bordered bg-transparent border-gray-700 text-gray-300 rounded-none focus:outline-none focus:border-primary w-24">
                                    <option value="200">200g</option>
                                    <option value="500">500g</option>
                                    <option value="1000">1kg</option>
                                </select>
                            @else
                                <input type="number" min="1" max="10" wire:model="quantities.{{ $product->id }}" placeholder="1" class="input input-xs input-bordered bg-transparent border-gray-700 text-gray-300 rounded-none focus:outline-none focus:border-primary w-16 text-center" />
                            @endif

                            <button wire:click="add({{ $product->id }}, '{{ $product->unit_type }}')" class="btn btn-outline border-primary text-primary hover:bg-primary hover:text-base-100 btn-sm rounded-none px-6 font-serif tracking-wider">
                                AGREGAR
                            </button>
                        </div>
                    </div>
                </div>

                {{-- IMAGE SECTION --}}
                <div class="
                    relative overflow-hidden h-[50vh] md:h-full w-full bg-neutral
                    {{ $loop->even ? 'md:order-first' : 'md:order-last' }}
                    order-first {{-- Mobile always image first --}}
                ">
                     @if($product->image_path)
                         <img src="{{ asset('storage/' . $product->image_path) }}" 
                              alt="{{ $product->name }}" 
                              class="w-full h-full object-cover transition-transform duration-[2s] ease-in-out group-hover:scale-110 grayscale-[30%] group-hover:grayscale-0"
                              onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';" 
                         />
                         {{-- Fallback --}}
                         <div class="hidden w-full h-full flex items-center justify-center bg-base-200">
                             <span class="text-9xl font-serif text-white/5">{{ substr($product->name, 0, 1) }}</span>
                         </div>
                     @else
                        {{-- Fallback --}}
                         <div class="w-full h-full flex items-center justify-center bg-base-200">
                             <span class="text-9xl font-serif text-white/5">{{ substr($product->name, 0, 1) }}</span>
                         </div>
                     @endif
                     
                     {{-- Overlay Gradient --}}
                     <div class="absolute inset-0 bg-gradient-to-t from-base-100 via-transparent to-transparent opacity-80 md:opacity-40"></div>
                </div>

            </div>
        @endforeach

        {{-- PAGINATION --}}
        <div class="py-12 px-6 flex justify-center">
            {{ $products->links() }}
        </div>
    </div>
</div>