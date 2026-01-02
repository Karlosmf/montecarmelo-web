<?php

use Livewire\Volt\Component;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\Hash;

new
    #[Layout('layouts.login')]
    class extends Component {

    #[Rule('required|min:3')]
    public string $name = '';

    #[Rule('required|email|unique:users,email')]
    public string $email = '';

    #[Rule('required|min:6')]
    public string $password = '';

    #[Rule('required|min:2')]
    public string $company_name = '';

    #[Rule('required|string|max:20')]
    public string $cuit = '';

    #[Rule('required|string|max:20')]
    public string $phone = '';

    #[Rule('required|string')]
    public string $address = '';

    public function register()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => 'user',
            'company_name' => $this->company_name,
            'cuit' => $this->cuit,
            'phone' => $this->phone,
            'address' => $this->address,
            'is_active' => false, // Important: Created as inactive
        ]);

        // WhatsApp Redirect Logic
        $message = urlencode("Hola, soy *{$this->company_name}* (CUIT: {$this->cuit}). Acabo de registrarme en la web y espero aprobación para ver el catálogo y precios.");
        // Replace with actual business number if available, using strict standard for now or dynamic
        $whatsappUrl = "https://wa.me/5491112345678?text={$message}";

        // Redirect to WhatsApp or Success Page (User requested WhatsApp finish)
        return redirect()->away($whatsappUrl);
    }
}; ?>

<div class="min-h-screen flex items-center justify-center p-4">
    <div class="glass-panel p-8 max-w-md w-full rounded-2xl border border-white/10 shadow-2xl relative overflow-hidden">

        {{-- Decorative Elements --}}
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-[#D4AF37] to-[#8C1C13]"></div>

        <div class="text-center mb-8">
            <x-mary-icon name="o-building-storefront" class="w-12 h-12 text-primary mx-auto mb-3 opacity-80" />
            <h1
                class="font-serif text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-[#D4AF37] to-[#F1C40F]">
                Registro Mayorista
            </h1>
            <p class="text-text-muted text-sm mt-2">
                Crea tu cuenta comercial para acceder a precios exclusivos.
            </p>
        </div>

        <x-mary-form wire:submit="register" class="space-y-4">

            <x-mary-input label="Nombre de Contacto" wire:model="name" icon="o-user"
                class="!bg-black/30 border-white/10 focus:border-primary" />
            <x-mary-input label="Email Corporativo" wire:model="email" icon="o-envelope"
                class="!bg-black/30 border-white/10 focus:border-primary" />
            <x-mary-password label="Contraseña" wire:model="password"
                class="!bg-black/30 border-white/10 focus:border-primary" />

            <div class="grid grid-cols-2 gap-4">
                <x-mary-input label="Razón Social / Empresa" wire:model="company_name" icon="o-briefcase"
                    class="!bg-black/30 border-white/10 focus:border-primary col-span-2" />
                <x-mary-input label="CUIT" wire:model="cuit" icon="o-identification"
                    class="!bg-black/30 border-white/10 focus:border-primary" />
                <x-mary-input label="Teléfono / WhatsApp" wire:model="phone" icon="o-phone"
                    class="!bg-black/30 border-white/10 focus:border-primary" />
            </div>

            <x-mary-input label="Dirección de Entrega" wire:model="address" icon="o-map-pin"
                class="!bg-black/30 border-white/10 focus:border-primary" />

            <div class="pt-4">
                <x-mary-button label="Solicitar Cuenta"
                    class="btn-primary w-full font-bold tracking-widest shadow-lg shadow-primary/20" type="submit"
                    spinner="register" icon="o-paper-airplane" />
                <p class="text-xs text-center text-text-muted mt-4 opacity-70">
                    Al solicitar cuenta, serás redirigido a WhatsApp para confirmar tus datos con un asesor.
                </p>
            </div>
        </x-mary-form>

        <div class="mt-6 text-center border-t border-white/5 pt-4">
            <a href="/login" class="text-sm text-primary hover:text-white transition-colors">
                ¿Ya tienes cuenta? Iniciar Sesión
            </a>
        </div>
    </div>
</div>