<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $plan->title }} — Poster</title>
    @vite(['resources/css/app.css'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap');
        body { font-family: 'Inter', sans-serif; background: #f5f5f4; }
        @media print {
            body { background: white; }
            .no-print { display: none !important; }
            .poster-card { box-shadow: none !important; margin: 0; max-width: 100%; }
        }
    </style>
</head>
<body class="min-h-screen py-8 px-4">

    {{-- Action bar --}}
    <div class="no-print max-w-2xl mx-auto mb-4 flex items-center justify-between">
        <a href="{{ route('planner.index') }}" class="text-sm text-stone-500 hover:text-stone-700">&larr; Back to plans</a>
        <div class="flex gap-2">
            <button onclick="window.print()"
                class="px-4 py-2 bg-stone-100 hover:bg-stone-200 text-stone-700 text-sm font-semibold rounded-xl transition-colors">
                🖨 Print
            </button>
            <a href="{{ route('planner.poster.download', $plan->id) }}"
                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-xl transition-colors">
                ⬇ Download PDF
            </a>
        </div>
    </div>

    {{-- Poster card --}}
    <div class="poster-card max-w-2xl mx-auto rounded-3xl overflow-hidden shadow-2xl" style="font-family: 'Inter', sans-serif;">

        {{-- Header band --}}
        <div class="px-10 pt-12 pb-8 text-white relative overflow-hidden"
            style="background-color: {{ $plan->cover_color }};">

            {{-- Decorative circles --}}
            <div class="absolute -top-16 -right-16 w-56 h-56 rounded-full opacity-10 bg-white"></div>
            <div class="absolute -bottom-10 -left-10 w-40 h-40 rounded-full opacity-10 bg-white"></div>

            <p class="text-xs font-bold uppercase tracking-[0.2em] opacity-70 mb-3">BushXplorer Presents</p>
            <h1 class="text-4xl font-black leading-tight relative">{{ $plan->title }}</h1>
            @if($plan->tagline)
            <p class="mt-3 text-base opacity-80 font-medium italic">{{ $plan->tagline }}</p>
            @endif

            {{-- Difficulty pill --}}
            @if($plan->difficulty)
            <span class="inline-block mt-4 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide bg-white/20 backdrop-blur">
                {{ $plan->difficulty }}
            </span>
            @endif
            @if($plan->type !== 'hike')
            <span class="inline-block mt-4 ml-2 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide bg-white/20 backdrop-blur capitalize">
                {{ str_replace('_', ' ', $plan->type) }}
            </span>
            @endif
        </div>

        {{-- Body --}}
        <div class="bg-white px-10 py-8 space-y-6">

            {{-- Date / Location row --}}
            <div class="grid grid-cols-2 gap-4">
                <div class="flex gap-3 items-start">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 text-white text-sm"
                        style="background-color: {{ $plan->cover_color }}">📅</div>
                    <div>
                        <p class="text-xs font-semibold text-stone-400 uppercase tracking-wide">Date</p>
                        <p class="font-bold text-stone-800 mt-0.5">
                            {{ $plan->departs_at?->format('D, d M Y') ?? 'TBC' }}
                        </p>
                        @if($plan->departs_at)
                        <p class="text-sm text-stone-500">{{ $plan->departs_at->format('H:i') }}
                            @if($plan->returns_at) – {{ $plan->returns_at->format('H:i') }} @endif
                        </p>
                        @endif
                    </div>
                </div>
                <div class="flex gap-3 items-start">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 text-white text-sm"
                        style="background-color: {{ $plan->cover_color }}">📍</div>
                    <div>
                        <p class="text-xs font-semibold text-stone-400 uppercase tracking-wide">Location</p>
                        <p class="font-bold text-stone-800 mt-0.5">{{ $plan->location ?? 'TBC' }}</p>
                        @if($plan->trail_name)
                        <p class="text-sm text-stone-500">{{ $plan->trail_name }}</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Description --}}
            @if($plan->description)
            <p class="text-stone-600 leading-relaxed text-sm border-t border-stone-100 pt-5">{{ $plan->description }}</p>
            @endif

            {{-- Specs row --}}
            <div class="grid grid-cols-3 gap-3 pt-2">
                <div class="text-center p-3 rounded-xl bg-stone-50">
                    <p class="text-xs text-stone-400 uppercase tracking-wide font-semibold">Spots</p>
                    <p class="text-xl font-black text-stone-800 mt-1">{{ $plan->max_capacity ?? '—' }}</p>
                </div>
                <div class="text-center p-3 rounded-2xl" style="background-color: {{ $plan->cover_color }}1a;">
                    <p class="text-xs text-stone-400 uppercase tracking-wide font-semibold">Price</p>
                    <p class="text-xl font-black mt-1" style="color: {{ $plan->cover_color }}">
                        R{{ number_format($plan->price ?? $plan->suggestedPrice(), 0) }}
                    </p>
                </div>
                <div class="text-center p-3 rounded-xl bg-stone-50">
                    <p class="text-xs text-stone-400 uppercase tracking-wide font-semibold">Points</p>
                    <p class="text-xl font-black text-stone-800 mt-1">+{{ $plan->points_awarded }}</p>
                </div>
            </div>

            {{-- Transport --}}
            @if($plan->includes_transport && !empty($plan->transport_pickup_points))
            <div class="p-4 rounded-2xl border" style="background-color: #fffbeb; border-color: #fde68a;">
                <p class="text-xs font-bold uppercase tracking-wide text-amber-700 mb-2">🚌 Transport Available — R{{ number_format($plan->transport_fee, 0) }}/person</p>
                <div class="grid grid-cols-2 gap-1">
                    @foreach($plan->transport_pickup_points as $pp)
                    <p class="text-xs text-stone-600">📌 {{ $pp['name'] }} — {{ $pp['departure_time'] }}</p>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Meeting point --}}
            @if($plan->meeting_point)
            <div class="flex gap-2 items-center text-sm text-stone-600 bg-stone-50 rounded-xl px-4 py-3">
                <span>🗺</span>
                <span><strong>Meeting point:</strong> {{ $plan->meeting_point }}</span>
            </div>
            @endif

        </div>

        {{-- Footer --}}
        <div class="px-10 py-5 flex items-center justify-between text-white text-sm"
            style="background-color: {{ $plan->cover_color }};">
            <p class="font-bold tracking-wide">BushXplorer</p>
            @if($plan->publishedHike)
            <p class="opacity-70 text-xs">Book at: {{ config('app.url') }}/hikes/{{ $plan->publishedHike->slug }}</p>
            @else
            <p class="opacity-70 text-xs">{{ config('app.url') }}</p>
            @endif
        </div>

    </div>

    {{-- Share nudge --}}
    <div class="no-print max-w-2xl mx-auto mt-6 text-center text-sm text-stone-400">
        Share this link or download the PDF and post it to your socials.
    </div>

</body>
</html>
