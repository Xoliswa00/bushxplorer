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
            <label class="block text-sm font-medium text-stone-700 mb-1">
                Number of spots
            </label>
            <input
                type="number"
                wire:model.live="spots"
                min="1"
                max="{{ $this->spotsRemaining }}"
                class="w-24 px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-center"
            />
            @error('spots') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Notes --}}
        <div>
            <label class="block text-sm font-medium text-stone-700 mb-1">
                Special requests / notes <span class="text-stone-400">(optional)</span>
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
            <div class="flex justify-between font-bold text-stone-800 text-base pt-1 border-t border-green-200">
                <span>Total due</span>
                <span>R{{ number_format($this->amountDue, 2) }}</span>
            </div>
        </div>

        {{-- Member level badge --}}
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
