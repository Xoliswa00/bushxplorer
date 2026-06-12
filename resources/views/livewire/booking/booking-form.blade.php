<div class="max-w-2xl mx-auto p-6 bg-white rounded-2xl shadow-md">

    {{-- Hike summary --}}
    <div class="mb-6 p-4 bg-stone-50 rounded-xl border border-stone-200">
        <h2 class="text-xl font-bold text-stone-800">{{ $this->hike->title }}</h2>
        <p class="text-sm text-stone-500 mt-1">
            {{ $this->hike->departs_at->format('D, d M Y \a\t H:i') }}
            &bull; {{ $this->hike->location }}
            &bull; <span class="capitalize">{{ $this->hike->difficulty }}</span>
        </p>
        <p class="text-sm text-stone-500 mt-1">
            {{ $this->spotsRemaining }} spot{{ $this->spotsRemaining === 1 ? '' : 's' }} remaining
        </p>
    </div>

    <form wire:submit="submit" class="space-y-5">

        {{-- Spots --}}
        <div>
            <label class="block text-sm font-medium text-stone-700 mb-1">Number of spots</label>
            <input
                type="number"
                wire:model.live="spots"
                min="1"
                max="{{ $this->spotsRemaining }}"
                class="w-24 px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-center"
            />
            @error('spots') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Transport (only when hike includes it) --}}
        @if($this->hike->includes_transport)
        <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl space-y-4">
            <label class="flex items-start gap-3 cursor-pointer">
                <input
                    type="checkbox"
                    wire:model.live="wantsTransport"
                    class="mt-0.5 w-4 h-4 rounded border-stone-300 text-green-600 focus:ring-green-500"
                />
                <div>
                    <p class="text-sm font-semibold text-stone-800">
                        Include transport
                        <span class="text-amber-700 font-normal ml-1">+ R{{ number_format($this->hike->transport_fee, 2) }} per spot</span>
                    </p>
                    <p class="text-xs text-stone-500 mt-0.5">Pick-up and drop-off included. Select your nearest point below.</p>
                </div>
            </label>

            {{-- Pickup point selector --}}
            @if($wantsTransport)
            <div class="space-y-2 mt-2">
                <p class="text-sm font-medium text-stone-700">Select your pick-up point</p>

                @forelse($this->pickupPoints as $point)
                <label
                    class="flex items-start gap-3 p-3 rounded-xl border cursor-pointer transition-colors
                        {{ $pickupPointId == $point->id ? 'border-green-500 bg-green-50' : 'border-stone-200 hover:border-stone-300' }}"
                >
                    <input
                        type="radio"
                        wire:model.live="pickupPointId"
                        value="{{ $point->id }}"
                        class="mt-0.5 text-green-600 focus:ring-green-500"
                        {{ $point->seats_remaining < $spots ? 'disabled' : '' }}
                    />
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-stone-800">{{ $point->name }}</p>
                        <p class="text-xs text-stone-500">{{ $point->address }}</p>
                        <p class="text-xs text-stone-500 mt-0.5">
                            Departs {{ \Carbon\Carbon::parse($point->departure_time)->format('H:i') }}
                            &bull;
                            @if($point->seats_remaining > 0)
                                <span class="text-green-700">{{ $point->seats_remaining }} seat{{ $point->seats_remaining === 1 ? '' : 's' }} left</span>
                            @else
                                <span class="text-red-600">Full</span>
                            @endif
                        </p>
                        @if($point->notes)
                        <p class="text-xs text-amber-700 mt-0.5">{{ $point->notes }}</p>
                        @endif
                    </div>
                </label>
                @empty
                <p class="text-sm text-stone-500">No pickup points configured yet.</p>
                @endforelse

                @error('pickupPointId') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            @endif
        </div>
        @endif

        {{-- Notes --}}
        <div>
            <label class="block text-sm font-medium text-stone-700 mb-1">
                Special requests <span class="text-stone-400">(optional)</span>
            </label>
            <textarea
                wire:model="notes"
                rows="3"
                placeholder="Dietary requirements, medical info, etc."
                class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent resize-none"
            ></textarea>
            @error('notes') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Pricing summary --}}
        <div class="p-4 bg-green-50 rounded-xl border border-green-200 space-y-1">
            <div class="flex justify-between text-sm text-stone-600">
                <span>R{{ number_format($this->hike->price, 2) }} &times; {{ $spots }} spot{{ $spots > 1 ? 's' : '' }}</span>
                <span>R{{ number_format($this->hike->price * $spots, 2) }}</span>
            </div>
            @if($this->discountAmount > 0)
            <div class="flex justify-between text-sm text-green-700">
                <span>{{ $this->member->explorerLevel->name }} discount ({{ $this->member->explorerLevel->discount_percentage }}%)</span>
                <span>&minus; R{{ number_format($this->discountAmount, 2) }}</span>
            </div>
            @endif
            @if($this->transportFee > 0)
            <div class="flex justify-between text-sm text-amber-700">
                <span>Transport ({{ $spots }} &times; R{{ number_format($this->hike->transport_fee, 2) }})</span>
                <span>+ R{{ number_format($this->transportFee, 2) }}</span>
            </div>
            @endif
            <div class="flex justify-between font-bold text-stone-800 text-base pt-1 border-t border-green-200">
                <span>Total due</span>
                <span>R{{ number_format($this->amountDue, 2) }}</span>
            </div>
        </div>

        @if($this->member->explorerLevel)
        <p class="text-xs text-stone-500">
            Your level:
            <span class="font-semibold" style="color: {{ $this->member->explorerLevel->badge_color }}">
                {{ $this->member->explorerLevel->name }}
            </span>
            &bull; {{ $this->member->total_points }} points
        </p>
        @endif

        <button
            type="submit"
            wire:loading.attr="disabled"
            class="w-full bg-green-600 hover:bg-green-700 disabled:opacity-50 text-white font-semibold py-3 px-6 rounded-xl transition-colors"
        >
            <span wire:loading.remove>Reserve My Spot &rarr;</span>
            <span wire:loading>Reserving&hellip;</span>
        </button>

    </form>
</div>
