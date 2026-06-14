<div class="max-w-5xl mx-auto space-y-6">

    {{-- ── NOTIFICATIONS BANNER ── --}}
    @if($this->recentNotifications->isNotEmpty())
    <div class="rounded-2xl overflow-hidden border" style="background:#fffbeb; border-color:#fbbf24;">
        <div class="px-5 py-3 flex items-center gap-2 border-b" style="border-color:#fde68a;">
            <span class="text-sm font-bold text-amber-800">Latest Updates</span>
            <span class="text-xs bg-amber-200 text-amber-800 px-2 py-0.5 rounded-full font-semibold">
                {{ $this->recentNotifications->count() }}
            </span>
        </div>
        <div class="divide-y divide-amber-100">
            @foreach($this->recentNotifications->take(3) as $notif)
            <div class="px-5 py-3 flex items-start gap-3">
                <span class="text-base mt-0.5">
                    @if($notif->type === 'badge_awarded') 🏅
                    @elseif($notif->type === 'booking_confirmed') ✅
                    @elseif($notif->type === 'payment_uploaded') 📤
                    @elseif($notif->type === 'payment_rejected') ⚠️
                    @else 🔔
                    @endif
                </span>
                <div>
                    <p class="text-sm font-semibold text-amber-900">{{ $notif->title }}</p>
                    <p class="text-xs text-amber-700 mt-0.5">{{ $notif->body }}</p>
                </div>
                <span class="ml-auto text-[10px] text-amber-500 whitespace-nowrap">
                    {{ $notif->created_at->diffForHumans() }}
                </span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ── EXPLORER PASSPORT HEADER ── --}}
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

            {{-- Level + discount badge --}}
            @if($this->member->explorerLevel)
            <div class="text-right space-y-2">
                <div>
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl"
                        style="background: rgba(201,168,76,0.1); border: 1px solid rgba(201,168,76,0.25);">
                        <div class="w-3 h-3 rounded-full" style="background: {{ $this->member->explorerLevel->badge_color }}"></div>
                        <span class="text-sm font-bold" style="color: #c9a84c;">{{ $this->member->explorerLevel->name }}</span>
                    </div>
                    @if($this->member->explorerLevel->discount_percentage > 0)
                    <div class="inline-flex items-center gap-1 px-3 py-2 rounded-xl ml-2"
                        style="background: rgba(74,124,89,0.2); border: 1px solid rgba(74,124,89,0.3);">
                        <span class="text-sm font-bold" style="color: #6aad7e;">
                            {{ number_format($this->member->explorerLevel->discount_percentage, 0) }}% OFF
                        </span>
                    </div>
                    @endif
                </div>
                <p class="text-xs" style="color: #5a7a60;">
                    {{ number_format($this->member->total_points) }} pts
                    &bull; {{ $this->earnedBadgeCount }}/{{ $this->allBadges->count() }} badges
                </p>
            </div>
            @endif
        </div>

        {{-- Stats bar --}}
        @php
            $discount = $this->member->explorerLevel?->discount_percentage ?? 0;
            $stats = [
                ['label' => 'Hikes Attended', 'value' => $this->member->hikes_attended,                           'color' => '#f5f0e8'],
                ['label' => 'Explorer Points', 'value' => number_format($this->member->total_points),              'color' => '#f5f0e8'],
                ['label' => 'Badges Earned',   'value' => $this->earnedBadgeCount . '/' . $this->allBadges->count(), 'color' => '#f5f0e8'],
                ['label' => 'My Discount',     'value' => $discount > 0 ? number_format($discount, 0).'%' : '—',  'color' => $discount > 0 ? '#6aad7e' : '#4a6a52'],
            ];
        @endphp
        <div class="grid grid-cols-4 border-t" style="border-color: rgba(201,168,76,0.1);">
            @foreach($stats as $i => $stat)
            <div class="px-5 py-4 {{ $i < 3 ? 'border-r' : '' }}" style="{{ $i < 3 ? 'border-color:rgba(201,168,76,0.1)' : '' }}">
                <p class="text-2xl font-bold" style="color:{{ $stat['color'] }};">{{ $stat['value'] }}</p>
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
                    @if($this->nextLevel->discount_percentage > 0)
                    &mdash; unlocks {{ number_format($this->nextLevel->discount_percentage, 0) }}% discount
                    @endif
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

    {{-- ── ADVENTURE BADGES ── --}}
    <div>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-stone-800">
                Adventure Badges
                <span class="text-sm font-normal text-stone-400 ml-2">
                    {{ $this->earnedBadgeCount }} of {{ $this->allBadges->count() }} earned
                </span>
            </h2>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3">
            @foreach($this->allBadges as $badge)
            <div class="relative rounded-2xl border p-4 text-center transition-all
                {{ $badge->earned
                    ? 'bg-white border-stone-200 shadow-sm hover:shadow-md'
                    : 'border-dashed border-stone-200 bg-stone-50' }}">

                {{-- Coloured top bar for earned badges --}}
                @if($badge->earned)
                <div class="absolute top-0 left-4 right-4 h-0.5 rounded-full" style="background: {{ $badge->color }};"></div>
                @endif

                {{-- Icon --}}
                <div class="text-3xl mb-2 {{ $badge->earned ? '' : 'opacity-20' }}">
                    {{ $badge->icon }}
                </div>

                {{-- Name --}}
                <p class="text-xs font-bold leading-tight {{ $badge->earned ? 'text-stone-800' : 'text-stone-400' }}">
                    {{ $badge->name }}
                </p>

                {{-- Description --}}
                <p class="text-[10px] leading-tight mt-1 {{ $badge->earned ? 'text-stone-500' : 'text-stone-300' }}">
                    {{ $badge->description }}
                </p>

                {{-- Earned date or lock icon --}}
                @if($badge->earned && $badge->awarded_at)
                <p class="text-[9px] mt-2 font-semibold" style="color: {{ $badge->color }};">
                    {{ \Carbon\Carbon::parse($badge->awarded_at)->format('M Y') }}
                </p>
                @elseif(!$badge->earned)
                <div class="mt-2 text-stone-300">
                    <svg class="w-3 h-3 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                    </svg>
                </div>
                @endif
            </div>
            @endforeach
        </div>

        {{-- Next badge hint --}}
        @php $nextBadge = $this->allBadges->first(fn($b) => !$b->earned); @endphp
        @if($nextBadge)
        <p class="text-xs text-stone-400 mt-3 text-center">
            Next to unlock: <span class="font-semibold text-stone-600">{{ $nextBadge->icon }} {{ $nextBadge->name }}</span>
            — {{ $nextBadge->description }}
        </p>
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
        <div class="bg-white rounded-2xl border border-stone-100 p-5 flex items-center gap-5 hover:border-stone-200 transition-colors mb-3">
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
