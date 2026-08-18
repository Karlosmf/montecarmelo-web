<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="montecarmelo">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @hasSection('title')
        <title>@yield('title') - {{ config('app.name') }}</title>
    @else
        <title>{{ config('app.name') }}</title>
    @endif

    <meta name="description" content="@yield('meta_description', 'Charcutería y Productos Premium en Monte Carmelo.')">
    <meta property="og:title" content="@yield('title', config('app.name'))" />
    <meta property="og:description" content="@yield('meta_description', 'Charcutería y Productos Premium.')" />
    <meta property="og:image" content="@yield('meta_image', asset('images/default-og.jpg'))" />
    <meta property="og:type" content="website" />

    {{-- Google Fonts: Fraunces & Manrope --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300..700;1,9..144,300..700&family=Manrope:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    {{-- Favicon --}}
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    
    {{-- JSON-LD Schema Base (FoodEstablishment) --}}
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@type": "FoodEstablishment",
      "name": "{{ \App\Support\Settings::get('store.name', 'Monte Carmelo') }}",
      "image": "{{ asset('images/default-og.jpg') }}",
      "@@id": "{{ url('/') }}",
      "url": "{{ url('/') }}",
      "telephone": "{{ \App\Support\Settings::get('contact.phone', '+54 9 3482 53-5220') }}",
      "address": {
        "@@type": "PostalAddress",
        "streetAddress": "{{ \App\Support\Settings::get('store.address', 'Pje. 44-46') }}",
        "addressLocality": "{{ \App\Support\Settings::get('store.city', 'Reconquista') }}",
        "addressRegion": "{{ \App\Support\Settings::get('store.province', 'Santa Fe') }}",
        "postalCode": "S3560",
        "addressCountry": "AR"
      },
      "openingHoursSpecification": {
        "@@type": "OpeningHoursSpecification",
        "dayOfWeek": [
          "Monday",
          "Tuesday",
          "Wednesday",
          "Thursday",
          "Friday"
        ],
        "opens": "08:00",
        "closes": "17:00"
      },
      "sameAs": [
        "{{ \App\Support\Settings::get('social.instagram', 'https://www.instagram.com/montecarmeloarg/') }}",
        "{{ \App\Support\Settings::get('social.facebook', 'https://www.facebook.com/montecarmeloarg') }}"
      ]
    }
    </script>
    @stack('schema')

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen font-sans antialiased bg-background-main text-text-main flex flex-col">

    <div class="scroll-progress fixed top-0 left-0 h-1 bg-primary z-[100] origin-left scale-x-0 transition-transform duration-100"></div>

    {{-- Ambient Atmosphere --}}
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
        <div class="absolute inset-0 bg-background-main"></div>

        <div
            class="absolute top-[-10%] left-[-10%] w-[40vw] h-[40vw] bg-primary rounded-full mix-blend-soft-light filter blur-[100px] opacity-20 animate-pulse-slow">
        </div>
        <div
            class="absolute bottom-[-10%] right-[-10%] w-[35vw] h-[35vw] bg-accent-wine rounded-full mix-blend-soft-light filter blur-[120px] opacity-20">
        </div>
        <div
            class="absolute top-[40%] left-[50%] transform -translate-x-1/2 w-[50vw] h-[50vw] bg-background-card rounded-full mix-blend-overlay filter blur-[150px] opacity-40">
        </div>

        {{-- Textura Pattern de fondo (detrás del contenido) --}}
        {{-- Textura Pattern de fondo (detrás del contenido) --}}
        <div class="absolute inset-0 opacity-[0.35]"
            style="background-image: url('/images/pattern.webp'); background-size: 400px; background-repeat: repeat;"></div>

        <div class="absolute inset-0 opacity-[0.03]"
            style="background-image: url('https://grainy-gradients.vercel.app/noise.svg');"></div>
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
            <div>
                <livewire:components.navbar />
            </div>

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
                    <x-logo class="h-8" />
                    <div class="text-xs text-gray-400 mt-1 uppercase tracking-widest">Premium Deli</div>
                </div>

                <ul>
                    <li><a href="/" wire:navigate class="text-lg"><x-mary-icon name="o-home" class="w-5 h-5" /> Inicio</a></li>
                    <li><a href="/products" wire:navigate class="text-lg"><x-mary-icon name="o-shopping-bag" class="w-5 h-5" />
                            Productos</a></li>
                    <li><a href="/#nuestra-historia" wire:navigate class="text-lg"><x-mary-icon name="o-users" class="w-5 h-5" /> Nosotros</a></li>
                    <li><a href="/contact" wire:navigate class="text-lg"><x-mary-icon name="o-map-pin" class="w-5 h-5" /> Contacto</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {{-- CART DRAWER --}}
    <livewire:components.cart-drawer />

    {{-- ACCIONES FLOTANTES --}}
    <livewire:components.floating-actions />

    {{-- TOAST --}}
    <x-mary-toast />
</body>

</html>