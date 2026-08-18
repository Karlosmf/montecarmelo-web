@props(['images'])

<section id="galeria" class="py-32 px-6 relative overflow-hidden" x-data="{
    lightboxOpen: false,
    activeIndex: 0,
    images: [
        @foreach($visibleImages ?? $images as $image)
            { src: '{{ asset('storage/' . $image->image_path) }}', title: '{{ str_replace("'", "\\'", $image->title) }}' }@if(!$loop->last),@endif
        @endforeach
    ],
    open(index) {
        this.activeIndex = index;
        this.lightboxOpen = true;
        document.body.style.overflow = 'hidden';
    },
    close() {
        this.lightboxOpen = false;
        this.activeIndex = 0;
        document.body.style.overflow = '';
    },
    prev() {
        this.activeIndex = (this.activeIndex - 1 + this.images.length) % this.images.length;
    },
    next() {
        this.activeIndex = (this.activeIndex + 1) % this.images.length;
    }
}">
    <div class="section-glow w-[60vw] h-[60vw] top-0 left-1/2 -translate-x-1/2 opacity-60"></div>
    <div class="container mx-auto relative z-10">
        <h2 data-reveal class="h2-section text-center mb-16 tracking-[0.3em]">GALERÍA</h2>

        @if(isset($images) && $images->count() > 0)
            <div class="gallery-masonry">
                @foreach($images as $index => $image)
                    <div data-reveal-stagger class="gallery-tile break-inside-avoid group"
                        @click="open({{ $index }})" role="button" :aria-label="'Abrir imagen {{ $image->title }}'">
                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $image->title }}"
                            class="w-full h-auto" loading="lazy">
                        <div class="gallery-tile-caption">
                            <span class="gallery-tile-index">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="gallery-tile-title">{{ $image->title }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center text-text-muted body-text py-20">
                Aún no hay imágenes en la galería.
            </div>
        @endif
    </div>

    {{-- Lightbox con navegación prev/next --}}
    <div x-show="lightboxOpen" style="display: none;"
        class="fixed inset-0 z-[100] bg-[#0a0808]/96 backdrop-blur-sm flex items-center justify-center p-4"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @keydown.window.left.prevent="prev()"
        @keydown.window.right.prevent="next()"
        @keydown.window.escape.prevent="close()">

        {{-- Cerrar --}}
        <button @click="close()" class="absolute top-6 right-6 text-text-muted hover:text-primary transition-colors z-10">
            <x-mary-icon name="o-x-mark" class="w-10 h-10" />
        </button>

        {{-- Anterior --}}
        <button @click="prev()" aria-label="Imagen anterior"
            class="absolute left-3 md:left-8 top-1/2 -translate-y-1/2 z-10 text-text-muted hover:text-primary transition-colors duration-300"
            :class="{ 'opacity-0 pointer-events-none': images.length <= 1 }">
            <x-mary-icon name="o-arrow-left" class="w-11 h-11 md:w-14 md:h-14" />
        </button>

        <div class="max-w-5xl w-full" @click.self="close()">
            <figure class="flex flex-col items-center">
                <img :src="images[activeIndex].src" :alt="images[activeIndex].title"
                    class="max-w-full max-h-[76vh] object-contain rounded-[2px] shadow-2xl">
                <figcaption class="mt-5 flex items-center gap-5 text-text-muted">
                    <span class="font-serif italic text-lg text-primary tracking-wide" x-text="images[activeIndex].title"></span>
                    <span class="text-[10px] uppercase tracking-[0.25em] text-text-placeholder" x-text="(activeIndex + 1) + ' / ' + images.length"></span>
                </figcaption>
            </figure>
        </div>

        {{-- Siguiente --}}
        <button @click="next()" aria-label="Imagen siguiente"
            class="absolute right-3 md:right-8 top-1/2 -translate-y-1/2 z-10 text-text-muted hover:text-primary transition-colors duration-300"
            :class="{ 'opacity-0 pointer-events-none': images.length <= 1 }">
            <x-mary-icon name="o-arrow-right" class="w-11 h-11 md:w-14 md:h-14" />
        </button>
    </div>
</section>