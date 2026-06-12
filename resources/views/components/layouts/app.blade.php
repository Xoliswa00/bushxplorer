<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'BushXplorer') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        body { font-family: 'Inter', sans-serif; }
        .brand-name { font-family: 'Cormorant Garamond', serif; }

        /* Nav link active indicator */
        .nav-link { position: relative; }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -4px; left: 0; right: 0;
            height: 2px;
            background: #c9a84c;
            border-radius: 1px;
            transform: scaleX(0);
            transition: transform .2s ease;
        }
        .nav-link:hover::after,
        .nav-link.active::after { transform: scaleX(1); }

        /* Global scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f5f0e8; }
        ::-webkit-scrollbar-thumb { background: #c5b99a; border-radius: 3px; }
    </style>
</head>
<body class="min-h-screen antialiased" style="background: #f2ece2;">

    {{-- ════════════════════════════════════════════════════ --}}
    {{-- NAVIGATION                                           --}}
    {{-- ════════════════════════════════════════════════════ --}}
    <nav style="background: #0b1c0f;" class="relative z-40">

        {{-- Gold topline --}}
        <div style="height:3px; background: linear-gradient(90deg, transparent, #c9a84c, #e8d08a, #c9a84c, transparent);"></div>

        <div class="max-w-7xl mx-auto px-6 py-3.5 flex items-center justify-between">

            {{-- Brand --}}
            <a href="{{ route('hikes.index') }}" class="flex items-center gap-2.5 group">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-sm flex-shrink-0 transition-all group-hover:scale-105"
                    style="border: 1px solid rgba(201,168,76,0.4); background: rgba(201,168,76,0.1);">
                    🌿
                </div>
                <div class="leading-none">
                    <span class="brand-name text-xl font-bold tracking-wide" style="color: #c9a84c;">BushXplorer</span>
                    <span class="block text-[9px] uppercase tracking-[0.25em] font-medium" style="color: #3d5c43; margin-top: -1px;">Adventures</span>
                </div>
            </a>

            {{-- Centre nav links --}}
            <div class="hidden md:flex items-center gap-6">
                <a href="{{ route('hikes.index') }}"
                    class="nav-link text-sm font-medium tracking-wide transition-colors {{ request()->routeIs('hikes.*') ? 'active' : '' }}"
                    style="color: {{ request()->routeIs('hikes.*') ? '#c9a84c' : '#8aad90' }};">
                    Hikes
                </a>
                @auth
                <a href="{{ route('planner.index') }}"
                    class="nav-link text-sm font-medium tracking-wide transition-colors {{ request()->routeIs('planner.*') ? 'active' : '' }}"
                    style="color: {{ request()->routeIs('planner.*') ? '#c9a84c' : '#8aad90' }};">
                    Planner
                </a>
                @endauth
            </div>

            {{-- Right: user --}}
            <div class="flex items-center gap-4">
                @auth
                <livewire:notification-bell />
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0"
                        style="background: rgba(201,168,76,0.15); border: 1px solid rgba(201,168,76,0.3); color: #c9a84c;">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <span class="text-sm font-medium hidden sm:block" style="color: #8aad90;">{{ Auth::user()->name }}</span>
                </div>
                @else
                <a href="{{ route('login') }}" class="text-sm font-semibold px-4 py-1.5 rounded-lg transition-colors"
                    style="color: #c9a84c; border: 1px solid rgba(201,168,76,0.3);">
                    Sign in
                </a>
                @endauth
            </div>

        </div>
    </nav>

    {{-- ════════════════════════════════════════════════════ --}}
    {{-- MAIN CONTENT                                         --}}
    {{-- ════════════════════════════════════════════════════ --}}
    <main class="py-8 px-4 md:px-6">
        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>
