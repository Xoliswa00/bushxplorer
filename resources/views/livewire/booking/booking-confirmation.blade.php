<div class="max-w-2xl mx-auto p-6">

    {{-- Status banner --}}
    @php
        $colors = [
            'green'  => 'bg-green-50 border-green-300 text-green-800',
            'blue'   => 'bg-blue-50 border-blue-300 text-blue-800',
            'yellow' => 'bg-yellow-50 border-yellow-300 text-yellow-800',
            'red'    => 'bg-red-50 border-red-300 text-red-800',
            'gray'   => 'bg-stone-50 border-stone-300 text-stone-800',
        ];
        $color = $colors[$this->statusColor] ?? $colors['gray'];
    @endphp

    <div class="mb-6 p-5 rounded-2xl border {{ $color }} text-center">
        @if($this->statusColor === 'green')
            <svg class="mx-auto h-12 w-12 text-green-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        @elseif($this->statusColor === 'blue')
            <svg class="mx-auto h-12 w-12 text-blue-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        @elseif($this->statusColor === 'yellow')
            <svg class="mx-auto h-12 w-12 text-yellow-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        @endif

        <h2 class="text-2xl font-bold">{{ $this->statusLabel }}</h2>
        <p class="text-sm mt-1 font-mono font-semibold">{{ $this->booking->booking_ref }}</p>
    </div>

    {{-- Hike details --}}
    <div class="bg-white rounded-2xl shadow-md p-5 mb-4">
        <h3 class="font-bold text-stone-800 text-lg mb-3">Hike Details</h3>
        <dl class="space-y-2 text-sm">
            <div class="flex justify-between">
                <dt class="text-stone-500">Hike</dt>
                <dd class="font-medium text-stone-800">{{ $this->booking->hike->title }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-stone-500">Date</dt>
                <dd class="font-medium text-stone-800">{{ $this->booking->hike->departs_at->format('D, d M Y \a\t H:i') }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-stone-500">Meeting point</dt>
                <dd class="font-medium text-stone-800">{{ $this->booking->hike->meeting_point ?? $this->booking->hike->location }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-stone-500">Spots</dt>
                <dd class="font-medium text-stone-800">{{ $this->booking->spots }}</dd>
            </div>
        </dl>
    </div>

    {{-- Payment summary --}}
    <div class="bg-white rounded-2xl shadow-md p-5 mb-4">
        <h3 class="font-bold text-stone-800 text-lg mb-3">Payment Summary</h3>
        <dl class="space-y-2 text-sm">
            <div class="flex justify-between">
                <dt class="text-stone-500">Amount due</dt>
                <dd class="font-medium text-stone-800">R{{ number_format($this->booking->amount_due, 2) }}</dd>
            </div>
            @if($this->booking->discount_applied > 0)
            <div class="flex justify-between">
                <dt class="text-stone-500">Discount applied</dt>
                <dd class="font-medium text-green-700">&minus; R{{ number_format($this->booking->discount_applied, 2) }}</dd>
            </div>
            @endif
            <div class="flex justify-between">
                <dt class="text-stone-500">Amount paid</dt>
                <dd class="font-medium text-stone-800">R{{ number_format($this->booking->amount_paid, 2) }}</dd>
            </div>
            @if($this->booking->payments->isNotEmpty())
            <div class="flex justify-between">
                <dt class="text-stone-500">Payment status</dt>
                <dd class="font-semibold capitalize {{ $this->booking->payments->last()->status === 'verified' ? 'text-green-700' : 'text-blue-700' }}">
                    {{ ucfirst($this->booking->payments->last()->status) }}
                </dd>
            </div>
            @endif
        </dl>
    </div>

    {{-- Member passport snippet --}}
    <div class="bg-white rounded-2xl shadow-md p-5 mb-6">
        <h3 class="font-bold text-stone-800 text-lg mb-3">Explorer Passport</h3>
        <div class="flex items-center gap-3">
            @if($this->booking->member->explorerLevel)
            <div
                class="w-10 h-10 rounded-full flex items-center justify-center text-white text-xs font-bold"
                style="background-color: {{ $this->booking->member->explorerLevel->badge_color }}"
            >
                {{ strtoupper(substr($this->booking->member->explorerLevel->name, 0, 2)) }}
            </div>
            @endif
            <div>
                <p class="font-semibold text-stone-800">{{ $this->booking->member->full_name }}</p>
                <p class="text-sm text-stone-500">
                    {{ $this->booking->member->explorerLevel?->name ?? 'Explorer' }}
                    &bull; {{ $this->booking->member->total_points }} pts
                    &bull; {{ $this->booking->member->hikes_attended }} hike{{ $this->booking->member->hikes_attended !== 1 ? 's' : '' }}
                </p>
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <div class="flex gap-3">
        <a href="{{ route('hikes.index') }}" class="flex-1 text-center bg-stone-100 hover:bg-stone-200 text-stone-700 font-semibold py-3 px-4 rounded-xl transition-colors">
            Browse more hikes
        </a>
        @if($this->booking->status === 'pending_payment')
        <a href="{{ route('booking.payment', $this->booking->booking_ref) }}" class="flex-1 text-center bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-xl transition-colors">
            Upload payment &rarr;
        </a>
        @endif
    </div>

</div>
