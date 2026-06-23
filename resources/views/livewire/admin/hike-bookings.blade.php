<div class="max-w-6xl mx-auto space-y-6">

    {{-- ── HIKE HEADER ── --}}
    <div class="rounded-2xl overflow-hidden" style="background:#0d1e13;">
        <div style="height:3px; background: linear-gradient(90deg,transparent,#c9a84c,#e8d08a,#c9a84c,transparent);"></div>
        <div class="px-8 py-6 flex items-start justify-between gap-6 flex-wrap">
            <div>
                <p class="text-[10px] uppercase tracking-[0.2em] font-semibold mb-1" style="color:#4a6a52;">
                    Admin · Booking Management
                </p>
                <h1 class="text-2xl font-bold" style="font-family:'Cormorant Garamond',serif; color:#f5f0e8;">
                    {{ $this->hike->title }}
                </h1>
                <p class="text-sm mt-1" style="color:#6a9070;">
                    {{ $this->hike->departs_at->format('D, d M Y \a\t H:i') }}
                    &bull; {{ $this->hike->location }}
                    &bull; <span class="capitalize">{{ $this->hike->difficulty }}</span>
                </p>
            </div>
            <a href="{{ route('admin.hikes') }}"
                class="text-xs font-semibold px-4 py-2 rounded-xl transition-colors"
                style="background:rgba(201,168,76,0.1); border:1px solid rgba(201,168,76,0.25); color:#c9a84c;">
                &larr; All hikes
            </a>
        </div>

        {{-- Stats --}}
        @php $s = $this->stats; $pct = $s['capacity'] > 0 ? round($s['booked']/$s['capacity']*100) : 0; @endphp
        <div class="px-8 pb-6 grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="rounded-xl p-3.5" style="background:rgba(255,255,255,0.04);">
                <p class="text-2xl font-bold" style="color:#f5f0e8;">{{ $s['booked'] }} / {{ $s['capacity'] }}</p>
                <p class="text-[10px] uppercase tracking-wide mt-0.5" style="color:#4a6a52;">Spots filled</p>
                <div class="mt-2 h-1 rounded-full" style="background:rgba(255,255,255,0.08);">
                    <div class="h-1 rounded-full" style="width:{{ $pct }}%; background:linear-gradient(90deg,#c9a84c,#e8d08a);"></div>
                </div>
            </div>
            <div class="rounded-xl p-3.5" style="background:rgba(255,255,255,0.04);">
                <p class="text-2xl font-bold" style="color:#c9a84c;">R{{ number_format($s['revenue'], 0) }}</p>
                <p class="text-[10px] uppercase tracking-wide mt-0.5" style="color:#4a6a52;">Revenue confirmed</p>
            </div>
            <div class="rounded-xl p-3.5" style="background:rgba(255,255,255,0.04);">
                <p class="text-2xl font-bold text-yellow-400">{{ $s['pending'] }}</p>
                <p class="text-[10px] uppercase tracking-wide mt-0.5" style="color:#4a6a52;">Payments to review</p>
            </div>
            <div class="rounded-xl p-3.5" style="background:rgba(255,255,255,0.04);">
                <p class="text-2xl font-bold text-green-400">{{ $s['confirmed'] }}</p>
                <p class="text-[10px] uppercase tracking-wide mt-0.5" style="color:#4a6a52;">Confirmed bookings</p>
            </div>
        </div>
    </div>

    {{-- ── FILTER TABS ── --}}
    <div class="flex items-center gap-2 flex-wrap">
        @foreach(['all' => 'All', 'payment_uploaded' => '🔔 Needs Review', 'confirmed' => 'Confirmed', 'pending_payment' => 'Awaiting Payment', 'attended' => 'Attended', 'cancelled' => 'Cancelled'] as $val => $label)
        <button wire:click="$set('filterStatus', '{{ $val }}')"
            class="px-3 py-1.5 rounded-xl text-xs font-semibold transition-all
                   {{ $filterStatus === $val ? 'bg-green-700 text-white shadow-sm' : 'bg-white text-stone-600 border border-stone-200 hover:border-stone-300' }}">
            {{ $label }}
        </button>
        @endforeach
    </div>

    {{-- ── BOOKINGS TABLE ── --}}
    @if($this->bookings->isEmpty())
    <div class="bg-white rounded-2xl border border-dashed border-stone-200 p-10 text-center text-stone-400">
        <p>No bookings match this filter.</p>
    </div>
    @else
    <div class="bg-white rounded-2xl border border-stone-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr style="background:#f9f7f3; border-bottom:1px solid #e8e0d0;">
                    <th class="text-left px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-stone-400">Member</th>
                    <th class="text-left px-4 py-3 text-[10px] font-bold uppercase tracking-wider text-stone-400">Booking ref</th>
                    <th class="text-left px-4 py-3 text-[10px] font-bold uppercase tracking-wider text-stone-400">Package</th>
                    <th class="text-right px-4 py-3 text-[10px] font-bold uppercase tracking-wider text-stone-400">Amount</th>
                    <th class="text-left px-4 py-3 text-[10px] font-bold uppercase tracking-wider text-stone-400">Status</th>
                    <th class="text-right px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-stone-400">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @foreach($this->bookings as $booking)
                @php
                    $badge = match($booking->status) {
                        'confirmed'        => 'bg-green-100 text-green-800',
                        'attended'         => 'bg-green-200 text-green-900',
                        'payment_uploaded' => 'bg-yellow-100 text-yellow-800',
                        'payment_verified' => 'bg-blue-100 text-blue-800',
                        'pending_payment'  => 'bg-orange-100 text-orange-800',
                        'cancelled'        => 'bg-red-100 text-red-700',
                        default            => 'bg-stone-100 text-stone-600',
                    };
                    $statusLabel = match($booking->status) {
                        'payment_uploaded' => 'Needs Review',
                        'payment_verified' => 'Payment OK',
                        'pending_payment'  => 'Awaiting Payment',
                        default => ucwords(str_replace('_', ' ', $booking->status)),
                    };
                @endphp
                <tr class="hover:bg-stone-50/60 transition-colors" wire:key="bk-{{ $booking->id }}">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full flex-shrink-0 flex items-center justify-center text-xs font-bold text-white"
                                style="background: {{ $booking->member->explorerLevel?->badge_color ?? '#166534' }};">
                                {{ strtoupper(substr($booking->member->first_name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-stone-800">{{ $booking->member->full_name }}</p>
                                <p class="text-xs text-stone-400">{{ $booking->member->explorerLevel?->name ?? 'Trail Starter' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-4">
                        <p class="font-mono text-xs text-stone-700">{{ $booking->booking_ref }}</p>
                        <p class="text-xs text-stone-400 mt-0.5">{{ $booking->spots }} spot{{ $booking->spots > 1 ? 's' : '' }}</p>
                    </td>
                    <td class="px-4 py-4">
                        <span class="text-[10px] font-bold uppercase tracking-wide px-2 py-1 rounded-lg
                                     {{ $booking->package === 'full' ? 'bg-indigo-100 text-indigo-700' : ($booking->package === 'stay' ? 'bg-purple-100 text-purple-700' : 'bg-stone-100 text-stone-600') }}">
                            {{ strtoupper($booking->package ?? 'DAY') }}
                        </span>
                        @if($booking->pickupPoint)
                        <p class="text-[10px] text-amber-700 mt-1">🚌 {{ $booking->pickupPoint->name }}</p>
                        @endif
                    </td>
                    <td class="px-4 py-4 text-right">
                        <p class="font-bold text-stone-800">R{{ number_format($booking->amount_due, 2) }}</p>
                        @if($booking->discount_applied > 0)
                        <p class="text-[10px] text-green-600">-R{{ number_format($booking->discount_applied, 2) }} disc</p>
                        @endif
                    </td>
                    <td class="px-4 py-4">
                        <span class="text-[10px] font-bold uppercase tracking-wide px-2.5 py-1 rounded-full {{ $badge }}">
                            {{ $statusLabel }}
                        </span>
                        @if($booking->payments->isNotEmpty())
                        <p class="text-[10px] text-stone-400 mt-1">{{ ucfirst($booking->payments->last()->method) }} proof uploaded</p>
                        @endif
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex items-center justify-end gap-2">
                            @if($booking->status === 'payment_uploaded')
                            <button wire:click="verifyAndConfirm({{ $booking->id }})"
                                wire:confirm="Verify payment and confirm this booking?"
                                class="text-xs font-semibold text-white bg-green-700 hover:bg-green-800 px-3 py-1.5 rounded-lg transition-colors">
                                ✓ Verify
                            </button>
                            <button wire:click="rejectPayment({{ $booking->id }})"
                                wire:confirm="Reject this payment proof? The member will be asked to re-upload."
                                class="text-xs font-semibold text-red-600 hover:text-red-800 border border-red-200 hover:border-red-400 px-3 py-1.5 rounded-lg transition-colors">
                                Reject
                            </button>
                            @elseif($booking->status === 'confirmed')
                            <button wire:click="markAttended({{ $booking->id }})"
                                wire:confirm="Mark this member as attended? This will award their explorer points."
                                class="text-xs font-semibold text-blue-600 hover:text-blue-800 border border-blue-200 hover:border-blue-400 px-3 py-1.5 rounded-lg transition-colors">
                                ✓ Attended
                            </button>
                            @else
                            <span class="text-xs text-stone-300">—</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

</div>
