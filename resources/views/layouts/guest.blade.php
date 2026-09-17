<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'MOMAI PLYWOOD') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-gray-900 min-h-screen bg-cover bg-center bg-fixed relative selection:bg-botanical-700 selection:text-white"
          style="background-image: url('{{ asset('images/botanical-bg.jpg') }}');">
        <!-- Ambient depth overlay with soft blur -->
        <div class="fixed inset-0 bg-gradient-to-tr from-black/25 via-emerald-950/10 to-white/10 backdrop-blur-[4px] pointer-events-none z-0"></div>

        <main class="relative z-10 min-h-screen flex items-center justify-center p-4 sm:p-6 lg:p-8">
            {{ $slot }}
        </main>
    </body>
</html>
