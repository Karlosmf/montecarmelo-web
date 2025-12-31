<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="montecarmelo">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' - Admin Monte Carmelo' : 'Admin - Monte Carmelo' }}</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&family=Playfair+Display:wght@400;600;700&display=swap"
        rel="stylesheet">

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen font-sans antialiased bg-gradient-to-br from-[#0f0f0f] to-[#1a1a1a] text-text-main">

    {{-- Ambient Lighting --}}
    <div class="fixed inset-0 z-[-1] overflow-hidden pointer-events-none">
        <div
            class="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] bg-[#D4AF37] rounded-full mix-blend-screen filter blur-[120px] opacity-10 animate-pulse">
        </div>
        <div
            class="absolute bottom-[-10%] right-[-10%] w-[600px] h-[600px] bg-[#8C1C13] rounded-full mix-blend-screen filter blur-[130px] opacity-10">
        </div>
    </div>

    {{-- TOP NAVBAR (Mobile only) --}}
    <x-mary-nav sticky class="lg:hidden glass-dark border-b border-white/5">
        <x-slot:brand>
            <div class="ml-5 text-xl font-serif font-bold text-primary tracking-widest uppercase">MC <span
                    class="text-white font-light opacity-50">ADMIN</span></div>
        </x-slot:brand>
        <x-slot:actions>
            <label for="main-drawer" class="lg:hidden mr-3">
                <x-mary-icon name="o-bars-3" class="cursor-pointer text-primary" />
            </label>
        </x-slot:actions>
    </x-mary-nav>

    {{-- MAIN WRAPPER --}}
    <x-mary-main full-width>

        {{-- SIDEBAR --}}
        <x-slot:sidebar drawer="main-drawer" collapsible class="glass-dark border-r border-white/5 !bg-transparent">

            {{-- BRAND LOGO --}}
            <div class="p-6 pt-10 hidden lg:block">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center border border-primary/30">
                        <x-mary-icon name="o-sparkles" class="w-6 h-6 text-primary" />
                    </div>
                    <div>
                        <div class="font-serif font-bold text-lg leading-none text-primary uppercase tracking-widest">
                            Monte Carmelo
                        </div>
                        <div class="text-[10px] text-text-muted uppercase tracking-[0.2em] font-bold mt-1">Control Panel
                        </div>
                    </div>
                </div>
            </div>

            {{-- MENU --}}
            <x-mary-menu activate-by-route class="mt-4 px-2 space-y-1">
                {{-- User Info (Sidebar version) --}}
                @if($user = auth()->user())
                    <div class="px-2 py-2 mb-6">
                        <div class="flex items-center justify-between glass-panel p-2 rounded-xl border border-white/5">
                            <div class="flex items-center gap-2">
                                <div
                                    class="w-8 h-8 rounded-full bg-primary/20 flex items-center justify-center text-primary font-bold text-xs border border-primary/30">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <span
                                    class="text-xs font-semibold truncate max-w-[100px] text-text-main">{{ $user->name }}</span>
                            </div>
                            <x-mary-button icon="o-power" link="/logout"
                                class="btn-ghost btn-xs text-error/70 hover:text-error" tooltip="Salir" />
                        </div>
                    </div>
                @endif

                <x-mary-menu-item title="Estadísticas" icon="o-chart-bar" link="/admin/dashboard"
                    class="hover:bg-primary/10 hover:text-primary transition-all duration-300 rounded-xl" />
                <x-mary-menu-item title="Pedidos / Leads" icon="o-inbox-stack" link="/admin/orders"
                    class="hover:bg-primary/10 hover:text-primary transition-all duration-300 rounded-xl">
                    <x-slot:actions>
                        @php
                            $pendingCount = \App\Models\Order::where('status', 'pending')->count();
                        @endphp
                        @if($pendingCount > 0)
                            <div class="badge badge-sm bg-error text-white border-0 animate-pulse">
                                {{ $pendingCount }}
                            </div>
                        @endif
                    </x-slot:actions>
                </x-mary-menu-item>

                <x-mary-menu-separator title="Catálogo"
                    class="text-text-muted/50 font-serif uppercase tracking-widest text-[10px]" />

                <x-mary-menu-item title="Productos" icon="o-cube" link="/admin/products"
                    class="hover:bg-primary/10 hover:text-primary transition-all duration-300 rounded-xl" />
                <x-mary-menu-item title="Categorías" icon="o-tag" link="/admin/categories"
                    class="hover:bg-primary/10 hover:text-primary transition-all duration-300 rounded-xl" />
                <x-mary-menu-item title="Etiquetas" icon="o-hashtag" link="/admin/tags"
                    class="hover:bg-primary/10 hover:text-primary transition-all duration-300 rounded-xl" />

                <x-mary-menu-separator title="Sistema"
                    class="text-text-muted/50 font-serif uppercase tracking-widest text-[10px]" />

                <x-mary-menu-item title="Usuarios" icon="o-users" link="/admin/users"
                    class="hover:bg-primary/10 hover:text-primary transition-all duration-300 rounded-xl" />
                <x-mary-menu-item title="Ir al Sitio" icon="o-arrow-top-right-on-square" link="/"
                    class="hover:bg-primary/10 hover:text-primary transition-all duration-300 rounded-xl" />

            </x-mary-menu>
        </x-slot:sidebar>

        {{-- MAIN CONTENT AREA --}}
        <x-slot:content>
            {{-- Header/Title Slot can be used in child components --}}
            <div class="max-w-7xl mx-auto p-6 lg:p-10 min-h-screen">
                {{ $slot }}
            </div>
        </x-slot:content>
    </x-mary-main>

    {{-- TOAST NOTIFICATIONS --}}
    <x-mary-toast />
</body>

</html>