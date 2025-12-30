<section id="story" class="py-32 bg-[#151515] relative overflow-hidden">

    {{-- Sketch Illustration (Right Top) --}}
    <div class="absolute -right-10 top-10 w-64 opacity-10 pointer-events-none">
        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <path fill="#D4AF37"
                d="M45.7,-76.3C58.9,-69.3,69.1,-55.6,76.3,-41.2C83.5,-26.8,87.7,-11.7,85.2,2.5C82.7,16.7,73.5,30,63.1,41.3C52.7,52.6,41.1,61.9,28.6,68.6C16.1,75.3,2.7,79.4,-9.9,77.8C-22.5,76.2,-34.3,68.9,-45.3,60.2C-56.3,51.5,-66.5,41.4,-73.4,29.3C-80.3,17.2,-83.9,3.1,-80.7,-9.6C-77.5,-22.3,-67.5,-33.6,-56.3,-42.2C-45.1,-50.8,-32.7,-56.7,-20.5,-64.3C-8.3,-71.9,3.7,-81.2,16.8,-83.5C29.9,-85.8,44,-81.1,45.7,-76.3Z"
                transform="translate(100 100)" />
        </svg>
    </div>

    <div class="container mx-auto flex flex-col lg:flex-row items-center gap-16 lg:gap-32 px-6">

        {{-- Left: Polaroid Stack --}}
        <div class="relative w-64 h-80 mx-auto lg:mx-0 flex-shrink-0">
            {{-- Card 1 (Rotated Left) --}}
            <div class="polaroid-base rotate-[-6deg] z-10 absolute inset-0">
                <img src="https://images.unsplash.com/photo-1625938145744-e38051524225?q=80&w=400&h=500&fit=crop"
                    class="w-full h-full object-cover grayscale-[30%]" alt="Tradición">
            </div>
            {{-- Card 2 (Rotated Right) --}}
            <div class="polaroid-base rotate-[3deg] z-20 absolute inset-0 translate-x-4 translate-y-4">
                <img src="https://images.unsplash.com/photo-1596560548464-f010549b84d7?q=80&w=400&h=500&fit=crop"
                    class="w-full h-full object-cover grayscale-[30%]" alt="Calidad">
            </div>
        </div>

        {{-- Right: Text & Quote --}}
        <div class="flex flex-col items-center lg:items-start text-center lg:text-left z-30">
            <div class="mb-10 max-w-lg">
                <p class="font-serif italic text-2xl md:text-3xl text-[#E5E5E5] leading-relaxed text-center">
                    "El destino nos marcó el camino, pero la pasión nos mantuvo en él."
                </p>
            </div>

            <div class="w-full flex justify-center lg:justify-center">
                <a href="/products" wire:navigate
                    class="btn btn-outline border-[#D4AF37] text-[#D4AF37] hover:bg-[#D4AF37] hover:text-[#121212] rounded-full uppercase tracking-widest px-10 py-3 font-serif text-sm">
                    NUESTROS PRODUCTOS
                </a>
            </div>
        </div>
    </div>
</section>