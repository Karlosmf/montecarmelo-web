<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="montecarmelo">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title.' - '.config('app.name') : config('app.name') }}</title>

    {{-- Google Fonts: Playfair Display & Lato --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen font-sans antialiased bg-background-main text-text-main flex flex-col">

    {{-- Ambient Atmosphere --}}
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
        <div class="absolute inset-0 bg-[#0a0a0a]"></div>

        <div class="absolute top-[-10%] left-[-10%] w-[40vw] h-[40vw] bg-[#D4AF37] rounded-full mix-blend-soft-light filter blur-[100px] opacity-20 animate-pulse-slow"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[35vw] h-[35vw] bg-[#8C1C13] rounded-full mix-blend-soft-light filter blur-[120px] opacity-20"></div>
        <div class="absolute top-[40%] left-[50%] transform -translate-x-1/2 w-[50vw] h-[50vw] bg-[#1E1E1E] rounded-full mix-blend-overlay filter blur-[150px] opacity-40"></div>

        <div class="absolute inset-0 opacity-[0.03]" style="background-image: url('https://grainy-gradients.vercel.app/noise.svg');"></div>
    </div>

    {{-- 
        DRAWER WRAPPER 
        The drawer wraps the entire page content to allow the sidebar to slide over it on mobile.
        We do NOT hide this on desktop. We just hide the sidebar trigger/content on desktop via internal classes.
    --}}
    <div class="drawer">
        <input id="main-drawer" type="checkbox" class="drawer-toggle" />
        
        {{-- PAGE CONTENT --}}
        <div class="drawer-content flex flex-col min-h-screen">
            
            {{-- NAVBAR --}}
            <livewire:components.navbar />

            {{-- MAIN SLOT --}}
            <main class="flex-grow">
                {{ $slot }}
            </main>

            {{-- FOOTER --}}
            <livewire:components.footer />
            
        </div> 
        
        {{-- DRAWER SIDEBAR (Mobile Only) --}}
        <div class="drawer-side z-50">
            <label for="main-drawer" aria-label="close sidebar" class="drawer-overlay"></label>
            <div class="menu p-4 w-80 min-h-full bg-base-200 text-base-content">
                {{-- Sidebar Content --}}
                <div class="p-4 pt-2 text-center mb-4">
                    <div class="text-3xl font-bold text-primary tracking-wide">Monte Carmelo</div>
                    <div class="text-xs text-gray-400 mt-1 uppercase tracking-widest">Premium Deli</div>
                </div>
                
                <ul>
                    <li><a href="/" class="text-lg"><x-mary-icon name="o-home" class="w-5 h-5"/> Inicio</a></li>
                    <li><a href="/products" class="text-lg"><x-mary-icon name="o-shopping-bag" class="w-5 h-5"/> Productos</a></li>
                    <li><a href="#" class="text-lg"><x-mary-icon name="o-users" class="w-5 h-5"/> Nosotros</a></li>
                    <li><a href="/contact" class="text-lg"><x-mary-icon name="o-map-pin" class="w-5 h-5"/> Contacto</a></li>
                </ul>
            </div>
        </div>
    </div>

    {{-- CART DRAWER --}}
    <livewire:components.cart-drawer />

    {{-- TOAST --}}
    <x-mary-toast />
</body>
</html>