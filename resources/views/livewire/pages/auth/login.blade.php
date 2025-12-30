<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.login')] class extends Component {
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        if (auth()->user()->email === 'admin@admin.com') {
            $this->redirect(route('admin.dashboard', absolute: false), navigate: false);
            return;
        }

        $this->redirect(route('dashboard', absolute: false), navigate: false);
    }
}; ?>

<div id="login-form" class="login-form p-4 rounded-2xl backdrop-blur-md bg-white/30 dark:bg-gray-900/30">
    <div class="mb-4 flex items-center justify-center gap-4">
        <a href="/" wire:navigate>
            <img src="{{ asset('images/logo.webp') }}" class="w-20 h-20" alt="Logo">
        </a>
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">{{ config('app.name', 'Laravel') }}</h1>
    </div>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login">
        <!-- Email Address -->
        <div>
            <x-mary-input wire:model="form.email" id="email" type="email" name="email" required autofocus
                autocomplete="username" label="Email" />
            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-mary-input wire:model="form.password" id="password" type="password" name="password" required
                autocomplete="current-password" label="Contraseña" />

            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <x-mary-toggle wire:model="form.remember" id="remember" label="Recordarme" />
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    href="{{ route('password.request') }}" wire:navigate>
                    {{ __('Olvidó su contraseña?') }}
                </a>
            @endif

            <x-mary-button class="ms-3 btn-success" icon="o-arrow-right-end-on-rectangle" type="submit">
                {{ __('Iniciar Sesión') }}
            </x-mary-button>
        </div>
    </form>
</div>