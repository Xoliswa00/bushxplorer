<div class="max-w-6xl mx-auto space-y-8">

    {{-- Header --}}
    <div class="flex items-end justify-between">
        <div>
            <p class="text-[10px] font-bold uppercase tracking-[0.2em] mb-1" style="color:#c9a84c;">Admin</p>
            <h1 class="text-2xl font-bold text-stone-900" style="font-family:'Cormorant Garamond',serif;">Dashboard</h1>
        </div>
        <nav class="flex items-center gap-3 text-sm">
            <a href="{{ route('admin.hikes') }}"
               class="text-stone-500 hover:text-stone-900 transition-colors px-3 py-1.5 rounded-lg hover:bg-stone-100">
                Hike Management
            </a>
            <a href="{{ route('admin.gallery') }}"
               class="text-stone-500 hover:text-stone-900 transition-colors px-3 py-1.5 rounded-lg hover:bg-stone-100">
                Gallery
            </a>
            <a href="{{ route('planner.index') }}"
               class="text-stone-500 hover:text-stone-900 transition-colors px-3 py-1.5 rounded-lg hover:bg-stone-100">
                Planner
            </a>
        </nav>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        {{-- Revenue --}}
        <div class="bg-white rounded-2xl border border-stone-100 p-5">
            <p class="text-[10px] font-bold uppercase tracking-wider text-stone-400 mb-3">Revenue {{ now()->year }}</p>
            <p class="text-2xl font-bold text-stone-900">R{{ number_format($this->revenueThisYear, 0) }}</p>
            @if($this->revenueLastMonth > 0)
            <p class="text-xs text-stone-400 mt-1">R{{ number_format($this->revenueLastMonth, 0) }} last month</p>
            @endif
        </div>

        {{-- Members --}}
        <div class="bg-white rounded-2xl border border-stone-100 p-5">
            <p class="text-[10px] font-bold uppercase tracking-wider text-stone-400 mb-3">Active Members</p>
            <p class="text-2xl font-bold text-stone-900">{{ number_format($this->activeMemberCount) }}</p>
            @if($this->newMembersThisMonth > 0)
            <p class="text-xs text-stone-400 mt-1">+{{ $this->newMembersThisMonth }} this month</p>
            @else
            <p class="text-xs text-stone-400 mt-1">No new members this month</p>
            @endif
        </div>

        {{-- Pending --}}
        <div class="bg-white rounded-2xl border border-stone-100 p-5 {{ $this->pendingVerificationCount > 0 ? 'ring-2 ring-amber-300' : '' }}">
            <p class="text-[10px] font-bold uppercase tracking-wider text-stone-400 mb-3">Awaiting Verification</p>
            <p class="text-2xl font-bold {{ $this->pendingVerificationCount > 0 ? 'text-amber-600' : 'text-stone-900' }}">
                {{ $this->pendingVerificationCount }}
            </p>
            <p class="text-xs text-stone-400 mt-1">Payment proofs to review</p>
        </div>

        {{-- Upcoming Trips --}}
        <div class="bg-white rounded-2xl border border-stone-100 p-5">
            <p class="text-[10px] font-bold uppercase tracking-wider text-stone-400 mb-3">Trips (Next 90 Days)</p>
            <p class="text-2xl font-bold text-stone-900">{{ $this->upcomingTripCount }}</p>
            <p class="text-xs text-stone-400 mt-1">Published & active</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Action Queue: Payment Verifications --}}
        <div class="lg:col-span-2 space-y-4">
            <h2 class="text-sm font-bold uppercase tracking-wider text-stone-500">
                Action Queue
                @if($this->pendingVerificationCount > 0)
                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-700">
                    {{ $this->pendingVerificationCount }} pending
                </span>
                @endif
            </h2>

            @if($this->pendingVerifications->isEmpty())
            <div class="bg-white rounded-2xl border border-dashed border-stone-200 p-8 text-center text-stone-400">
                <p class="text-2xl mb-2">✓</p>
                <p class="text-sm font-medium">No payments awaiting verification</p>
            </div>
            @else
            <div class="bg-white rounded-2xl border border-stone-100 overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr style="background:#f9f7f3; border-bottom:1px solid #e8e0d0;">
                            <th class="text-left px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-stone-400">Member</th>
                            <th class="text-left px-4 py-3 text-[10px] font-bold uppercase tracking-wider text-stone-400">Hike</th>
                            <th class="text-right px-4 py-3 text-[10px] font-bold uppercase tracking-wider text-stone-400">Amount</th>
                            <th class="text-right px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-stone-400">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        @foreach($this->pendingVerifications as $booking)
                        <tr class="hover:bg-stone-50/60 transition-colors">
                            <td class="px-5 py-3.5">
                                <p class="font-semibold text-stone-800">{{ $booking->member->full_name }}</p>
                                <p class="text-xs text-stone-400">{{ $booking->booking_ref }}</p>
                            </td>
                            <td class="px-4 py-3.5">
                                <p class="text-stone-700 font-medium line-clamp-1">{{ $booking->hike->title }}</p>
                                <p class="text-xs text-stone-400">{{ $booking->hike->departs_at->format('d M Y') }}</p>
                            </td>
                            <td class="px-4 py-3.5 text-right font-bold text-stone-800">
                                R{{ number_format($booking->amount_due, 0) }}
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <a href="{{ route('admin.hike.bookings', $booking->hike_id) }}"
                                   class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-lg text-white transition-colors"
                                   style="background:#166534;">
                                    Review →
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

            {{-- Upcoming Hikes --}}
            <h2 class="text-sm font-bold uppercase tracking-wider text-stone-500 pt-2">Upcoming Trips</h2>

            @if($this->upcomingHikes->isEmpty())
            <div class="bg-white rounded-2xl border border-dashed border-stone-200 p-8 text-center text-stone-400">
                <p class="text-sm">No upcoming trips in the next 90 days.</p>
                <a href="{{ route('planner.create') }}"
                   class="mt-3 inline-block text-xs font-semibold px-4 py-2 rounded-xl text-white"
                   style="background:#166534;">
                    Plan a Trip →
                </a>
            </div>
            @else
            <div class="bg-white rounded-2xl border border-stone-100 overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr style="background:#f9f7f3; border-bottom:1px solid #e8e0d0;">
                            <th class="text-left px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-stone-400">Trip</th>
                            <th class="text-left px-4 py-3 text-[10px] font-bold uppercase tracking-wider text-stone-400">Date</th>
                            <th class="text-right px-4 py-3 text-[10px] font-bold uppercase tracking-wider text-stone-400">Capacity</th>
                            <th class="text-right px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-stone-400"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        @foreach($this->upcomingHikes as $hike)
                        @php
                            $pct = $hike->max_capacity > 0
                                ? min(100, round($hike->confirmed_spots / $hike->max_capacity * 100))
                                : 0;
                            $spotsLeft = max(0, $hike->max_capacity - $hike->confirmed_spots);
                        @endphp
                        <tr class="hover:bg-stone-50/60 transition-colors">
                            <td class="px-5 py-4">
                                <p class="font-semibold text-stone-800">{{ $hike->title }}</p>
                                <p class="text-xs text-stone-400 mt-0.5 capitalize">{{ $hike->difficulty }}
                                    @if(($hike->nights ?? 0) > 0) · {{ $hike->nights }}n overnight @endif
                                </p>
                            </td>
                            <td class="px-4 py-4 text-stone-600 whitespace-nowrap">
                                {{ $hike->departs_at->format('D, d M') }}
                                @if($hike->departs_at->diffInDays(now()) <= 7)
                                <span class="ml-1.5 inline-block px-1.5 py-0.5 text-[10px] font-bold rounded bg-amber-100 text-amber-700 uppercase">Soon</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <span class="text-xs text-stone-500">{{ $hike->confirmed_spots }}/{{ $hike->max_capacity }}</span>
                                    <div class="w-20 h-1.5 rounded-full bg-stone-100 overflow-hidden">
                                        <div class="h-1.5 rounded-full transition-all {{ $pct >= 90 ? 'bg-red-500' : ($pct >= 70 ? 'bg-amber-500' : 'bg-green-600') }}"
                                             style="width:{{ $pct }}%"></div>
                                    </div>
                                </div>
                                @if($spotsLeft <= 3 && $spotsLeft > 0)
                                <p class="text-[10px] text-red-500 font-bold mt-0.5 text-right">{{ $spotsLeft }} left!</p>
                                @elseif($spotsLeft === 0)
                                <p class="text-[10px] text-red-600 font-bold mt-0.5 text-right">FULL</p>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('admin.hike.bookings', $hike->id) }}"
                                   class="text-xs text-green-700 font-semibold hover:text-green-900">
                                    Bookings →
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

        {{-- Right sidebar --}}
        <div class="space-y-6">

            {{-- Member Level Distribution --}}
            <div class="bg-white rounded-2xl border border-stone-100 p-5">
                <h3 class="text-[10px] font-bold uppercase tracking-wider text-stone-400 mb-4">Member Levels</h3>
                <div class="space-y-3">
                    @foreach($this->levelDistribution as $level)
                    <div>
                        <div class="flex justify-between text-xs mb-1">
                            <span class="font-semibold text-stone-700">{{ $level['name'] }}</span>
                            <span class="text-stone-400">{{ $level['count'] }} ({{ $level['percent'] }}%)</span>
                        </div>
                        <div class="h-1.5 rounded-full bg-stone-100 overflow-hidden">
                            <div class="h-1.5 rounded-full transition-all"
                                 style="width:{{ $level['percent'] }}%; background:{{ $level['color'] }};"></div>
                        </div>
                    </div>
                    @endforeach

                    @if($this->levelDistribution->isEmpty())
                    <p class="text-xs text-stone-400 text-center py-2">No levels configured yet.</p>
                    @endif
                </div>
            </div>

            {{-- Recent Confirmed Bookings --}}
            <div class="bg-white rounded-2xl border border-stone-100 p-5">
                <h3 class="text-[10px] font-bold uppercase tracking-wider text-stone-400 mb-4">Recent Bookings</h3>

                @if($this->recentBookings->isEmpty())
                <p class="text-xs text-stone-400 text-center py-2">No bookings yet.</p>
                @else
                <div class="space-y-3">
                    @foreach($this->recentBookings as $booking)
                    @php
                        $statusColor = match($booking->status) {
                            'confirmed'        => ['bg' => '#dcfce7', 'text' => '#166534'],
                            'payment_uploaded' => ['bg' => '#fef9c3', 'text' => '#854d0e'],
                            'payment_verified' => ['bg' => '#dbeafe', 'text' => '#1e40af'],
                            default            => ['bg' => '#f3f4f6', 'text' => '#6b7280'],
                        };
                        $statusLabel = match($booking->status) {
                            'confirmed'        => 'Confirmed',
                            'payment_uploaded' => 'Proof uploaded',
                            'payment_verified' => 'Verified',
                            default            => $booking->status,
                        };
                    @endphp
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="text-xs font-semibold text-stone-800 truncate">{{ $booking->member->full_name }}</p>
                            <p class="text-[10px] text-stone-400 truncate">{{ $booking->hike->title }}</p>
                        </div>
                        <span class="flex-shrink-0 text-[10px] font-bold px-2 py-0.5 rounded-full"
                              style="background:{{ $statusColor['bg'] }}; color:{{ $statusColor['text'] }};">
                            {{ $statusLabel }}
                        </span>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Quick Links --}}
            <div class="bg-white rounded-2xl border border-stone-100 p-5">
                <h3 class="text-[10px] font-bold uppercase tracking-wider text-stone-400 mb-4">Quick Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('planner.create') }}"
                       class="flex items-center gap-2 text-sm font-medium text-stone-700 hover:text-green-800 py-1.5 transition-colors">
                        <span class="text-base">📋</span> Plan a new trip
                    </a>
                    <a href="{{ route('admin.hikes') }}"
                       class="flex items-center gap-2 text-sm font-medium text-stone-700 hover:text-green-800 py-1.5 transition-colors">
                        <span class="text-base">🥾</span> Manage hikes
                    </a>
                    <a href="{{ route('admin.gallery') }}"
                       class="flex items-center gap-2 text-sm font-medium text-stone-700 hover:text-green-800 py-1.5 transition-colors">
                        <span class="text-base">🖼</span> Gallery manager
                    </a>
                    <a href="{{ route('hikes.index') }}"
                       class="flex items-center gap-2 text-sm font-medium text-stone-700 hover:text-green-800 py-1.5 transition-colors">
                        <span class="text-base">🌿</span> View public site
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
