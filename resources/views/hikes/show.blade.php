<x-layouts.app>
    <div class="max-w-3xl mx-auto">

        <a href="{{ route('hikes.index') }}" class="text-sm text-green-700 hover:underline mb-4 inline-block">&larr; All hikes</a>

        <div class="bg-white rounded-2xl shadow-md p-6 mb-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h1 class="text-3xl font-bold text-stone-800">{{ $hike->title }}</h1>
                    <p class="text-stone-500 mt-2">{{ $hike->departs_at->format('D, d M Y \a\t H:i') }}</p>
                </div>
                <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold capitalize
                    {{ $hike->difficulty === 'easy' ? 'bg-green-100 text-green-800' :
                       ($hike->difficulty === 'moderate' ? 'bg-yellow-100 text-yellow-800' :
                       ($hike->difficulty === 'hard' ? 'bg-orange-100 text-orange-800' : 'bg-red-100 text-red-800')) }}">
                    {{ $hike->difficulty }}
                </span>
            </div>

            @if($hike->description)
            <p class="text-stone-600 leading-relaxed mb-6">{{ $hike->description }}</p>
            @endif

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm mb-6">
                <div class="bg-stone-50 rounded-xl p-3">
                    <p class="text-stone-500">Location</p>
                    <p class="font-semibold text-stone-800 mt-1">{{ $hike->location }}</p>
                </div>
                @if($hike->distance_km)
                <div class="bg-stone-50 rounded-xl p-3">
                    <p class="text-stone-500">Distance</p>
                    <p class="font-semibold text-stone-800 mt-1">{{ $hike->distance_km }} km</p>
                </div>
                @endif
                @if($hike->elevation_gain_m)
                <div class="bg-stone-50 rounded-xl p-3">
                    <p class="text-stone-500">Elevation gain</p>
                    <p class="font-semibold text-stone-800 mt-1">{{ $hike->elevation_gain_m }} m</p>
                </div>
                @endif
                <div class="bg-stone-50 rounded-xl p-3">
                    <p class="text-stone-500">Price</p>
                    <p class="font-semibold text-green-700 mt-1">R{{ number_format($hike->price, 2) }}</p>
                </div>
                <div class="bg-stone-50 rounded-xl p-3">
                    <p class="text-stone-500">Spots remaining</p>
                    <p class="font-semibold text-stone-800 mt-1">{{ $hike->spots_remaining }}</p>
                </div>
                <div class="bg-stone-50 rounded-xl p-3">
                    <p class="text-stone-500">Points awarded</p>
                    <p class="font-semibold text-stone-800 mt-1">+{{ $hike->points_awarded }} pts</p>
                </div>
                @if($hike->includes_transport)
                <div class="bg-amber-50 rounded-xl p-3 border border-amber-200">
                    <p class="text-amber-700">Transport available</p>
                    <p class="font-semibold text-amber-800 mt-1">+ R{{ number_format($hike->transport_fee, 2) }} p/p</p>
                </div>
                @endif
            </div>

            {{-- Pickup points preview --}}
            @if($hike->includes_transport && $hike->pickupPoints->isNotEmpty())
            <div class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-xl">
                <h3 class="text-sm font-semibold text-amber-900 mb-2">Pick-up Points</h3>
                <ul class="space-y-1">
                    @foreach($hike->pickupPoints as $point)
                    <li class="flex justify-between text-sm">
                        <span class="text-stone-700">{{ $point->name }}</span>
                        <span class="text-stone-500">{{ \Carbon\Carbon::parse($point->departure_time)->format('H:i') }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            @auth
                @if($hike->spots_remaining > 0)
                    <a href="{{ route('booking.form', $hike->id) }}"
                        class="block text-center w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-xl transition-colors">
                        Book My Spot &rarr;
                    </a>
                @else
                    <button disabled class="block w-full bg-stone-300 text-stone-500 font-bold py-3 px-6 rounded-xl cursor-not-allowed">
                        Fully Booked
                    </button>
                @endif
            @else
                <a href="{{ route('login') }}" class="block text-center w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-xl transition-colors">
                    Log in to Book &rarr;
                </a>
            @endauth
        </div>

        {{-- Gallery section --}}
        <div class="mb-6">
            <h2 class="text-xl font-bold text-stone-800 mb-4">Gallery</h2>

            @auth
            <div class="mb-4">
                <livewire:gallery.gallery-upload :hike-id="$hike->id" />
            </div>
            @endauth

            <livewire:gallery.gallery-viewer :hike-id="$hike->id" />
        </div>

    </div>
</x-layouts.app>
