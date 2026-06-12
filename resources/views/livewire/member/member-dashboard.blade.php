<div class="max-w-5xl mx-auto space-y-6">

    {{-- ── HEADER ── --}}
    <div class="rounded-2xl overflow-hidden" style="background: #0d1e13;">
        <div style="height:3px; background: linear-gradient(90deg,transparent,#c9a84c,#e8d08a,#c9a84c,transparent);"></div>
        <div class="px-8 py-6 flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-2xl font-bold flex-shrink-0"
                    style="background: rgba(201,168,76,0.15); border: 1px solid rgba(201,168,76,0.3); color: #c9a84c;">
                    {{ strtoupper(substr($this->member->first_name, 0, 1)) }}
                </div>
                <div>
                    <p class="text-xs uppercase tracking-[0.2em] font-semibold mb-0.5" style="color: #4a6a52;">Explorer Passport</p>
                    <h1 class="text-2xl font-bold" style="font-family:'Cormorant Garamond',serif; color:#f5f0e8;">
                        {{ $this->member->full_name }}
                    </h1>
                    <p class="text-xs font-mono mt-0.5" style="color: #5a7a60;">{{ $this->member->member_ref }}</p>
                </div>
            </div>

            {{-- Level badge --}}
            @if($this->member->explorerLevel)
            <div class="text-right">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl"
                    style="background: rgba(201,168,76,0.1); border: 1px solid rgba(201,168,76,0.25);">
                    <div class="w-3 h-3 rounded-full" style="background: {{ $this->member->explorerLevel->badge_color }}"></div>
                    <span class="text-sm font-bold" style="color: #c9a84c;">{{ $this->member->explorerLevel->name }}</span>
                </div>
                <p class="text-xs mt-1" style="color: #5a7a60;">{{ number_format($this->member->total_points) }} points earned</p>
            </div>
            @endif
        </div>

        {{-- Stats bar --}}
        <div class="grid grid-cols-3 border-t" style="border-color: rgba(201,168,76,0.1);">
            @php
                $stats = [
                    ['label' => 'Hikes Attended', 'value' => $this->member->hikes_attended],
                    ['label' => 'Total Points',   'value' => number_format($this->member->total_points)],
                    ['label' => 'Active Bookings','value' => $this->upcomingBookings->count()],
                ];
            @endphp
            @foreach($stats as $i => $stat)
            <div class="px-6 py-4 {{ $i < 2 ? 'border-r' : '' }}" style="{{ $i < 2 ? 'border-color:rgba(201,168,76,0.1)' : '' }}">
                <p class="text-2xl font-bold" style="color:#f5f0e8;">{{ $stat['value'] }}</p>
                <p class="text-[10px] uppercase tracking-widest mt-0.5" style="color:#4a6a52;">{{ $stat['label'] }}</p>
            </div>
            @endforeach
        </div>

        {{-- Level progress bar --}}
        @if($this->nextLevel)
        <div class="px-8 py-4 border-t" style="border-color: rgba(201,168,76,0.1);">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-[10px] uppercase tracking-widest font-semibold" style="color:#4a6a52;">
                    Progress to {{ $this->nextLevel->name }}
                </span>
                <span class="text-[10px]" style="color:#4a6a52;">
                    {{ $this->member->total_points }} / {{ $this->nextLevel->min_points }} pts
                </span>
            </div>
            <div class="h-1.5 rounded-full" style="background: rgba(255,255,255,0.08);">
                <div class="h-1.5 rounded-full transition-all" style="width: {{ $this->levelProgress }}%; background: linear-gradient(90deg,#c9a84c,#e8d08a);"></div>
            </div>
        </div>
        @endif
    </div>

    {{-- ── UPCOMING BOOKINGS ── --}}
    <div>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-stone-800">Upcoming Trips</h2>
            <a href="{{ route('hikes.index') }}"
                class="text-xs font-semibold text-green-700 hover:text-green-800 transition-colors">
                Browse all hikes &rarr;
            </a>
        </div>

        @forelse($this->upcomingBookings as $booking)
        @php
            $statusColor = match($booking->status) {
                'confirmed'        => ['bg' => 'bg-green-100',  'text' => 'text-green-800',  'label' => 'Confirmed'],
                'payment_uploaded' => ['bg' => 'bg-blue-100',   'text' => 'text-blue-800',   'label' => 'Payment Under Review'],
                'payment_verified' => ['bg' => 'bg-blue-100',   'text' => 'text-blue-800',   'label' => 'Payment Verified'],
                'pending_payment'  => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'label' => 'Awaiting Payment'],
                default            => ['bg' => 'bg-stone-100',  'text' => 'text-stone-600',  'label' => ucfirst($booking->status)],
            };
        @endphp
        <div class="bg-white rounded-2xl border border-stone-100 p-5 flex items-center gap-5 hover:border-stone-200 transition-colors">
            {{-- Date block --}}
            <div class="flex-shrink-0 w-14 text-center rounded-xl p-2" style="background:#0d1e13;">
                <p class="text-[10px] uppercase tracking-wide font-bold" style="color:#c9a84c;">
                    {{ $booking->hike->departs_at->format('M') }}
                </p>
                <p class="text-2xl font-bold leading-none mt-0.5" style="color:#f5f0e8;">
                    {{ $booking->hike->departs_at->format('d') }}
                </p>
                <p class="text-[9px] uppercase tracking-wide mt-0.5" style="color:#4a6a52;">
                    {{ $booking->hike->departs_at->format('D') }}
                </p>
            </div>

            {{-- Info --}}
            <div class="flex-1 min-w-0">
                <p class="font-bold text-stone-800 truncate">{{ $booking->hike->title }}</p>
                <p class="text-xs text-stone-500 mt-0.5">{{ $booking->hike->location }}</p>
                <div class="flex items-center gap-2 mt-1.5 flex-wrap">
                    <span class="text-[10px] font-bold uppercase tracking-wide px-2 py-0.5 rounded-full {{ $statusColor['bg'] }} {{ $statusColor['text'] }}">
                        {{ $statusColor['label'] }}
                    </span>
                    <span class="text-[10px] text-stone-400">{{ $booking->booking_ref }}</span>
                    @if($booking->package !== 'day')
                    <span class="text-[10px] font-semibold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full uppercase">
                        {{ $booking->package }} pkg
                    </span>
                    @endif
                </div>
            </div>

            {{-- Amount + action --}}
            <div class="flex-shrink-0 text-right">
                <p class="text-sm font-bold text-stone-800">R{{ number_format($booking->amount_due, 2) }}</p>
                @if($booking->status === 'pending_payment')
                <a href="{{ route('booking.payment', $booking->booking_ref) }}"
                    class="mt-1.5 inline-block text-xs font-semibold text-white bg-green-700 hover:bg-green-800 px-3 py-1.5 rounded-lg transition-colors">
                    Pay now
                </a>
                @else
                <a href="{{ route('booking.confirmation', $booking->booking_ref) }}"
                    class="mt-1.5 inline-block text-xs font-semibold text-green-700 hover:text-green-800 transition-colors">
                    View &rarr;
                </a>
                @endif
            </div>
        </div>
        @empty
        <div class="bg-white rounded-2xl border border-dashed border-stone-200 p-10 text-center">
            <p class="text-2xl mb-3">🥾</p>
            <p class="font-semibold text-stone-700">No upcoming trips</p>
            <p class="text-sm text-stone-400 mt-1">Browse hikes and book your next adventure</p>
            <a href="{{ route('hikes.index') }}"
                class="mt-4 inline-block bg-green-700 hover:bg-green-800 text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition-colors">
                Browse Hikes
            </a>
        </div>
        @endforelse
    </div>

    {{-- ── PAST HIKES ── --}}
    @if($this->recentBookings->isNotEmpty())
    <div>
        <h2 class="text-lg font-bold text-stone-800 mb-4">Recent History</h2>
        <div class="bg-white rounded-2xl border border-stone-100 divide-y divide-stone-100">
            @foreach($this->recentBookings as $booking)
            <div class="px-5 py-3.5 flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-stone-800">{{ $booking->hike->title }}</p>
                    <p class="text-xs text-stone-400 mt-0.5">
                        {{ $booking->hike->departs_at->format('D, d M Y') }}
                        &bull; <span class="capitalize">{{ $booking->status }}</span>
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-sm font-bold text-stone-700">R{{ number_format($booking->amount_due, 2) }}</p>
                    @if($booking->status === 'attended')
                    <p class="text-xs text-green-700 mt-0.5">+{{ $booking->hike->points_awarded }} pts earned</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
