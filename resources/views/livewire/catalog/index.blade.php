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

<div class="bg-background-main min-h-screen pt-20">
    
    {{-- HEADER WITH FILTERS --}}
    <div class="container mx-auto px-6 py-12 flex flex-col md:flex-row justify-between items-end gap-8 border-b border-primary/10 mb-12">
        <div>
            <h1 class="h2-section mb-2">Nuestros Productos</h1>
            <p class="text-text-muted font-light">Selección exclusiva de charcutería artesanal.</p>
        </div>
        
        <div class="flex items-center gap-8 w-full md:w-auto">
             <div class="relative w-full md:w-64">
                <input type="text" wire:model.live.debounce="search" placeholder="BUSCAR..." class="input-gold text-sm py-2 uppercase tracking-widest placeholder-text-muted" />
                <x-mary-icon name="o-magnifying-glass" class="absolute right-0 top-2 w-5 h-5 text-primary" />
             </div>
             
             <select wire:model.live="category_filter" class="select select-sm select-ghost text-xs uppercase tracking-wide border-b border-primary text-primary rounded-none focus:outline-none w-40 md:w-auto p-0 h-auto py-2">
                <option value="">Todas las Categorías</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
             </select>
        </div>
    </div>

    {{-- ZIG-ZAG PRODUCTS LIST --}}
    <div class="flex flex-col w-full">
        
        @if($products->isEmpty())
            <div class="min-h-[40vh] flex flex-col items-center justify-center text-center px-4">
                <h3 class="h3-product text-text-muted mb-4">No se encontraron productos</h3>
                <button wire:click="$set('search', '')" class="btn-primary-outline">Ver Todo</button>
            </div>
        @endif

        @foreach($products as $product)
            {{-- ROW CONTAINER --}}
            <div class="grid grid-cols-1 md:grid-cols-2 min-h-[60vh] md:h-[80vh] border-b border-white/5 group bg-background-card">
                
                {{-- TEXT SECTION --}}
                <div class="
                    flex flex-col justify-center px-8 py-16 md:px-16 lg:px-24 relative
                    {{ $loop->even ? 'md:order-last' : 'md:order-first' }}
                    order-last
                ">
                    <span class="text-xs font-bold tracking-[0.3em] text-text-muted uppercase mb-6 block border-l-2 border-primary pl-3">
                        {{ $product->category->name ?? 'Sin Categoría' }}
                    </span>
                    
                    <h2 class="font-serif text-4xl lg:text-5xl font-bold text-primary tracking-wide mb-6 leading-tight uppercase">
                        {{ $product->name }}
                    </h2>
                    
                    <p class="body-text mb-10 max-w-md">
                        {{ $product->description ?: 'Producto artesanal de calidad premium, elaborado con las mejores materias primas seleccionadas para garantizar un sabor inigualable.' }}
                    </p>

                    {{-- Technical Details --}}
                    <div class="mb-12 border-t border-white/10 pt-6">
                        <ul class="space-y-3 text-sm text-text-muted font-mono uppercase tracking-wider">
                             <li class="flex items-center gap-4">
                                <span class="w-2 h-2 border border-primary rounded-full"></span>
                                <span>Precio por {{ $product->unit_type == 'kg' ? 'Kilo' : 'Unidad' }}</span>
                            </li>
                             <li class="flex items-center gap-4">
                                <span class="w-2 h-2 border border-primary rounded-full"></span>
                                <span>Curado Natural</span>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-8 mt-auto">
                        <span class="text-4xl font-serif text-white">
                            ${{ number_format($product->price / 100, 0, ',', '.') }}
                        </span>
                        
                        <div class="flex items-center gap-6 w-full sm:w-auto">
                             @if($product->unit_type === 'kg')
                                <select wire:model="quantities.{{ $product->id }}" class="select select-sm bg-transparent border-0 border-b border-white/20 text-text-main rounded-none focus:outline-none focus:border-primary w-full sm:w-32">
                                    <option value="200">200g</option>
                                    <option value="500">500g</option>
                                    <option value="1000">1kg</option>
                                </select>
                            @else
                                <div class="flex items-center border-b border-white/20">
                                    <span class="text-text-muted text-xs mr-2">CANT.</span>
                                    <input type="number" min="1" max="10" wire:model="quantities.{{ $product->id }}" placeholder="1" class="input input-sm bg-transparent border-0 text-text-main focus:outline-none text-center w-16" />
                                </div>
                            @endif

                            <button wire:click="add({{ $product->id }}, '{{ $product->unit_type }}')" class="btn-primary-outline px-6 py-2 text-xs">
                                Agregar
                            </button>
                        </div>
                    </div>
                </div>

                {{-- IMAGE SECTION --}}
                <div class="
                    relative overflow-hidden h-[50vh] md:h-full w-full bg-[#151515]
                    {{ $loop->even ? 'md:order-first' : 'md:order-last' }}
                    order-first
                ">
                     @if($product->image_path)
                         <img src="{{ asset('storage/' . $product->image_path) }}" 
                              alt="{{ $product->name }}" 
                              class="img-product-clean"
                              onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';" 
                         />
                         {{-- Fallback --}}
                         <div class="hidden w-full h-full flex items-center justify-center">
                             <span class="text-9xl font-serif text-white/5">{{ substr($product->name, 0, 1) }}</span>
                         </div>
                     @else
                        {{-- Fallback --}}
                         <div class="w-full h-full flex items-center justify-center">
                             <span class="text-9xl font-serif text-white/5">{{ substr($product->name, 0, 1) }}</span>
                         </div>
                     @endif
                </div>

            </div>
        @endforeach

        {{-- PAGINATION --}}
        <div class="py-16 px-6 flex justify-center border-t border-primary/10">
            {{ $products->links() }}
        </div>
    </div>
</div>