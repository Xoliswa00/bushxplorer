<x-layouts.app>
<div class="max-w-5xl mx-auto">

    {{-- Page header --}}
    <div class="mb-8">
        <p class="text-[10px] font-bold uppercase tracking-[0.2em] mb-1" style="color: #c9a84c;">BushXplorer</p>
        <h1 class="text-4xl font-bold tracking-tight text-stone-900" style="font-family: 'Cormorant Garamond', serif;">
            Upcoming Adventures
        </h1>
        <p class="text-stone-500 text-sm mt-1.5">Find your next trail. Book your spot. Earn your points.</p>
    </div>

    @php
        $hikes = \App\Models\Hike::where('is_published', true)
            ->where('is_cancelled', false)
            ->where('departs_at', '>', now())
            ->orderBy('departs_at')
            ->get();

        $difficultyStyle = [
            'easy'     => ['🟢', 'bg-green-50 text-green-700 border-green-200'],
            'moderate' => ['🟡', 'bg-amber-50 text-amber-700 border-amber-200'],
            'hard'     => ['🟠', 'bg-orange-50 text-orange-700 border-orange-200'],
            'extreme'  => ['🔴', 'bg-red-50 text-red-700 border-red-200'],
        ];
    @endphp

    @forelse($hikes as $hike)
    @php
        [$diffEmoji, $diffClass] = $difficultyStyle[$hike->difficulty] ?? ['⚪', 'bg-stone-50 text-stone-600 border-stone-200'];
        $urgency = $hike->spots_remaining <= 5 && $hike->spots_remaining > 0;
        $soldOut = $hike->spots_remaining === 0;
        $accentColors = ['#166534','#1e3a5f','#7c2d12','#134e4a','#4a044e','#0c4a6e','#713f12'];
        $accentColor  = $accentColors[$hike->id % count($accentColors)];
    @endphp

    <a href="{{ route('hikes.show', $hike) }}"
        class="group block bg-white rounded-2xl border border-stone-100 shadow-sm hover:shadow-md overflow-hidden mb-4 transition-shadow">

        {{-- Accent bar --}}
        <div class="h-1.5" style="background: linear-gradient(90deg, {{ $accentColor }}, transparent);"></div>

        <div class="p-5 flex items-start gap-4">

            {{-- Date block --}}
            <div class="flex-shrink-0 w-14 text-center px-1 py-2 rounded-xl border"
                style="background: {{ $accentColor }}12; border-color: {{ $accentColor }}30;">
                <p class="text-[10px] font-bold uppercase tracking-wide" style="color: {{ $accentColor }};">
                    {{ $hike->departs_at->format('M') }}
                </p>
                <p class="text-2xl font-black leading-tight" style="color: {{ $accentColor }};">
                    {{ $hike->departs_at->format('d') }}
                </p>
                <p class="text-[9px] font-medium text-stone-400">
                    {{ $hike->departs_at->format('D') }}
                </p>
            </div>

            {{-- Main content --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <h2 class="text-lg font-bold text-stone-900 truncate group-hover:text-green-800 transition-colors leading-snug"
                            style="font-family: 'Cormorant Garamond', serif;">
                            {{ $hike->title }}
                        </h2>
                        @if($hike->description)
                        <p class="text-xs text-stone-400 mt-0.5 line-clamp-1">{{ $hike->description }}</p>
                        @endif
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="text-xl font-bold" style="color: #0f2117;">R{{ number_format($hike->price, 0) }}</p>
                        @if($hike->includes_transport)
                        <p class="text-[10px] text-amber-600 font-medium mt-0.5">+ transport</p>
                        @endif
                    </div>
                </div>

                {{-- Meta chips --}}
                <div class="flex flex-wrap items-center gap-1.5 mt-2.5">
                    <span class="inline-flex items-center gap-1 text-[10px] font-medium px-2.5 py-1 bg-stone-50 rounded-full text-stone-600 border border-stone-100">
                        📍 {{ $hike->location }}
                    </span>
                    <span class="inline-flex items-center gap-1 text-[10px] font-semibold px-2.5 py-1 rounded-full border {{ $diffClass }}">
                        {{ $diffEmoji }} {{ ucfirst($hike->difficulty) }}
                    </span>
                    @if($hike->distance_km)
                    <span class="inline-flex items-center gap-1 text-[10px] font-medium px-2.5 py-1 bg-stone-50 rounded-full text-stone-600 border border-stone-100">
                        🥾 {{ $hike->distance_km }} km
                    </span>
                    @endif
                    <span class="inline-flex items-center gap-1 text-[10px] font-medium px-2.5 py-1 bg-stone-50 rounded-full text-stone-600 border border-stone-100">
                        ⭐ +{{ $hike->points_awarded }} pts
                    </span>
                    @if($soldOut)
                    <span class="inline-flex items-center text-[10px] font-bold px-2.5 py-1 bg-red-50 rounded-full text-red-600 border border-red-100">
                        Sold out
                    </span>
                    @elseif($urgency)
                    <span class="inline-flex items-center text-[10px] font-bold px-2.5 py-1 bg-amber-50 rounded-full text-amber-700 border border-amber-100 animate-pulse">
                        🔥 Only {{ $hike->spots_remaining }} left
                    </span>
                    @else
                    <span class="inline-flex items-center text-[10px] font-medium px-2.5 py-1 bg-stone-50 rounded-full text-stone-500 border border-stone-100">
                        {{ $hike->spots_remaining }} spots left
                    </span>
                    @endif
                </div>
            </div>

            {{-- Arrow --}}
            <div class="flex-shrink-0 self-center w-8 h-8 rounded-full flex items-center justify-center transition-all text-stone-300 group-hover:text-white group-hover:bg-green-700"
                style="">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </div>

        </div>
    </a>

    @empty
    <div class="text-center py-20 rounded-2xl bg-white border border-stone-100">
        <div class="w-14 h-14 rounded-2xl mx-auto mb-4 flex items-center justify-center text-2xl bg-stone-50">🌄</div>
        <p class="text-lg font-bold text-stone-700" style="font-family: 'Cormorant Garamond', serif;">No hikes scheduled yet</p>
        <p class="text-sm text-stone-400 mt-1">Check back soon — adventures are being planned right now.</p>
    </div>
    @endforelse

</div>
</x-layouts.app>
