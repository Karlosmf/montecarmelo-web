@props(['slides'])

@php
    $hasSlides = $slides && $slides->count() > 0;
@endphp

@if($hasSlides)
    <div x-data="{ 
                active: 0, 
                count: {{ $slides->count() }},
                timer: null,
                start() {
                    this.timer = setInterval(() => {
                        this.next();
                    }, 6000);
                },
                stop() {
                    clearInterval(this.timer);
                },
                next() {
                    this.active = (this.active + 1) % this.count;
                },
                goTo(index) {
                    this.active = index;
                    this.stop();
                    this.start();
                }
            }" x-init="start()" @mouseenter="stop()" @mouseleave="start()"
        class="relative w-full h-screen overflow-hidden group">

        @foreach($slides as $index => $slide)
            <div x-show="active === {{ $index }}" x-transition:enter="transition ease-out duration-1000"
                x-transition:enter-start="opacity-0 scale-105" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-1000" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-100" class="absolute inset-0 w-full h-full" style="display: none;">
                {{-- Background Image --}}
                <div class="absolute inset-0 bg-cover bg-center"
                    style="background-image: url('{{ asset('storage/' . $slide->image_path) }}');">
                    <div class="absolute inset-0 bg-black/40 bg-gradient-to-b from-transparent via-black/20 to-background-main">
                    </div>
                </div>

                {{-- Content --}}
                <div class="relative z-10 flex flex-col items-center justify-center h-full text-center px-4 max-w-5xl mx-auto">
                    <h1
                        class="font-serif text-5xl md:text-7xl lg:text-8xl font-bold uppercase tracking-wider text-primary drop-shadow-2xl mb-6">
                        {{ $slide->title }}
                    </h1>
                    <p
                        class="font-sans text-white/90 font-light text-lg md:text-xl lg:text-2xl max-w-2xl leading-relaxed mb-10 tracking-wide">
                        {{ $slide->description }}
                    </p>

                    @if($slide->button_text)
                        <a href="{{ $slide->button_url ?? '#' }}"
                            class="inline-block border border-primary text-primary hover:bg-primary hover:text-black px-8 py-3 uppercase tracking-[0.2em] font-bold text-sm transition-all duration-300">
                            {{ $slide->button_text }}
                        </a>
                    @endif
                </div>
            </div>
        @endforeach

        {{-- Controls/Dots --}}
        <div class="absolute bottom-12 left-0 right-0 z-20 flex justify-center gap-4">
            @foreach($slides as $index => $slide)
                <button @click="goTo({{ $index }})"
                    :class="active === {{ $index }} ? 'bg-primary w-12 opacity-100' : 'bg-white w-3 opacity-30 hover:opacity-60'"
                    class="h-1 rounded-full transition-all duration-500"></button>
            @endforeach
        </div>

        {{-- Arrows --}}
        <button @click="active = (active - 1 + count) % count; stop(); start()"
            class="absolute left-4 top-1/2 -translate-y-1/2 text-white/20 hover:text-primary transition-colors duration-300 hidden group-hover:block z-20">
            <x-mary-icon name="o-chevron-left" class="w-12 h-12" />
        </button>
        <button @click="next(); stop(); start()"
            class="absolute right-4 top-1/2 -translate-y-1/2 text-white/20 hover:text-primary transition-colors duration-300 hidden group-hover:block z-20">
            <x-mary-icon name="o-chevron-right" class="w-12 h-12" />
        </button>

    </div>
@else
    {{-- Fallback Static Hero if no slides --}}
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
@endif