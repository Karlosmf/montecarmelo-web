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
    }Cart::add($productId, $qty, $unitType);
    $this->dispatch('cart-updated');
    // Update Drawer & Navbar Badge

    $product = Product::find($productId);
    $qtyLabel = $unitType === 'kg' ? "{$qty}g" : "{$qty} u.";
    $this->success("{$product->name} ({$qtyLabel}) agregado al pedido.");
};

with(fn() => [
    'products' => Product::query()
        ->with('category') // Eager load category
        ->when($this->search, fn(Builder $q) => $q->where('name', 'like', "%{$this->search}%"))
        ->when($this->category_filter, fn(Builder $q) => $q->where('category_id', $this->category_filter))
        ->orderBy('name')
        ->paginate(10),
    'categories' => Category::orderBy('name')->get(), // Fetch actual categories
]);

    ?>

@section('title', 'Catálogo de Productos')
@section('meta_description', 'Explora nuestra colección exclusiva de charcutería, quesos y vinos premium.')


<div class="min-h-screen pt-24 relative">

    {{-- HEADER WITH FILTERS --}}
    <x-catalog.header :categories="$categories" />

    {{-- ZIG-ZAG PRODUCTS LIST --}}
    <div class="flex flex-col w-full relative z-10">

        @if($products->isEmpty())
            <div class="min-h-[40vh] flex flex-col items-center justify-center text-center px-4">
                <h3 class="font-serif text-2xl text-text-muted mb-6 uppercase tracking-widest">No se encontraron piezas
                    únicas</h3>
                <button wire:click="$set('search', '')" class="btn-luxury">Ver Catálogo Completo</button>
            </div>
        @endif

        @foreach($products as $product)
            {{-- ROW CONTAINER --}}
            <x-catalog.product-item :product="$product" :loop="$loop" wire:key="{{ $product->id }}" />
        @endforeach

        {{-- PAGINATION --}}
        <div class="py-16 px-6 flex justify-center border-t border-primary/10">
            {{ $products->links() }}
        </div>
    </div>
</div>