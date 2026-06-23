<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'BushXplorer') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-stone-100 font-sans antialiased">

    <nav class="bg-green-800 text-white px-6 py-4 flex items-center justify-between shadow">
        <a href="/" class="text-xl font-bold tracking-wide">BushXplorer</a>
        <div class="flex items-center gap-4 text-sm">
            <a href="{{ route('hikes.index') }}" class="hover:text-green-200 transition-colors">Hikes</a>
            @auth
                <livewire:notification-bell />
                <span class="text-green-300">{{ Auth::user()->name }}</span>
            @endauth
        </div>
    </nav>

    <main class="py-8 px-4">
        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>
