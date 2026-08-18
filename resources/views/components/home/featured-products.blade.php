@props(['picks'])

@if($picks && $picks->count() > 0)
<section class="py-32 px-4 lg:px-12 bg-background-card border-t border-border-subtle relative overflow-hidden">
    <div class="section-glow w-[55vw] h-[55vw] top-1/4 left-1/2 -translate-x-1/2 opacity-70"></div>
    <div class="container mx-auto relative z-10">
        <div class="flex flex-col items-center text-center mb-20">
            <h2 data-reveal class="h2-section tracking-[0.2em] mb-4">SELECCIÓN DEL MAESTRO</h2>
            <div class="w-24 h-px bg-primary/50"></div>
            <p data-reveal class="body-text text-text-muted max-w-xl mt-6">
                El maestro elige, marida y cuenta cómo disfrutar cada pieza.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
            @foreach($picks as $pick)
                <div data-reveal-stagger class="group relative flex flex-col">
                    <a href="/products/{{ $pick->product?->slug }}" wire:navigate class="block featured-card border-beam bg-background-main rounded-sm overflow-hidden relative flex flex-col flex-1">
                        <div class="aspect-[4/5] relative overflow-hidden">
                            @if($pick->displayImage())
                                <img src="{{ asset('storage/' . $pick->displayImage()) }}" alt="{{ $pick->displayTitle() }}"
                                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
                            @else
                                <div class="w-full h-full bg-background-card flex items-center justify-center">
                                    <i class="ph ph-image text-4xl text-text-muted" aria-hidden="true"></i>
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                            <div class="absolute inset-3 border border-white/10 pointer-events-none"></div>

                            @if($pick->kicker)
                                <div class="absolute top-4 left-0">
                                    <span class="text-[10px] uppercase tracking-[0.3em] text-primary bg-black/50 backdrop-blur-sm px-4 py-1.5 border-l border-primary">
                                        {{ $pick->kicker }}
                                    </span>
                                </div>
                            @endif

                            <div class="absolute bottom-0 inset-x-0 p-7">
                                <h3 class="h3-product text-2xl mb-3 text-white group-hover:text-primary transition-colors">
                                    {{ $pick->displayTitle() }}
                                </h3>
                                @if($pick->recommendation)
                                    <p class="text-sm text-white/70 leading-relaxed line-clamp-3">
                                        {{ $pick->recommendation }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        @if($pick->product)
                            <div class="p-6 flex items-center justify-between gap-4 mt-auto">
                                <div>
                                    <div class="text-primary font-serif italic tracking-wider text-xl">
                                        ${{ number_format($pick->product->price / 100, 0, ',', '.') }}
                                    </div>
                                    <div class="text-[10px] text-text-muted uppercase tracking-widest mt-1">
                                        / {{ $pick->product->unit_type === 'kg' ? 'kg' : 'unidad' }}
                                    </div>
                                </div>
                                <span class="inline-flex items-center gap-2 text-[11px] uppercase tracking-[0.2em] text-text-muted group-hover:text-primary transition-colors">
                                    Ver producto
                                    <i class="ph ph-arrow-right text-base transition-transform duration-300 group-hover:translate-x-1" aria-hidden="true"></i>
                                </span>
                            </div>
                        @endif
                    </a>
                </div>
            @endforeach
        </div>

        <div class="mt-20 flex justify-center" data-reveal>
            <div data-magnetic>
                <a href="/products" wire:navigate class="btn-magnetic btn-luxury">
                    <span class="btn-text">VER CATÁLOGO COMPLETO</span>
                </a>
            </div>
        </div>
    </div>
</section>
@endif
