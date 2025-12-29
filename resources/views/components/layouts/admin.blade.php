<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="cupcake">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title.' - Admin Monte Carmelo' : 'Admin - Monte Carmelo' }}</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    
    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen font-sans antialiased bg-base-200/50">

    {{-- TOP NAVBAR (Mobile only) --}}
    <x-mary-nav sticky class="lg:hidden">
        <x-slot:brand>
            <div class="ml-5 text-xl font-bold text-primary tracking-tighter">MC <span class="text-base-content font-light">ADMIN</span></div>
        </x-slot:brand>
        <x-slot:actions>
            <label for="main-drawer" class="lg:hidden mr-3">
                <x-mary-icon name="o-bars-3" class="cursor-pointer" />
            </label>
        </x-slot:actions>
    </x-mary-nav>

    {{-- MAIN WRAPPER --}}
    <x-mary-main full-width>
        
        {{-- SIDEBAR --}}
        <x-slot:sidebar drawer="main-drawer" collapsible class="bg-base-100 border-r border-base-300">
            
            {{-- BRAND LOGO --}}
            <div class="p-6 pt-10 hidden lg:block">
                <div class="flex items-center gap-2">
                    <x-mary-icon name="o-sparkles" class="w-8 h-8 text-primary" />
                    <div>
                        <div class="font-bold text-lg leading-none text-primary uppercase tracking-widest">Monte Carmelo</div>
                        <div class="text-[10px] text-gray-400 uppercase tracking-[0.2em] font-bold">Panel de Control</div>
                    </div>
                </div>
            </div>

            {{-- MENU --}}
            <x-mary-menu activate-by-route class="mt-4">
                {{-- User Info (Sidebar version) --}}
                @if($user = auth()->user())
                    <x-mary-menu-item title="{{ $user->name }}" icon="o-user-circle" class="bg-base-200/50 mb-4" />
                @endif

                <x-mary-menu-item title="Estadísticas" icon="o-chart-bar" link="/admin/dashboard" />
                <x-mary-menu-item title="Pedidos / Leads" icon="o-inbox-stack" link="/admin/orders" />
                
                <x-mary-menu-separator title="Catálogo" />
                
                <x-mary-menu-item title="Productos" icon="o-cube" link="/admin/products" />
                <x-mary-menu-item title="Categorías" icon="o-tag" link="/admin/categories" />
                <x-mary-menu-item title="Etiquetas" icon="o-hashtag" link="/admin/tags" />
                
                <x-mary-menu-separator title="Sistema" />
                
                <x-mary-menu-item title="Usuarios" icon="o-users" link="/admin/users" />
                <x-mary-menu-item title="Ir al Sitio" icon="o-arrow-top-right-on-square" link="/" />
                
                <x-mary-menu-item title="Salir" icon="o-power" link="/logout" class="text-error mt-10" />
            </x-mary-menu>
        </x-slot:sidebar>

        {{-- MAIN CONTENT AREA --}}
        <x-slot:content>
            {{-- Header/Title Slot can be used in child components --}}
            <div class="max-w-7xl mx-auto">
                {{ $slot }}
            </div>
        </x-slot:content>
    </x-mary-main>

    {{-- TOAST NOTIFICATIONS --}}
    <x-mary-toast />
</body>
</html>
