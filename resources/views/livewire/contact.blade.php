<?php

use function Livewire\Volt\{state, rules, layout, uses};
use Mary\Traits\Toast;

uses([Toast::class]);
layout('components.layouts.app');

state([
    'business_name' => '',
    'business_type' => '',
    'whatsapp' => ''
]);

rules([
    'business_name' => 'required|min:3',
    'business_type' => 'required',
    'whatsapp' => 'required|numeric|min:10'
]);

$save = function () {
    $this->validate();
    
    \App\Models\Lead::create([
        'business_name' => $this->business_name,
        'business_type' => $this->business_type,
        'whatsapp' => $this->whatsapp,
        'status' => 'new',
    ]);

    
    $this->success("¡Solicitud enviada! Te contactaremos a la brevedad.");
    $this->reset();
};

?>

<div class="py-16 px-4 max-w-6xl mx-auto">
    
    <div class="text-center mb-16 pt-10" data-reveal>
        <h1 class="h1-hero text-5xl mb-6">Contacto</h1>
        <p class="body-text text-lg text-text-muted max-w-2xl mx-auto">Visitanos en nuestra tienda o contactanos para convertirte en distribuidor oficial.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 lg:gap-24">
        
        {{-- LEFT COLUMN: POINT OF SALE --}}
        <div data-reveal>
            <h2 class="h2-section text-2xl mb-10 flex items-center gap-4">
                <span class="w-12 h-px bg-primary block"></span> Nuestro Local
            </h2>

            <div class="glass-panel p-8 md:p-10 border-beam group">
                <div class="flex flex-col md:flex-row gap-7 md:gap-9 items-start">
                    <div class="w-12 h-12 shrink-0 rounded-full bg-background-card border border-white/10 flex items-center justify-center text-primary group-hover:scale-105 transition-transform duration-500">
                        <i class="ph ph-storefront text-xl leading-none" aria-hidden="true"></i>
                    </div>

                    <div class="space-y-7 flex-1">
                        <h3 class="h3-product text-2xl text-text-main tracking-[0.08em] uppercase font-semibold">Casa Central</h3>

                        <div class="flex flex-col gap-5 body-text text-text-muted">
                            <div class="flex items-start gap-4 hover:text-primary transition-colors duration-300">
                                <i class="ph ph-map-pin text-lg leading-none text-primary/70 mt-0.5" aria-hidden="true"></i>
                                <span>{{ \App\Support\Settings::get('store.address', 'Pje. 44-46, S3560 Reconquista, Santa Fe') }}</span>
                            </div>
                            <div class="flex items-center gap-4 hover:text-primary transition-colors duration-300">
                                <i class="ph ph-clock text-lg leading-none text-primary/70" aria-hidden="true"></i>
                                <span>Lunes a Viernes 08:00 a 17:00</span>
                            </div>
                            <div class="flex items-center gap-4 hover:text-primary transition-colors duration-300">
                                <i class="ph ph-phone text-lg leading-none text-primary/70" aria-hidden="true"></i>
                                <span>{{ \App\Support\Settings::get('contact.phone', '+54 9 3482 53-5220') }}</span>
                            </div>
                            <div class="flex items-center gap-4 hover:text-primary transition-colors duration-300">
                                <i class="ph ph-envelope text-lg leading-none text-primary/70" aria-hidden="true"></i>
                                <span>{{ \App\Support\Settings::get('contact.email', 'contacto@montecarmelo.com.ar') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN: B2B FORM --}}
        <div data-reveal>
             <h2 class="h2-section text-2xl mb-4 flex items-center gap-4">
                 <span class="w-12 h-px bg-accent-wine block"></span> ¿Tenés un negocio?
            </h2>
            <p class="mb-10 body-text text-text-muted">Llevá la calidad de Monte Carmelo a tu mostrador. Precios especiales para mayoristas.</p>

            <form wire:submit="save" class="card-modern p-8 md:p-10 border-t border-primary/20">
                <div class="space-y-6">
                    <div>
                        <label class="block text-xs uppercase tracking-widest text-text-muted mb-2 font-serif">Nombre del Negocio</label>
                        <input type="text" wire:model="business_name" class="input-gold-line w-full" placeholder="Ej: Fiambrería Gourmet">
                        @error('business_name') <span class="text-accent-wine text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-xs uppercase tracking-widest text-text-muted mb-2 font-serif">Rubro</label>
                        <select wire:model="business_type" class="input-gold-line w-full bg-background-card">
                            <option value="">Seleccioná un rubro...</option>
                            <option value="almacen">Almacén / Despensa</option>
                            <option value="restaurante">Restaurante / Bar</option>
                            <option value="supermercado">Supermercado</option>
                            <option value="otro">Otro</option>
                        </select>
                        @error('business_type') <span class="text-accent-wine text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs uppercase tracking-widest text-text-muted mb-2 font-serif">WhatsApp</label>
                        <div class="relative">
                            <span class="absolute left-0 top-3 text-text-muted">+54 9</span>
                            <input type="tel" wire:model="whatsapp" class="input-gold-line w-full pl-14" placeholder="11 2233-4455">
                        </div>
                        <p class="text-xs text-text-placeholder mt-2">Te enviaremos la lista de precios por acá.</p>
                        @error('whatsapp') <span class="text-accent-wine text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-4" data-magnetic>
                        <button type="submit" class="btn-luxury w-full flex justify-center">
                            Solicitar Lista de Precios
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
