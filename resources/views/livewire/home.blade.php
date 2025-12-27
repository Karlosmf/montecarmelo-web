<?php

use function Livewire\Volt\{state, layout, uses};
use App\Models\Product;
use Mary\Traits\Toast;

uses([Toast::class]);
layout('components.layouts.app');

state(['featuredProducts' => fn() => Product::where('is_featured', true)->take(3)->get()]);

$save = function () {
    // Contact form logic placeholder
    $this->success("¡Gracias por contactarnos! Te responderemos a la brevedad.");
};

?>

<div class="font-sans text-text-main bg-background-main overflow-x-hidden">

    {{-- 1. HERO SLIDER SECTION --}}
    <div class="relative w-full h-screen">
        {{-- Background Image --}}
        <div class="absolute inset-0 bg-cover bg-center bg-no-repeat" style="background-image: url('https://images.unsplash.com/photo-1544025162-d76690b67f11?q=80&w=2000&auto=format&fit=crop');">
             <div class="absolute inset-0 bg-black/50 bg-gradient-to-b from-transparent via-black/20 to-background-main"></div>
        </div>
        
        {{-- Content --}}
        <div class="relative z-10 flex flex-col items-center justify-center h-full text-center px-4">
             <h1 class="font-serif text-5xl md:text-8xl font-bold uppercase tracking-[0.15em] text-primary drop-shadow-2xl mb-6">
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

    {{-- 2. SECTION "SOMOS" --}}
    <section id="somos" class="py-32 px-6 bg-background-main relative overflow-hidden">
        <div class="container mx-auto grid grid-cols-1 md:grid-cols-12 gap-12 items-center">
            
            {{-- Left: Centered Icon (4 cols) --}}
            <div class="md:col-span-4 flex justify-center md:justify-end md:pr-12">
                <div class="text-[#D4AF37] opacity-80">
                     {{-- Using a star as a placeholder for Fleur-de-lis if not available, or a generic decorative icon --}}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-24 h-24" viewBox="0 0 24 24">
                        <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" />
                    </svg>
                </div>
            </div>

            {{-- Right: Content (8 cols) --}}
            <div class="md:col-span-8 flex flex-col items-start max-w-2xl">
                <h2 class="h2-section text-[#D4AF37]">SOMOS</h2>
                
                <p class="body-text text-justify mb-8">
                    En Monte Carmelo, creemos que la verdadera calidad nace del respeto por la tradición. 
                    Somos artesanos del sabor, dedicados a seleccionar las materias primas más nobles para 
                    crear productos que honran el paladar exigente. Nuestra historia es un viaje de pasión, 
                    donde cada corte refleja nuestro compromiso inquebrantable con la excelencia.
                </p>

                <a href="#story" class="btn-primary-outline">
                    CONOCENOS
                </a>
            </div>
        </div>
    </section>

    {{-- 3. SECTION "STORYTELLING" --}}
    <section id="story" class="py-32 bg-[#151515] relative overflow-hidden">
        
        {{-- Sketch Illustration (Right Top) --}}
        <div class="absolute -right-10 top-10 w-64 opacity-10 pointer-events-none">
             <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                <path fill="#D4AF37" d="M45.7,-76.3C58.9,-69.3,69.1,-55.6,76.3,-41.2C83.5,-26.8,87.7,-11.7,85.2,2.5C82.7,16.7,73.5,30,63.1,41.3C52.7,52.6,41.1,61.9,28.6,68.6C16.1,75.3,2.7,79.4,-9.9,77.8C-22.5,76.2,-34.3,68.9,-45.3,60.2C-56.3,51.5,-66.5,41.4,-73.4,29.3C-80.3,17.2,-83.9,3.1,-80.7,-9.6C-77.5,-22.3,-67.5,-33.6,-56.3,-42.2C-45.1,-50.8,-32.7,-56.7,-20.5,-64.3C-8.3,-71.9,3.7,-81.2,16.8,-83.5C29.9,-85.8,44,-81.1,45.7,-76.3Z" transform="translate(100 100)" />
            </svg>
        </div>

        <div class="container mx-auto flex flex-col lg:flex-row items-center gap-16 lg:gap-32 px-6">
            
            {{-- Left: Polaroid Stack --}}
            <div class="relative w-64 h-80 mx-auto lg:mx-0 flex-shrink-0">
                {{-- Card 1 (Rotated Left) --}}
                <div class="polaroid-base rotate-[-6deg] z-10 absolute inset-0">
                     <img src="https://images.unsplash.com/photo-1625938145744-e38051524225?q=80&w=400&h=500&fit=crop" class="w-full h-full object-cover grayscale-[30%]" alt="Tradición">
                </div>
                {{-- Card 2 (Rotated Right) --}}
                <div class="polaroid-base rotate-[3deg] z-20 absolute inset-0 translate-x-4 translate-y-4">
                     <img src="https://images.unsplash.com/photo-1596560548464-f010549b84d7?q=80&w=400&h=500&fit=crop" class="w-full h-full object-cover grayscale-[30%]" alt="Calidad">
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
                    <a href="/products" wire:navigate class="btn btn-outline border-[#D4AF37] text-[#D4AF37] hover:bg-[#D4AF37] hover:text-[#121212] rounded-full uppercase tracking-widest px-10 py-3 font-serif text-sm">
                        NUESTROS PRODUCTOS
                    </a>
                 </div>
            </div>
        </div>
    </section>

    {{-- 4. SECTION "GALLERY" --}}
    <section class="py-32 px-6 bg-background-main">
        <div class="container mx-auto">
            <h2 class="h2-section text-center mb-16 tracking-[0.3em]">GALERÍA DE FOTOS</h2>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                {{-- Image 1 --}}
                <div class="aspect-square overflow-hidden rounded-md group relative">
                    <img src="https://images.unsplash.com/photo-1529692236671-f1f6cf9683ba?q=80&w=500&fit=crop" class="w-full h-full object-cover transition duration-500 group-hover:scale-110 group-hover:opacity-80" alt="Gallery 1">
                </div>
                 {{-- Image 2 --}}
                 <div class="aspect-square overflow-hidden rounded-md group relative">
                    <img src="https://images.unsplash.com/photo-1606850903332-98446979602e?q=80&w=500&fit=crop" class="w-full h-full object-cover transition duration-500 group-hover:scale-110 group-hover:opacity-80" alt="Gallery 2">
                </div>
                 {{-- Image 3 --}}
                 <div class="aspect-square overflow-hidden rounded-md group relative">
                    <img src="https://images.unsplash.com/photo-1551024709-8f23befc6f87?q=80&w=500&fit=crop" class="w-full h-full object-cover transition duration-500 group-hover:scale-110 group-hover:opacity-80" alt="Gallery 3">
                </div>
                 {{-- Image 4 --}}
                 <div class="aspect-square overflow-hidden rounded-md group relative">
                    <img src="https://images.unsplash.com/photo-1560155016-bd4879ae8f21?q=80&w=500&fit=crop" class="w-full h-full object-cover transition duration-500 group-hover:scale-110 group-hover:opacity-80" alt="Gallery 4">
                </div>
                 {{-- Image 5 --}}
                 <div class="aspect-square overflow-hidden rounded-md group relative">
                    <img src="https://images.unsplash.com/photo-1457666134378-6b77915bd5f2?q=80&w=500&fit=crop" class="w-full h-full object-cover transition duration-500 group-hover:scale-110 group-hover:opacity-80" alt="Gallery 5">
                </div>
                 {{-- Image 6 --}}
                 <div class="aspect-square overflow-hidden rounded-md group relative">
                    <img src="https://images.unsplash.com/photo-1623855244183-52fd8d3ce2f7?q=80&w=500&fit=crop" class="w-full h-full object-cover transition duration-500 group-hover:scale-110 group-hover:opacity-80" alt="Gallery 6">
                </div>
                 {{-- Image 7 --}}
                 <div class="aspect-square overflow-hidden rounded-md group relative">
                    <img src="https://images.unsplash.com/photo-1582294158913-90d096d2719d?q=80&w=500&fit=crop" class="w-full h-full object-cover transition duration-500 group-hover:scale-110 group-hover:opacity-80" alt="Gallery 7">
                </div>
                 {{-- Image 8 --}}
                 <div class="aspect-square overflow-hidden rounded-md group relative">
                    <img src="https://images.unsplash.com/photo-1625938144755-652e08e359b7?q=80&w=500&fit=crop" class="w-full h-full object-cover transition duration-500 group-hover:scale-110 group-hover:opacity-80" alt="Gallery 8">
                </div>
            </div>
        </div>
    </section>

    {{-- 5. SECTION "CONTACT FOOTER" --}}
    <section class="py-24 px-6 bg-[#121212] border-t border-[#D4AF37]/20">
        <div class="container mx-auto grid grid-cols-1 lg:grid-cols-2 gap-20">
            
            {{-- Left: Badge/Title --}}
            <div class="flex flex-col justify-center">
                 <h2 class="font-serif text-5xl md:text-7xl font-bold leading-none uppercase tracking-wide text-white mb-8">
                    Estamos<br>
                    <span class="text-[#D4AF37] italic font-light ml-4">donde</span><br>
                    tenemos que<br>
                    estar.
                </h2>
                <div class="w-24 h-1 bg-[#D4AF37] mb-8"></div>
            </div>

            {{-- Right: Form --}}
            <div class="flex flex-col gap-8 max-w-md w-full lg:ml-auto bg-[#1E1E1E] p-8 rounded-sm shadow-2xl border border-white/5">
                <h3 class="font-serif text-2xl text-[#D4AF37] mb-2 uppercase">Contacto</h3>
                <p class="text-sm text-[#A3A3A3] mb-6">Para consultas comerciales, distribuciones o eventos.</p>

                <form wire:submit="save" class="space-y-6">
                    <div>
                        <input type="text" placeholder="Nombre y Apellido" class="input-gold-line" required />
                    </div>
                    <div>
                        <input type="email" placeholder="Correo electrónico" class="input-gold-line" required />
                    </div>
                    <div>
                        <input type="tel" placeholder="Número de teléfono" class="input-gold-line" />
                    </div>
                    <div>
                         <select class="input-gold-line bg-transparent">
                            <option value="" disabled selected class="text-gray-500">Motivo de la consulta</option>
                            <option value="comercial" class="text-black">Comercial</option>
                            <option value="distribucion" class="text-black">Distribución</option>
                            <option value="eventos" class="text-black">Eventos</option>
                            <option value="otro" class="text-black">Otro</option>
                        </select>
                    </div>
                    <div>
                        <textarea placeholder="Tu consulta" rows="3" class="input-gold-line resize-none"></textarea>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="btn btn-outline border-[#D4AF37] text-[#D4AF37] hover:bg-[#D4AF37] hover:text-black w-full rounded-none uppercase tracking-widest font-serif">
                            ENVIAR MENSAJE
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
