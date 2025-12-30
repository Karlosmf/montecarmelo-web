@props(['categories'])

<div class="container mx-auto px-6 py-12 flex flex-col md:flex-row justify-between items-end gap-8 relative z-10 mb-12">
    <div class="space-y-2">
        <h1 class="text-5xl lg:text-7xl font-serif font-bold uppercase tracking-widest text-gold-gradient">
            Nuestros Productos
        </h1>
        <p class="text-text-muted font-light tracking-[0.2em] uppercase text-xs">Selección exclusiva de charcutería
            artesanal</p>
    </div>

    <div class="flex flex-col sm:flex-row items-center gap-6 w-full md:w-auto">
        <div class="relative w-full md:w-64 group">
            <input type="text" wire:model.live.debounce="search" placeholder="BUSCAR PRODUCTO..."
                class="input-modern !py-2 !text-xs tracking-[0.2em] group-hover:border-primary/50 transition-all" />
            <x-mary-icon name="o-magnifying-glass" class="absolute right-0 top-2 w-4 h-4 text-primary opacity-50" />
        </div>

        <select wire:model.live="category_filter"
            class="bg-transparent border-0 border-b border-white/20 text-xs uppercase tracking-[0.2em] text-text-muted focus:outline-none focus:border-primary w-full sm:w-auto py-2 cursor-pointer transition-colors">
            <option value="">Todas las Categorías</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" class="bg-[#0a0a0a]">{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
</div>