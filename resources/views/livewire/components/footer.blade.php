<?php

use function Livewire\Volt\{state};

state([]);

?>

<footer class="bg-footer-bg border-t border-border-subtle pt-16 lg:pt-20 pb-8 relative overflow-hidden">
    <div class="container mx-auto px-4 lg:px-8 relative z-10">

        {{-- CTA "Hablemos" --}}
        <div data-reveal class="flex flex-col lg:flex-row lg:items-end justify-between gap-10 pb-16 mb-16 border-b border-border-subtle">
            <div class="max-w-xl space-y-4">
                <h2 class="h2-section !text-4xl lg:!text-6xl !mb-0">Hablemos</h2>
                <p class="body-text text-text-muted leading-relaxed">
                    Fiambres, quesos y cuchillería de elaboración propia para tu mesa, tu restaurante o tu distribuidora.
                    Escribinos y te respondemos a la brevedad.
                </p>
            </div>
            <div data-magnetic class="inline-block">
                <a href="/contact" wire:navigate class="btn-magnetic">
                    <span class="btn-text">CONVERSEMOS</span>
                </a>
            </div>
        </div>

        {{-- Columnas --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 lg:gap-16 mb-16">

            {{-- Marca --}}
            <div data-reveal class="space-y-6">
                <x-logo class="h-12" />
                <p class="body-text text-sm max-w-xs text-text-muted leading-relaxed">
                    Artesanos del sabor desde 1998. Elaboración propia, maduración controlada y la materia prima de la llanura santafesina.
                </p>
                <div class="flex gap-3 pt-2">
                    <a href="{{ \App\Support\Settings::get('social.instagram', '#') }}" target="_blank" aria-label="Instagram" class="footer-social">
                        <i class="ph ph-instagram-logo text-lg leading-none" aria-hidden="true"></i>
                    </a>
                    <a href="{{ \App\Support\Settings::get('social.facebook', '#') }}" target="_blank" aria-label="Facebook" class="footer-social">
                        <i class="ph ph-facebook-logo text-lg leading-none" aria-hidden="true"></i>
                    </a>
                    <a href="https://wa.me/{{ preg_replace('/\D/', '', \App\Support\Settings::get('contact.phone', '+54 9 3482 53-5220')) }}" target="_blank" aria-label="WhatsApp" class="footer-social">
                        <i class="ph ph-whatsapp-logo text-lg leading-none" aria-hidden="true"></i>
                    </a>
                </div>
            </div>

            {{-- Navegación --}}
            <div data-reveal class="md:justify-self-center">
                <h3 class="footer-heading">Navegación</h3>
                <ul class="mt-7 space-y-4">
                    <li><a href="/" wire:navigate class="reversed-link footer-link">Inicio</a></li>
                    <li><a href="/products" wire:navigate class="reversed-link footer-link">Nuestros Productos</a></li>
                    <li><a href="/#nuestra-historia" wire:navigate class="reversed-link footer-link">Nuestra Historia</a></li>
                    <li><a href="/contact" wire:navigate class="reversed-link footer-link">Contacto</a></li>
                </ul>
            </div>

            {{-- Contacto --}}
            <div data-reveal>
                <h3 class="footer-heading">Contacto</h3>
                <ul class="mt-7 space-y-5 body-text text-sm text-text-muted">
                    <li class="flex items-start gap-3">
                        <i class="ph ph-map-pin text-base leading-none text-primary mt-0.5" aria-hidden="true"></i>
                        <span>{!! nl2br(e(\App\Support\Settings::get('store.address', 'Pje. 44-46, S3560 Reconquista, Santa Fe'))) !!}</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="ph ph-phone text-base leading-none text-primary" aria-hidden="true"></i>
                        <span>{{ \App\Support\Settings::get('contact.phone', '+54 9 3482 53-5220') }}</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="ph ph-envelope text-base leading-none text-primary" aria-hidden="true"></i>
                        <span>{{ \App\Support\Settings::get('contact.email', 'contacto@montecarmelo.com.ar') }}</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="ph ph-clock text-base leading-none text-primary" aria-hidden="true"></i>
                        <span>Lunes a Viernes 08:00 a 17:00</span>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Barra inferior --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pt-8 border-t border-border-subtle">
            <p class="text-xs text-text-placeholder uppercase tracking-widest">
                © {{ date('Y') }} Monte Carmelo · Hecho con paciencia en Santa Fe
            </p>
            <div class="flex flex-wrap gap-x-7 gap-y-2">
                <a href="/products" wire:navigate class="reversed-link footer-link text-xs uppercase tracking-widest">Catálogo</a>
                <a href="/contact" wire:navigate class="reversed-link footer-link text-xs uppercase tracking-widest">Contacto</a>
                <a href="/" wire:navigate class="reversed-link footer-link text-xs uppercase tracking-widest">Inicio</a>
            </div>
        </div>
    </div>

    {{-- Decoración --}}
    <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-accent-wine rounded-full mix-blend-soft-light filter blur-[150px] opacity-10 pointer-events-none"></div>
</footer>