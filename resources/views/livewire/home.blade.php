<?php

use function Livewire\Volt\{state, layout};
use App\Models\Product;

layout('components.layouts.app');

state(['featuredProducts' => fn() => Product::where('is_featured', true)->take(3)->get()]);

?>

<div class="font-sans text-base-content bg-base-100 overflow-x-hidden">

    {{-- HERO SECTION --}}
    <div class="relative w-full h-screen">
        <div class="absolute inset-0 bg-cover bg-center bg-no-repeat" style="background-image: url('https://images.unsplash.com/photo-1544025162-d76690b67f11?q=80&w=2000&auto=format&fit=crop');">
             <div class="absolute inset-0 bg-black/50"></div>
        </div>
        
        <div class="relative z-10 flex flex-col items-center justify-center h-full text-center px-4">
             <h1 class="text-5xl md:text-7xl lg:text-9xl font-serif font-bold text-white tracking-widest opacity-90 drop-shadow-2xl">
                MONTE CARMELO
            </h1>
        </div>
    </div>

    {{-- SECTION "SOMOS" --}}
    <section class="py-24 px-6 text-center bg-base-100 relative">
        <div class="max-w-3xl mx-auto flex flex-col items-center gap-8">
            {{-- Icon / Emblem --}}
            <div class="text-primary mb-2">
                <x-mary-icon name="o-scale" class="w-12 h-12" />
            </div>
            
            <h2 class="text-4xl md:text-5xl font-serif text-primary tracking-[0.2em] uppercase">Somos</h2>
            
            <p class="text-lg md:text-xl text-gray-400 font-light leading-relaxed max-w-2xl">
                Una familia dedicada al arte de los fiambres y quesos premium. 
                Combinamos recetas ancestrales con la pasión por el detalle para llevar 
                a tu mesa una experiencia de sabor inigualable.
            </p>

            <a href="#" class="btn btn-outline border-primary text-primary hover:bg-primary hover:text-base-100 rounded-full px-10 py-3 tracking-widest mt-4 transition-all duration-300">
                CONOCENOS
            </a>
        </div>
    </section>

    {{-- SECTION "POLAROID STACK" --}}
    <section class="py-24 px-4 bg-base-100 relative overflow-hidden">
        {{-- Decorative Background Elements (Simple Circles for abstract effect) --}}
        <div class="absolute top-10 left-10 w-64 h-64 bg-primary/5 rounded-full blur-3xl -z-10"></div>
        <div class="absolute bottom-10 right-10 w-96 h-96 bg-secondary/5 rounded-full blur-3xl -z-10"></div>

        <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center justify-center gap-12 min-h-[500px]">
            
            {{-- Stack of Polaroids --}}
            <div class="relative w-full max-w-lg h-96 mx-auto">
                 {{-- Photo 1 --}}
                <div class="absolute top-0 left-4 md:left-10 z-10 transform -rotate-6 w-64 md:w-72 bg-white p-3 shadow-2xl transition-transform hover:scale-105 hover:z-50 duration-500">
                    <img src="https://images.unsplash.com/photo-1625938145744-e38051524225?q=80&w=600&auto=format&fit=crop" class="w-full aspect-[4/5] object-cover grayscale-[20%] hover:grayscale-0 transition-all" alt="Picada">
                </div>
                
                 {{-- Photo 2 --}}
                <div class="absolute top-8 right-4 md:right-10 z-20 transform rotate-3 w-64 md:w-72 bg-white p-3 shadow-2xl transition-transform hover:scale-105 hover:z-50 duration-500">
                    <img src="https://images.unsplash.com/photo-1596560548464-f010549b84d7?q=80&w=600&auto=format&fit=crop" class="w-full aspect-[4/5] object-cover grayscale-[20%] hover:grayscale-0 transition-all" alt="Salame">
                </div>

                 {{-- Photo 3 --}}
                <div class="absolute bottom-0 left-1/2 -translate-x-1/2 z-30 transform -rotate-2 w-64 md:w-72 bg-white p-3 shadow-2xl transition-transform hover:scale-105 hover:z-50 duration-500">
                     <img src="https://images.unsplash.com/photo-1486297678162-eb2a19b0a32d?q=80&w=600&auto=format&fit=crop" class="w-full aspect-[4/5] object-cover grayscale-[20%] hover:grayscale-0 transition-all" alt="Quesos">
                </div>
            </div>

        </div>
    </section>

    {{-- SECTION "ESTAMOS" (Form Footer) --}}
    <section class="py-24 px-6 bg-base-200 text-base-content relative">
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">
            
            {{-- Left: Badge / Title --}}
            <div class="flex flex-col gap-6">
                <div class="w-24 h-24 border-2 border-primary rounded-full flex items-center justify-center text-primary mb-4 animate-spin-slow" style="animation-duration: 10s;">
                     <span class="text-xs tracking-widest uppercase font-bold text-center leading-tight p-2">Premium<br>Quality<br>Since '95</span>
                </div>
                <h2 class="text-5xl md:text-6xl font-serif font-bold leading-tight">
                    ESTAMOS<br>
                    <span class="text-primary italic font-light">DONDE</span><br>
                    TENEMOS<br>
                    QUE ESTAR.
                </h2>
                <p class="text-gray-500 text-lg max-w-md mt-4">
                    Escribinos. Queremos ser parte de tus mejores momentos.
                </p>
            </div>

            {{-- Right: Form --}}
            <div class="flex flex-col gap-8 pt-4">
                <div class="form-control w-full">
                    <input type="text" placeholder="NOMBRE" class="input input-gold-line w-full px-0 py-4 text-xl font-light placeholder-gray-600 focus:placeholder-primary/50" />
                </div>
                
                <div class="form-control w-full">
                    <input type="email" placeholder="EMAIL" class="input input-gold-line w-full px-0 py-4 text-xl font-light placeholder-gray-600 focus:placeholder-primary/50" />
                </div>
                
                <div class="form-control w-full">
                    <textarea placeholder="MENSAJE" rows="3" class="textarea textarea-ghost w-full px-0 py-4 text-xl font-light border-0 border-b-2 border-primary rounded-none focus:outline-none focus:ring-0 placeholder-gray-600 focus:placeholder-primary/50 resize-none"></textarea>
                </div>

                <div class="flex justify-end mt-4">
                    <button class="btn btn-ghost hover:bg-transparent hover:text-primary text-base-content font-serif tracking-[0.2em] text-lg group">
                        ENVIAR <span class="group-hover:translate-x-2 transition-transform duration-300">→</span>
                    </button>
                </div>
            </div>

        </div>
    </section>
</div>