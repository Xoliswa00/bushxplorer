<x-layouts.app>
<div class="max-w-5xl mx-auto space-y-6">

    <div>
        <p class="text-[10px] font-bold uppercase tracking-[0.2em] mb-1" style="color:#c9a84c;">Admin</p>
        <h1 class="text-2xl font-bold text-stone-900" style="font-family:'Cormorant Garamond',serif;">Hike Management</h1>
    </div>

    @php
        $hikes = \App\Models\Hike::withCount(['bookings as active_bookings' => function($q) {
            $q->whereNotIn('status', ['cancelled','refunded']);
        }])->orderByDesc('departs_at')->get();
    @endphp

    @if($hikes->isEmpty())
    <div class="bg-white rounded-2xl border border-dashed border-stone-200 p-12 text-center text-stone-400">
        <p>No hikes yet. Create one in the Planner.</p>
        <a href="{{ route('planner.create') }}" class="mt-4 inline-block bg-green-700 text-white text-sm font-semibold px-6 py-2.5 rounded-xl">Plan a Hike →</a>
    </div>
    @else
    <div class="bg-white rounded-2xl border border-stone-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr style="background:#f9f7f3; border-bottom:1px solid #e8e0d0;">
                    <th class="text-left px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-stone-400">Hike</th>
                    <th class="text-left px-4 py-3 text-[10px] font-bold uppercase tracking-wider text-stone-400">Date</th>
                    <th class="text-right px-4 py-3 text-[10px] font-bold uppercase tracking-wider text-stone-400">Bookings</th>
                    <th class="text-right px-4 py-3 text-[10px] font-bold uppercase tracking-wider text-stone-400">Capacity</th>
                    <th class="text-right px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-stone-400">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @foreach($hikes as $hike)
                @php $pct = $hike->max_capacity > 0 ? round($hike->active_bookings / $hike->max_capacity * 100) : 0; @endphp
                <tr class="hover:bg-stone-50/60 transition-colors">
                    <td class="px-5 py-4">
                        <p class="font-semibold text-stone-800">{{ $hike->title }}</p>
                        <p class="text-xs text-stone-400 mt-0.5 capitalize">{{ $hike->difficulty }}
                            @if($hike->is_overnight) · {{ $hike->nights }}n overnight @endif
                        </p>
                    </td>
                    <td class="px-4 py-4 text-stone-600">
                        {{ $hike->departs_at->format('D, d M Y') }}
                    </td>
                    <td class="px-4 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <span class="font-bold text-stone-800">{{ $hike->active_bookings }}</span>
                            <div class="w-20 h-1.5 rounded-full bg-stone-100">
                                <div class="h-1.5 rounded-full {{ $pct >= 90 ? 'bg-red-500' : ($pct >= 70 ? 'bg-amber-500' : 'bg-green-600') }}"
                                    style="width:{{ $pct }}%"></div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-4 text-right text-stone-500">
                        {{ $hike->max_capacity }}
                    </td>
                    <td class="px-5 py-4 text-right">
                        <a href="{{ route('admin.hike.bookings', $hike->id) }}"
                            class="text-xs font-semibold text-green-700 hover:text-green-900 transition-colors">
                            Manage bookings →
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

</div>
</x-layouts.app>
