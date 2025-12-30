@props(['product', 'loop'])

<div class="grid grid-cols-1 md:grid-cols-2 min-h-[80vh] group bg-[#121212] overflow-hidden">

    {{-- TEXT SECTION --}}
    <div class="
        flex flex-col justify-center px-8 py-20 md:px-16 lg:px-24 relative
        {{ $loop->even ? 'md:order-last' : 'md:order-first' }}
        order-last
    ">

        {{-- Floating Category --}}
        <div
            class="absolute top-10 left-8 md:left-16 lg:left-24 opacity-0 transform -translate-y-4 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-700 delay-100">
            <span
                class="text-[10px] uppercase tracking-[0.3em] text-[#D4AF37]/60 font-serif border-b border-[#D4AF37]/20 pb-1">
                {{ $product->category->name ?? 'Selección' }}
            </span>
        </div>

        <h2 class="h1-hero !text-4xl md:!text-5xl !mb-6 group-hover:text-gold-gradient transition-all duration-500">
            {{ $product->name }}
        </h2>

        <p class="body-text mb-10 max-w-sm text-sm md:text-base opacity-70">
            {{ $product->description ?: 'Pieza de charcutería premium, elaborada con técnicas tradicionales y maduración controlada.' }}
        </p>

        {{-- Technical Details (Reference style) --}}
        <div class="flex flex-col gap-1 mb-12 text-[#A3A3A3]/60 text-[10px] uppercase tracking-[0.2em] font-sans">
            <p>Tiempo de curado / {{ rand(30, 90) }} días</p>
            <p>Origen / Santa Fe, Argentina</p>
            <p>Tripa / Natural</p>
        </div>

        <div class="mt-auto">
            <span class="text-[10px] text-[#D4AF37] uppercase tracking-[0.2em] mb-2 block">Valor por
                {{ $product->unit_type == 'kg' ? 'kg' : 'unidad' }}</span>
            <div class="flex items-end gap-8 border-b border-[#D4AF37]/20 pb-6 w-full max-w-md justify-between">
                <span class="text-3xl font-serif text-[#E5E5E5]">
                    ${{ number_format($product->price / 100, 0, ',', '.') }}
                </span>

                {{-- Clean Controls --}}
                <div class="flex items-center gap-4">
                    @if($product->unit_type === 'kg')
                        <select wire:model="quantities.{{ $product->id }}"
                            class="bg-transparent text-[#E5E5E5] text-xs uppercase border-none focus:ring-0 cursor-pointer">
                            <option value="200" class="bg-[#1E1E1E]">200g</option>
                            <option value="500" class="bg-[#1E1E1E]">500g</option>
                            <option value="1000" class="bg-[#1E1E1E]">1kg</option>
                        </select>
                    @else
                        <input type="number" min="1" max="10" wire:model="quantities.{{ $product->id }}"
                            class="bg-transparent text-[#E5E5E5] w-10 text-center border-none focus:ring-0 font-mono text-sm"
                            placeholder="1">
                    @endif

                    <button wire:click="add({{ $product->id }}, '{{ $product->unit_type }}')"
                        class="text-[#D4AF37] hover:text-white transition-colors text-xs uppercase tracking-[0.2em] font-bold">
                        AGREGAR +
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- IMAGE SECTION --}}
    <div class="
        relative h-[50vh] md:h-full w-full bg-[#050505]
        {{ $loop->even ? 'md:order-first' : 'md:order-last' }}
        order-first
    ">
        {{-- Polaroid Effect if desired or Clean Full Bleed --}}
        <div class="absolute inset-0 p-0 md:p-12 lg:p-20 transition-all duration-700 ease-out">
            <div
                class="w-full h-full relative overflow-hidden shadow-2xl group-hover:shadow-[0_20px_50px_rgba(0,0,0,0.5)] transition-all duration-700 {{ $loop->even ? 'rotate-1 group-hover:rotate-0' : '-rotate-1 group-hover:rotate-0' }}">
                @if($product->image_path)
                    <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}"
                        class="w-full h-full object-cover grayscale-[10%] group-hover:grayscale-0 group-hover:scale-110 transition-all duration-[1.5s]" />
                @else
                    <div class="w-full h-full bg-[#1E1E1E] flex items-center justify-center">
                        <span class="text-9xl font-serif text-white/5">{{ substr($product->name, 0, 1) }}</span>
                    </div>
                @endif

                {{-- Border inner --}}
                <div class="absolute inset-4 border border-white/10 pointer-events-none z-10"></div>
            </div>
        </div>
    </div>

</div>