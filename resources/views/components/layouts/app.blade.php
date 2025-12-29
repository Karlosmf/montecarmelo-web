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