<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="flex items-center justify-center min-h-screen font-sans antialiased bg-base-200/50 dark:bg-base-200 bg-cover bg-center"
  style="background-image: url({{ asset('storage/imgs/background.webp') }})" data-theme="light">
  
    <div class="w-full max-w-md">
      @if(isset($slot))
        {{ $slot }}
      @endif
    </div>

  <x-mary-toast />
  @livewireScripts
  @stack('scripts')
</body>

</html>