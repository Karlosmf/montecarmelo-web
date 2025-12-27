<?php

use function Livewire\Volt\{state};

state([]);

?>

<footer class="bg-[#0a0a0a] border-t border-primary/20 py-16">
    <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-12">
        
        {{-- Column 1: Brand --}}
        <div class="space-y-6">
            <div class="text-4xl font-serif font-bold text-primary tracking-widest uppercase">
                Monte Carmelo
            </div>
            <p class="body-text text-sm max-w-xs">
                Artesanos del sabor. Una experiencia culinaria premium que honra la tradición y la excelencia en cada detalle.
            </p>
            <div class="flex gap-4">
                {{-- Social Placeholders --}}
                <a href="#" class="text-primary hover:text-white transition"><x-mary-icon name="o-camera" class="w-6 h-6" /></a>
                <a href="#" class="text-primary hover:text-white transition"><x-mary-icon name="o-heart" class="w-6 h-6" /></a>
            </div>
            <div class="text-xs text-text-muted mt-8">
                &copy; {{ date('Y') }} Monte Carmelo.
            </div>
        </div>

        {{-- Column 2: Navigation --}}
        <div>
            <h3 class="h3-product text-lg mb-6 border-b border-primary/30 pb-2 inline-block">Navegación</h3>
            <div class="flex flex-col space-y-4">
                <a href="/" class="nav-link">Inicio</a>
                <a href="/products" class="nav-link">Nuestros Productos</a>
                <a href="#" class="nav-link">Historia</a>
                <a href="/contact" class="nav-link">Contacto</a>
            </div>
        </div>

        {{-- Column 3: Contact --}}
        <div>
             <h3 class="h3-product text-lg mb-6 border-b border-primary/30 pb-2 inline-block">Contacto</h3>
             <div class="space-y-4 body-text text-sm">
                <div class="flex items-start gap-3">
                    <x-mary-icon name="o-map-pin" class="w-5 h-5 text-primary shrink-0" />
                    <span>Av. Libertador 1234<br>Buenos Aires, Argentina</span>
                </div>
                <div class="flex items-center gap-3">
                    <x-mary-icon name="o-phone" class="w-5 h-5 text-primary shrink-0" />
                    <span>+54 9 11 1234-5678</span>
                </div>
                <div class="flex items-center gap-3">
                    <x-mary-icon name="o-envelope" class="w-5 h-5 text-primary shrink-0" />
                    <span>info@montecarmelo.com</span>
                </div>
             </div>
        </div>

    </div>
</footer>
