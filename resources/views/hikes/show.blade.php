<x-layouts.app>
<div class="max-w-4xl mx-auto space-y-6">

    <a href="{{ route('hikes.index') }}"
        class="inline-flex items-center gap-1.5 text-sm font-medium text-stone-500 hover:text-stone-800 transition-colors">
        &#8592; All hikes
    </a>

    {{-- ── HERO CARD ── --}}
    <div class="rounded-2xl overflow-hidden" style="background:#0d1e13;">
        <div style="height:3px;background:linear-gradient(90deg,transparent,#c9a84c,#e8d08a,#c9a84c,transparent);"></div>
        <div class="px-8 py-7 flex items-start justify-between gap-6 flex-wrap">
            <div>
                <div class="flex items-center gap-2 mb-2 flex-wrap">
                    <span class="text-[10px] font-bold uppercase tracking-[0.2em] px-2.5 py-1 rounded-full capitalize
                        {{ $hike->difficulty === 'easy' ? 'bg-green-900/60 text-green-300' :
                           ($hike->difficulty === 'moderate' ? 'bg-yellow-900/60 text-yellow-300' :
                           ($hike->difficulty === 'hard' ? 'bg-orange-900/60 text-orange-300' : 'bg-red-900/60 text-red-300')) }}">
                        {{ $hike->difficulty }}
                    </span>
                    @if($hike->is_overnight)
                    <span class="text-[10px] font-bold uppercase tracking-[0.2em] px-2.5 py-1 rounded-full bg-indigo-900/60 text-indigo-300">
                        {{ $hike->nights }}-night trip
                    </span>
                    @endif
                </div>
                <h1 class="text-3xl font-bold leading-snug" style="font-family:'Cormorant Garamond',serif; color:#f5f0e8;">
                    {{ $hike->title }}
                </h1>
                <p class="text-sm mt-2" style="color:#6a9070;">
                    {{ $hike->departs_at->format('D, d M Y') }}
                    @if($hike->departs_at)· {{ $hike->departs_at->format('H:i') }}@endif
                    @if($hike->returns_at)– {{ $hike->returns_at->format('D, d M Y') }}@endif
                </p>
            </div>

            {{-- Price block --}}
            <div class="text-right flex-shrink-0">
                <p class="text-[10px] uppercase tracking-widest font-semibold mb-1" style="color:#4a6a52;">from</p>
                <p class="text-3xl font-bold" style="color:#c9a84c;">R{{ number_format($hike->price, 0) }}</p>
                <p class="text-xs mt-0.5" style="color:#4a6a52;">per person</p>
                @if($hike->is_overnight)
                <p class="text-xs mt-1" style="color:#6a8d78;">
                    + R{{ number_format($hike->accommodation_cost_per_person, 0) }}/night accom
                </p>
                @endif
                @if($hike->includes_transport)
                <p class="text-xs mt-0.5 text-amber-400">+ R{{ number_format($hike->transport_fee, 0) }} transport</p>
                @endif
            </div>
        </div>
    </div>

    {{-- ── SPECS GRID ── --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
        <div class="bg-white rounded-2xl border border-stone-100 p-4">
            <p class="text-[10px] uppercase tracking-widest text-stone-400 font-bold mb-1.5">📍 Location</p>
            <p class="font-semibold text-stone-800 text-sm">{{ $hike->location }}</p>
            @if($hike->trail_name)<p class="text-xs text-stone-500 mt-0.5">{{ $hike->trail_name }}</p>@endif
        </div>
        @if($hike->distance_km)
        <div class="bg-white rounded-2xl border border-stone-100 p-4">
            <p class="text-[10px] uppercase tracking-widest text-stone-400 font-bold mb-1.5">📏 Distance</p>
            <p class="font-semibold text-stone-800 text-sm">{{ $hike->distance_km }} km</p>
        </div>
        @endif
        @if($hike->elevation_gain_m)
        <div class="bg-white rounded-2xl border border-stone-100 p-4">
            <p class="text-[10px] uppercase tracking-widest text-stone-400 font-bold mb-1.5">⛰ Elevation</p>
            <p class="font-semibold text-stone-800 text-sm">{{ $hike->elevation_gain_m }} m gain</p>
        </div>
        @endif
        <div class="bg-white rounded-2xl border border-stone-100 p-4">
            <p class="text-[10px] uppercase tracking-widest text-stone-400 font-bold mb-1.5">👥 Spots</p>
            <p class="font-semibold text-sm {{ $hike->spots_remaining <= 5 ? 'text-red-600' : 'text-stone-800' }}">
                {{ $hike->spots_remaining }} remaining
            </p>
            <p class="text-xs text-stone-400 mt-0.5">of {{ $hike->max_capacity }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-stone-100 p-4">
            <p class="text-[10px] uppercase tracking-widest text-stone-400 font-bold mb-1.5">🏆 Points</p>
            <p class="font-semibold text-green-700 text-sm">+{{ $hike->points_awarded }} pts</p>
        </div>
        @if($hike->meeting_point)
        <div class="bg-white rounded-2xl border border-stone-100 p-4">
            <p class="text-[10px] uppercase tracking-widest text-stone-400 font-bold mb-1.5">🚩 Meeting point</p>
            <p class="font-semibold text-stone-800 text-sm">{{ $hike->meeting_point }}</p>
        </div>
        @endif
    </div>

    {{-- ── DESCRIPTION ── --}}
    @if($hike->description)
    <div class="bg-white rounded-2xl border border-stone-100 p-6">
        <h2 class="text-base font-bold text-stone-800 mb-3">About this hike</h2>
        <p class="text-stone-600 leading-relaxed text-sm">{{ $hike->description }}</p>
    </div>
    @endif

    {{-- ── OVERNIGHT ACCOMMODATION ── --}}
    @if($hike->is_overnight)
    <div class="rounded-2xl border border-indigo-100 overflow-hidden" style="background:rgba(99,80,180,0.04);">
        <div class="px-6 py-4 flex items-start gap-4 flex-wrap border-b border-indigo-100">
            <div class="flex-1">
                <p class="text-[10px] uppercase tracking-[0.2em] font-bold text-indigo-400 mb-1">🛏 Overnight Accommodation</p>
                <h3 class="text-lg font-bold text-stone-800">{{ $hike->accommodation_name ?? 'Lodge / Camp' }}</h3>
                <p class="text-sm text-stone-500 mt-0.5">
                    {{ $hike->nights }} night{{ $hike->nights > 1 ? 's' : '' }}
                    &bull; R{{ number_format($hike->accommodation_cost_per_person, 0) }}/person/night
                    &bull; <span class="font-semibold text-indigo-600">R{{ number_format($hike->total_accommodation_cost, 0) }} total/person</span>
                </p>
            </div>
        </div>
        @if($hike->what_is_included || $hike->what_to_bring)
        <div class="px-6 py-4 grid grid-cols-1 sm:grid-cols-2 gap-5">
            @if($hike->what_is_included)
            <div>
                <p class="text-[10px] uppercase tracking-widest font-bold text-indigo-400 mb-2">What's included</p>
                <p class="text-sm text-stone-600 leading-relaxed">{{ $hike->what_is_included }}</p>
            </div>
            @endif
            @if($hike->what_to_bring)
            <div>
                <p class="text-[10px] uppercase tracking-widest font-bold text-stone-400 mb-2">What to bring</p>
                <p class="text-sm text-stone-600 leading-relaxed">{{ $hike->what_to_bring }}</p>
            </div>
            @endif
        </div>
        @endif
    </div>
    @endif

    {{-- ── TRANSPORT ── --}}
    @if($hike->includes_transport && $hike->pickupPoints->isNotEmpty())
    <div class="bg-white rounded-2xl border border-amber-100 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-bold text-stone-800">🚌 Transport available</h2>
            <span class="text-sm font-bold text-amber-700">+ R{{ number_format($hike->transport_fee, 0) }}/person</span>
        </div>
        <div class="space-y-2">
            @foreach($hike->pickupPoints as $point)
            <div class="flex items-center justify-between p-3 rounded-xl bg-amber-50 border border-amber-100">
                <div>
                    <p class="text-sm font-semibold text-stone-800">{{ $point->name }}</p>
                    @if($point->address)<p class="text-xs text-stone-500">{{ $point->address }}</p>@endif
                </div>
                <div class="text-right text-xs text-stone-500">
                    <p>Departs {{ \Carbon\Carbon::parse($point->departure_time)->format('H:i') }}</p>
                    <p class="{{ $point->seats_remaining > 0 ? 'text-green-700' : 'text-red-600' }}">
                        {{ $point->seats_remaining > 0 ? $point->seats_remaining . ' seats' : 'Full' }}
                    </p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ── BOOKING CTA ── --}}
    <div class="bg-white rounded-2xl border border-stone-100 p-6 text-center">
        @auth
            @if($hike->spots_remaining > 0)
            <a href="{{ route('booking.form', $hike->id) }}"
                class="inline-block w-full py-4 rounded-xl font-bold text-base text-white transition-all
                       bg-green-700 hover:bg-green-800 active:scale-[0.99] shadow-sm shadow-green-900/20">
                Book My Spot &rarr;
            </a>
            <p class="text-xs text-stone-400 mt-2">{{ $hike->spots_remaining }} spots remaining</p>
            @else
            <button disabled class="w-full py-4 rounded-xl font-bold text-base bg-stone-200 text-stone-500 cursor-not-allowed">
                Fully Booked
            </button>
            @endif
        @else
        <p class="text-sm text-stone-500 mb-4">Sign in to book your spot</p>
        <div class="flex gap-3 justify-center">
            <a href="{{ route('register') }}"
                class="px-8 py-3 rounded-xl font-bold text-sm text-white bg-green-700 hover:bg-green-800 transition-colors">
                Join BushXplorer
            </a>
            <a href="{{ route('login') }}"
                class="px-8 py-3 rounded-xl font-bold text-sm text-stone-700 bg-stone-100 hover:bg-stone-200 transition-colors">
                Sign in
            </a>
        </div>
        @endauth
    </div>

    {{-- ── GALLERY ── --}}
    @auth
    <div>
        <h2 class="text-lg font-bold text-stone-800 mb-4">Gallery</h2>
        <div class="mb-4">
            <livewire:gallery.gallery-upload :hike-id="$hike->id" />
        </div>
        <livewire:gallery.gallery-viewer :hike-id="$hike->id" />
    </div>
    @endauth

</div>
</x-layouts.app>
