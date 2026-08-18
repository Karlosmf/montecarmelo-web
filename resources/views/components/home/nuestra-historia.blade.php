<section id="nuestra-historia" class="py-32 relative overflow-hidden">
    <div class="container mx-auto px-4 lg:px-12 flex flex-col lg:flex-row items-center gap-16 lg:gap-24">
        
        {{-- Left Content --}}
        <div class="w-full lg:w-1/2 flex flex-col items-start text-left z-10">
            <svg class="w-12 h-12 text-primary mb-8" data-stroke-draw fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M12 2l2.4 7.4h7.6l-6.2 4.5 2.4 7.4-6.2-4.5-6.2 4.5 2.4-7.4-6.2-4.5h7.6z" />
            </svg>
            
            <h2 data-reveal class="h2-section mb-6">NUESTRA HISTORIA</h2>
            
            <p data-split-reveal class="body-text text-lg leading-relaxed mb-8">
                Lo que comenzó como una pasión familiar en el corazón de Reconquista, hoy se ha convertido en sinónimo de excelencia.
                En Monte Carmelo, cada pieza cuenta una historia de paciencia, tradición y dedicación absoluta al arte de la charcutería.
                Nuestros maestros artesanos seleccionan cuidadosamente cada materia prima para crear experiencias únicas que despiertan los sentidos.
            </p>
            
            <h3 data-split-reveal class="text-xl md:text-2xl font-serif italic text-primary/80 mb-10 leading-snug">
                "El destino nos marcó el camino hacia la perfección de los sabores."
            </h3>
            
            <div data-magnetic>
                <a href="/products" wire:navigate class="btn-magnetic btn-luxury">
                    <span class="btn-text">Descubrir Orígenes</span>
                </a>
            </div>
        </div>
        
        {{-- Right Content: Polaroid Stack --}}
        <div class="w-full lg:w-1/2 relative min-h-[500px]" data-parallax>
            <div class="img-halo w-[560px] h-[560px] top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 opacity-60"></div>
            <div class="polaroid-base absolute top-0 right-10 w-64 md:w-80 z-10 -rotate-3">
                <img src="{{ asset('storage/gallery/history-1.jpg') }}" alt="Salame artesanal" class="w-full h-auto">
                <p class="caption">El salame, atado a mano</p>
            </div>
            
            <div class="polaroid-base absolute top-20 left-0 w-64 md:w-80 z-20 rotate-6">
                <img src="{{ asset('storage/gallery/history-2.jpg') }}" alt="Jamón de reserva" class="w-full h-auto">
                <p class="caption">Jamón de reserva, +180 días de maduración</p>
            </div>
            
            <div class="polaroid-base absolute bottom-[-50px] right-20 w-64 md:w-80 z-30 -rotate-2">
                <img src="{{ asset('storage/gallery/history-3.jpg') }}" alt="Picada Monte Carmelo" class="w-full h-auto">
                <p class="caption">La picada Monte Carmelo</p>
            </div>
        </div>
        
    </div>
</section>
