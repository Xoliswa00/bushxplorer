<x-layouts.app>
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-stone-800 mb-6">Upcoming Hikes</h1>

        @forelse(\App\Models\Hike::where('is_published', true)->where('is_cancelled', false)->where('departs_at', '>', now())->orderBy('departs_at')->get() as $hike)
        <a href="{{ route('hikes.show', $hike) }}" class="block bg-white rounded-2xl shadow-md hover:shadow-lg transition-shadow p-5 mb-4">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-xl font-bold text-stone-800">{{ $hike->title }}</h2>
                    <p class="text-sm text-stone-500 mt-1">
                        {{ $hike->departs_at->format('D, d M Y') }} &bull; {{ $hike->location }}
                    </p>
                    <p class="text-sm text-stone-500 mt-1 capitalize">
                        {{ $hike->difficulty }} &bull; {{ $hike->distance_km ?? '?' }} km
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-lg font-bold text-green-700">R{{ number_format($hike->price, 2) }}</p>
                    <p class="text-xs text-stone-500 mt-1">{{ $hike->spots_remaining }} spots left</p>
                </div>
            </div>
        </a>
        @empty
        <p class="text-stone-500 text-center py-12">No upcoming hikes at the moment. Check back soon!</p>
        @endforelse
    </div>
</x-layouts.app>
