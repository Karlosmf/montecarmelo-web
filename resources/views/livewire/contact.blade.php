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
    
    // Logic to send email or save lead would go here
    // For now, simple feedback
    
    $this->success("¡Solicitud enviada! Te contactaremos a la brevedad.");
    $this->reset();
};

?>

<div class="py-16 px-4 max-w-6xl mx-auto">
    
    <div class="text-center mb-16">
        <h1 class="text-4xl font-serif font-bold text-primary mb-4">Contacto</h1>
        <p class="text-lg text-gray-400">Estamos cerca tuyo. Visitá nuestros locales o convertite en distribuidor.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 lg:gap-24">
        
        {{-- LEFT COLUMN: POINTS OF SALE --}}
        <div>
            <h2 class="text-2xl font-bold font-serif text-white mb-8 flex items-center gap-3">
                <span class="w-8 h-1 bg-primary block"></span> Encontranos en
            </h2>

            <div class="space-y-4">
                {{-- Local 1 --}}
                <x-mary-card class="bg-base-200 border border-base-300 shadow-md">
                    <x-mary-list-item :item="[]" class="!p-0">
                        <x-slot:avatar>
                            <div class="w-12 h-12 rounded-full bg-primary/20 flex items-center justify-center text-primary">
                                <x-mary-icon name="o-building-storefront" class="w-6 h-6" />
                            </div>
                        </x-slot:avatar>
                        <x-slot:value>
                            Sucursal Centro
                        </x-slot:value>
                        <x-slot:sub-value>
                             <div class="flex flex-col gap-1 mt-1 text-sm text-gray-400">
                                <div class="flex items-center gap-2">
                                    <x-mary-icon name="o-map-pin" class="w-4 h-4 text-primary" />
                                    <span>Av. Libertador 1234, CABA</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <x-mary-icon name="o-clock" class="w-4 h-4 text-primary" />
                                    <span>Lun a Vie: 9:00 - 20:00</span>
                                </div>
                                 <div class="flex items-center gap-2">
                                    <x-mary-icon name="o-phone" class="w-4 h-4 text-primary" />
                                    <span>+54 9 11 4444-5555</span>
                                </div>
                            </div>
                        </x-slot:sub-value>
                    </x-mary-list-item>
                </x-mary-card>

                 {{-- Local 2 --}}
                 <x-mary-card class="bg-base-200 border border-base-300 shadow-md">
                    <x-mary-list-item :item="[]" class="!p-0">
                        <x-slot:avatar>
                            <div class="w-12 h-12 rounded-full bg-primary/20 flex items-center justify-center text-primary">
                                <x-mary-icon name="o-building-storefront" class="w-6 h-6" />
                            </div>
                        </x-slot:avatar>
                        <x-slot:value>
                            Sucursal Norte
                        </x-slot:value>
                        <x-slot:sub-value>
                             <div class="flex flex-col gap-1 mt-1 text-sm text-gray-400">
                                <div class="flex items-center gap-2">
                                    <x-mary-icon name="o-map-pin" class="w-4 h-4 text-primary" />
                                    <span>Calle 18 743, Avellaneda</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <x-mary-icon name="o-clock" class="w-4 h-4 text-primary" />
                                    <span>Lun a Sab: 9:00 - 13:00 / 16:00 - 20:00</span>
                                </div>
                                 <div class="flex items-center gap-2">
                                    <x-mary-icon name="o-phone" class="w-4 h-4 text-primary" />
                                    <span>+54 9 11 2222-3333</span>
                                </div>
                            </div>
                        </x-slot:sub-value>
                    </x-mary-list-item>
                </x-mary-card>
            </div>
        </div>

        {{-- RIGHT COLUMN: B2B FORM --}}
        <div>
             <h2 class="text-2xl font-bold font-serif text-white mb-2 flex items-center gap-3">
                 <span class="w-8 h-1 bg-secondary block"></span> ¿Tenés un negocio?
            </h2>
            <p class="mb-8 text-gray-400">Llevá la calidad de Monte Carmelo a tu mostrador. Precios especiales para mayoristas.</p>

            <x-mary-form wire:submit="save" class="bg-base-200 p-8 rounded-xl border border-base-300 shadow-lg">
                <x-mary-input label="Nombre del Negocio" placeholder="Ej: Almacén Don Pepe" icon="o-briefcase" wire:model="business_name" />
                
                <x-mary-select label="Rubro" icon="o-tag" wire:model="business_type" :options="[
                    ['id' => 'almacen', 'name' => 'Almacén / Despensa'],
                    ['id' => 'restaurante', 'name' => 'Restaurante / Bar'],
                    ['id' => 'supermercado', 'name' => 'Supermercado'],
                    ['id' => 'otro', 'name' => 'Otro'],
                ]" placeholder="Seleccioná un rubro" />

                <x-mary-input label="WhatsApp" placeholder="Ej: 1122334455" icon="o-chat-bubble-left-right" prefix="+54 9" wire:model="whatsapp" type="tel" hint="Te enviaremos la lista de precios por acá." />

                <x-slot:actions>
                    <x-mary-button label="Solicitar Lista de Precios" class="btn-primary w-full font-bold" type="submit" spinner="save" />
                </x-slot:actions>
            </x-mary-form>
        </div>
    </div>
</div>
