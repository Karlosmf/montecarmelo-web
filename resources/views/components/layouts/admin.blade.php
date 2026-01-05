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

<body class="min-h-screen font-sans antialiased bg-base-200/50">

    {{-- TOP NAVBAR (Mobile only) --}}
    <x-mary-nav sticky class="lg:hidden">
        <x-slot:brand>
            <div class="ml-5 text-xl font-bold text-primary">MC ADMIN</div>
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
        <x-slot:sidebar drawer="main-drawer" collapsible class="bg-base-100">

            {{-- BRAND LOGO --}}
            <div class="p-6 pt-10 hidden lg:block">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center text-primary">
                        <x-mary-icon name="o-sparkles" class="w-6 h-6" />
                    </div>
                    <div>
                        <div class="font-bold text-lg leading-none uppercase">
                            Monte Carmelo
                        </div>
                        <div class="text-xs text-base-content/60 uppercase tracking-widest font-bold">Control
                            Panel</div>
                    </div>
                </div>
            </div>

            {{-- MENU --}}
            <x-mary-menu activate-by-route>
                {{-- User Info (Sidebar version) --}}
                @if($user = auth()->user())
                    <div class="px-2 py-2 mb-6">
                        <div class="flex items-center justify-between p-2 rounded-xl bg-base-200">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-semibold truncate max-w-[100px]">{{ $user->name }}</span>
                            </div>
                            <x-mary-button icon="o-power" link="/logout" class="btn-ghost btn-xs text-error"
                                tooltip="Salir" />
                        </div>
                    </div>
                @endif

                <x-mary-menu-item title="Estadísticas" icon="o-chart-bar" link="/admin/dashboard" />
                <x-mary-menu-item title="Pedidos / Leads" icon="o-inbox-stack" link="/admin/orders">
                    <x-slot:actions>
                        @php
                            $pendingCount = \App\Models\Order::where('status', 'pending')->count();
                        @endphp
                        @if($pendingCount > 0)
                            <div class="badge badge-sm bg-error text-white border-0">
                                {{ $pendingCount }}
                            </div>
                        @endif
                    </x-slot:actions>
                </x-mary-menu-item>

                <x-mary-menu-separator title="Catálogo" />

                <x-mary-menu-item title="Productos" icon="o-cube" link="/admin/products" />
                <x-mary-menu-item title="Categorías" icon="o-hashtag" link="/admin/categories" />
                <x-mary-menu-item title="Etiquetas" icon="o-tag" link="/admin/tags" />

                <x-mary-menu-separator title="Sitio Web" />

                <x-mary-menu-item title="Hero Slider" icon="o-photo" link="/admin/slides" />

                <x-mary-menu-separator title="Sistema" />

                <x-mary-menu-item title="Usuarios" icon="o-users" link="/admin/users">
                    <x-slot:actions>
                        @php
                            $pendingUsers = \App\Models\User::pending()->count();
                        @endphp
                        @if($pendingUsers > 0)
                            <div class="badge badge-sm bg-error text-white border-0">
                                {{ $pendingUsers }}
                            </div>
                        @endif
                    </x-slot:actions>
                </x-mary-menu-item>
                <x-mary-menu-item title="Ir al Sitio" icon="o-arrow-top-right-on-square" link="/" />

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