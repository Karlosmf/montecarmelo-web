<?php

use function Livewire\Volt\{state, rules, uses};
use Mary\Traits\Toast;

uses([Toast::class]);

state([
    'name' => '',
    'email' => '',
    'phone' => '',
    'reason' => '',
    'message' => ''
]);

rules([
    'name' => 'required|min:3',
    'email' => 'required|email',
    'phone' => 'nullable',
    'reason' => 'nullable',
    'message' => 'nullable'
]);

$save = function () {
    $this->validate();
    // Contact form logic placeholder
    $this->success("¡Gracias por contactarnos! Te responderemos a la brevedad.");
    $this->reset();
};

?>

<section id="contact" class="py-24 px-6 bg-[#121212] relative overflow-hidden">

    {{-- Decorative line --}}
    <div class="absolute top-0 left-1/2 transform -translate-x-1/2 w-24 h-[1px] bg-[#D4AF37]/30"></div>

    <div class="container mx-auto grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">

        {{-- Left: Badge/Title (Custom SVG similar to reference) --}}
        <div class="flex flex-col items-center lg:items-end justify-center">
            <div class="relative w-64 h-80 md:w-80 md:h-96 group perspective-1000">
                <svg class="w-full h-full text-[#D4AF37] opacity-90 drop-shadow-[0_0_15px_rgba(212,175,55,0.2)]"
                    viewBox="0 0 200 300" fill="none" stroke="currentColor" stroke-width="1.5">
                    {{-- Rhombus/Shield Shape --}}
                    <path
                        d="M100 10 C 140 30, 190 80, 190 150 C 190 220, 140 270, 100 290 C 60 270, 10 220, 10 150 C 10 80, 60 30, 100 10 Z"
                        fill="none" />

                    {{-- Inner detail lines --}}
                    <path
                        d="M100 25 C 130 40, 175 85, 175 150 C 175 215, 130 260, 100 275 C 70 260, 25 215, 25 150 C 25 85, 70 40, 100 25 Z"
                        stroke-opacity="0.5" stroke-dasharray="2 2" />

                    {{-- Text inside SVG for perfect scaling --}}
                    <text x="100" y="90" text-anchor="middle" font-family="serif" font-size="14" fill="#D4AF37"
                        stroke="none" class="uppercase tracking-widest">Estamos</text>
                    <text x="100" y="115" text-anchor="middle" font-family="serif" font-size="20" fill="#D4AF37"
                        stroke="none" class="uppercase tracking-widest font-bold">Donde</text>
                    <text x="100" y="150" text-anchor="middle" font-family="serif" font-size="28" fill="#D4AF37"
                        stroke="none" class="uppercase tracking-tighter font-bold">TENEMOS</text>
                    <text x="100" y="175" text-anchor="middle" font-family="serif" font-size="12" fill="#D4AF37"
                        stroke="none" class="uppercase tracking-[0.3em]">QUE</text>
                    <text x="100" y="210" text-anchor="middle" font-family="serif" font-size="24" fill="#D4AF37"
                        stroke="none" class="uppercase tracking-[0.2em] font-bold">ESTAR</text>
                </svg>
            </div>
        </div>

        {{-- Right: Form --}}
        <div class="flex flex-col gap-10 max-w-md w-full lg:mr-auto pl-0 lg:pl-12">
            <div>
                <h3 class="h2-section !mb-2 !text-2xl">ESTAMOS</h3>
                <p class="body-text text-sm">Para consultas comerciales, completa este formulario y nos pondremos en
                    contacto a la brevedad.</p>
            </div>

            <form wire:submit="save" class="space-y-6">
                <div class="group">
                    <input type="text" wire:model="name" placeholder="Nombre y Apellido" class="input-gold-line"
                        required />
                    @error('name') <span class="text-error text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="group">
                    <input type="email" wire:model="email" placeholder="Correo electrónico" class="input-gold-line"
                        required />
                    @error('email') <span class="text-error text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="group">
                    <input type="tel" wire:model="phone" placeholder="Número de teléfono" class="input-gold-line" />
                </div>
                <div class="group relative">
                    <select wire:model="reason" class="input-gold-line bg-[#121212] appearance-none cursor-pointer">
                        <option value="" disabled selected>Motivo de la consulta</option>
                        <option value="comercial">Comercial</option>
                        <option value="distribucion">Distribución</option>
                        <option value="eventos">Eventos</option>
                    </select>
                    <div class="absolute right-0 top-1/2 -translate-y-1/2 pointer-events-none text-[#D4AF37]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
                <div class="group">
                    <textarea wire:model="message" placeholder="Tu consulta" rows="2"
                        class="input-gold-line resize-none"></textarea>
                </div>

                <div class="pt-8">
                    {{-- Using a button style that matches the reference (simple text or minimal button) --}}
                    <button type="submit" class="btn-luxury w-full md:w-auto">
                        ENVIAR
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Footer decorative logo center --}}
    <div class="flex justify-center mt-20 opacity-50">
        <div
            class="w-12 h-12 border border-[#D4AF37] rounded-full flex items-center justify-center text-[#D4AF37] font-serif">
            M
        </div>
    </div>
</section>