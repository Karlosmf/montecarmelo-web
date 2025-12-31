<div class="relative w-full h-screen">
    {{-- Background Image --}}
    <div class="absolute inset-0 bg-cover bg-center bg-no-repeat"
        style="background-image: url('{{ asset('storage/products/picada-premium.jpg') }}');">
        <div class="absolute inset-0 bg-black/50 bg-gradient-to-b from-transparent via-black/20 to-background-main">
        </div>
    </div>

    {{-- Content --}}
    <div class="relative z-10 flex flex-col items-center justify-center h-full text-center px-4">
        <h1
            class="font-serif text-5xl md:text-8xl font-bold uppercase tracking-widest text-primary drop-shadow-2xl mb-6">
            Monte Carmelo
        </h1>
        <p class="font-sans text-white/90 uppercase tracking-[0.4em] font-light text-sm md:text-lg">
            Charcuterie & Premium Goods
        </p>

        <div class="mt-16 animate-bounce text-primary/60">
            <x-mary-icon name="o-chevron-down" class="w-10 h-10" />
        </div>
    </div>
</div>