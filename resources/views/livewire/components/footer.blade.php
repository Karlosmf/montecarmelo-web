<?php

use function Livewire\Volt\{state};

// No specific state logic needed for footer yet
state([]);

?>

<footer class="bg-base-200 text-base-content">
    <div class="footer p-10 max-w-7xl mx-auto">
        <aside>
            <div class="text-3xl font-serif font-bold text-primary mb-2">MC</div>
            <p class="font-bold text-lg">Monte Carmelo</p>
            <p class="text-sm opacity-75">Tradición y sabor premium<br/>en cada bocado desde 1995.</p>
        </aside> 
        <nav>
            <header class="footer-title text-primary opacity-100">Navegación</header> 
            <a href="/" class="link link-hover">Inicio</a>
            <a href="/products" class="link link-hover">Catálogo</a>
            <a href="#" class="link link-hover">Nosotros</a>
            <a href="#" class="link link-hover">Mayoristas</a>
        </nav> 
        <nav>
            <header class="footer-title text-primary opacity-100">Contacto</header> 
            <div class="flex gap-2 items-center">
                <x-mary-icon name="o-phone" class="w-4 h-4 text-secondary" />
                <span>+54 9 11 1234-5678</span>
            </div>
            <div class="flex gap-2 items-center">
                <x-mary-icon name="o-envelope" class="w-4 h-4 text-secondary" />
                <span>info@montecarmelo.com</span>
            </div>
            <div class="flex gap-2 items-center">
                <x-mary-icon name="o-map-pin" class="w-4 h-4 text-secondary" />
                <span>Av. Libertador 1234, CABA</span>
            </div>
        </nav> 
        <nav>
            <header class="footer-title text-primary opacity-100">Social</header> 
            <div class="grid grid-flow-col gap-4">
                <a class="cursor-pointer hover:text-primary transition"><x-mary-icon name="c-code-bracket-square" class="w-6 h-6" /></a>
                <a class="cursor-pointer hover:text-primary transition"><x-mary-icon name="c-bolt" class="w-6 h-6" /></a>
            </div>
        </nav>
    </div>
    
    <div class="footer footer-center p-4 bg-base-300 text-base-content/60">
        <aside>
            <p>Copyright © {{ date('Y') }} - Todos los derechos reservados por Monte Carmelo S.A.</p>
        </aside>
    </div>
</footer>
