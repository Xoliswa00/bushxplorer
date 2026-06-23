<div class="max-w-2xl mx-auto space-y-5">

    {{-- Hike summary --}}
    <div class="rounded-2xl overflow-hidden" style="background:#0d1e13;">
        <div style="height:3px;background:linear-gradient(90deg,transparent,#c9a84c,#e8d08a,#c9a84c,transparent);"></div>
        <div class="px-6 py-5 flex items-start justify-between gap-4">
            <div>
                <p class="text-[10px] uppercase tracking-[0.2em] font-semibold mb-1" style="color:#4a6a52;">Booking</p>
                <h2 class="text-xl font-bold leading-snug" style="font-family:'Cormorant Garamond',serif; color:#f5f0e8;">
                    {{ $this->hike->title }}
                </h2>
                <p class="text-sm mt-1" style="color:#6a9070;">
                    {{ $this->hike->departs_at->format('D, d M Y \a\t H:i') }}
                    &bull; {{ $this->hike->location }}
                </p>
            </div>
            <div class="flex-shrink-0 text-right">
                <p class="text-2xl font-bold" style="color:#c9a84c;">R{{ number_format($this->hike->price, 0) }}</p>
                <p class="text-[10px]" style="color:#4a6a52;">per person</p>
                @if($this->spotsRemaining <= 5)
                <p class="text-[10px] text-red-400 mt-1">Only {{ $this->spotsRemaining }} left</p>
                @endif
            </div>
        </div>
    </div>

    <form wire:submit="submit" class="space-y-4">

        {{-- Spots --}}
        <div class="bg-white rounded-2xl border border-stone-100 p-5">
            <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-stone-400 mb-3">
                Number of spots
            </label>
            <div class="flex items-center gap-3">
                <button type="button" wire:click="$set('spots', max(1, spots - 1))"
                    class="w-9 h-9 rounded-xl bg-stone-100 hover:bg-stone-200 text-stone-700 font-bold text-lg flex items-center justify-center transition-colors">−</button>
                <span class="w-10 text-center font-bold text-stone-900 text-xl">{{ $spots }}</span>
                <button type="button" wire:click="$set('spots', min(spotsRemaining, spots + 1))"
                    class="w-9 h-9 rounded-xl bg-stone-100 hover:bg-stone-200 text-stone-700 font-bold text-lg flex items-center justify-center transition-colors">+</button>
                <span class="text-xs text-stone-400 ml-2">{{ $this->spotsRemaining }} available</span>
            </div>
            @error('spots') <p class="mt-2 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        {{-- Package selection for overnight trips --}}
        @if($this->isOvernight)
        <div class="bg-white rounded-2xl border border-stone-100 p-5">
            <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-stone-400 mb-3">
                Choose your package
            </label>
            <div class="space-y-2">
                {{-- DAY package --}}
                <label class="flex items-start gap-3 p-3.5 rounded-xl border cursor-pointer transition-all
                              {{ $package === 'day' ? 'border-stone-400 bg-stone-50' : 'border-stone-200 hover:border-stone-300' }}">
                    <input type="radio" wire:model.live="package" value="day" class="mt-0.5 text-green-600 focus:ring-green-500"/>
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-bold text-stone-800">🎫 Day Hiker</p>
                            <p class="text-sm font-bold text-stone-700">R{{ number_format($this->hike->price, 0) }}<span class="text-stone-400 font-normal text-xs">/person</span></p>
                        </div>
                        <p class="text-xs text-stone-500 mt-0.5">Ticket only — arrange your own accommodation</p>
                    </div>
                </label>

                {{-- STAY package --}}
                <label class="flex items-start gap-3 p-3.5 rounded-xl border cursor-pointer transition-all
                              {{ $package === 'stay' ? 'border-indigo-400 bg-indigo-50' : 'border-stone-200 hover:border-indigo-200' }}">
                    <input type="radio" wire:model.live="package" value="stay" class="mt-0.5 text-green-600 focus:ring-green-500"/>
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-bold text-stone-800">🛏 Stay Package</p>
                            <p class="text-sm font-bold text-indigo-700">
                                R{{ number_format($this->hike->price + $this->hike->accommodation_cost_per_person * $this->hike->nights, 0) }}
                                <span class="text-stone-400 font-normal text-xs">/person</span>
                            </p>
                        </div>
                        <p class="text-xs text-stone-500 mt-0.5">
                            Ticket + {{ $this->hike->nights }} night{{ $this->hike->nights > 1 ? 's' : '' }} at
                            {{ $this->hike->accommodation_name ?? 'lodge' }}
                            (R{{ number_format($this->hike->accommodation_cost_per_person, 0) }}/night)
                        </p>
                    </div>
                </label>

                {{-- FULL package --}}
                @if($this->hike->includes_transport)
                <label class="flex items-start gap-3 p-3.5 rounded-xl border cursor-pointer transition-all
                              {{ $package === 'full' ? 'border-amber-400 bg-amber-50' : 'border-stone-200 hover:border-amber-200' }}">
                    <input type="radio" wire:model.live="package" value="full" class="mt-0.5 text-green-600 focus:ring-green-500"/>
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-bold text-stone-800">🚌 Full Package</p>
                            <p class="text-sm font-bold text-amber-700">
                                R{{ number_format($this->hike->price + $this->hike->accommodation_cost_per_person * $this->hike->nights + $this->hike->transport_fee, 0) }}
                                <span class="text-stone-400 font-normal text-xs">/person</span>
                            </p>
                        </div>
                        <p class="text-xs text-stone-500 mt-0.5">Ticket + accommodation + transport (R{{ number_format($this->hike->transport_fee, 0) }}/person)</p>
                    </div>
                </label>
                @endif
            </div>
            @error('package') <p class="mt-2 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>
        @endif

        {{-- Transport (day trips only) --}}
        @if(! $this->isOvernight && $this->hike->includes_transport)
        <div class="bg-white rounded-2xl border border-stone-100 p-5 space-y-4">
            <label class="flex items-start gap-3 cursor-pointer">
                <input type="checkbox" wire:model.live="wantsTransport"
                    class="mt-0.5 w-4 h-4 rounded border-stone-300 text-amber-500 focus:ring-amber-400"/>
                <div>
                    <p class="text-sm font-semibold text-stone-800">
                        Add transport
                        <span class="text-amber-700 font-normal ml-1">+ R{{ number_format($this->hike->transport_fee, 2) }}/person</span>
                    </p>
                    <p class="text-xs text-stone-400 mt-0.5">Pick-up from your nearest stop</p>
                </div>
            </label>

            @if($wantsTransport)
            <div class="space-y-2">
                <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-stone-400">Select pickup point</p>
                @forelse($this->pickupPoints as $point)
                <label class="flex items-start gap-3 p-3 rounded-xl border cursor-pointer transition-colors
                    {{ $pickupPointId == $point->id ? 'border-amber-400 bg-amber-50' : 'border-stone-200 hover:border-stone-300' }}">
                    <input type="radio" wire:model.live="pickupPointId" value="{{ $point->id }}"
                        class="mt-0.5 text-amber-500 focus:ring-amber-400"
                        {{ $point->seats_remaining < $spots ? 'disabled' : '' }}/>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-stone-800">{{ $point->name }}</p>
                        <p class="text-xs text-stone-500">{{ $point->address }}</p>
                        <p class="text-xs mt-0.5">
                            Departs {{ \Carbon\Carbon::parse($point->departure_time)->format('H:i') }}
                            &bull;
                            @if($point->seats_remaining > 0)
                            <span class="text-green-700">{{ $point->seats_remaining }} seats left</span>
                            @else
                            <span class="text-red-600">Full</span>
                            @endif
                        </p>
                    </div>
                </label>
                @empty
                <p class="text-sm text-stone-400">No pickup points configured yet.</p>
                @endforelse
                @error('pickupPointId') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>
            @endif
        </div>
        @endif

        {{-- Pickup for FULL overnight package --}}
        @if($this->isOvernight && $package === 'full' && $this->hike->includes_transport)
        <div class="bg-white rounded-2xl border border-amber-100 p-5">
            <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-amber-600 mb-3">Select your pickup point</p>
            <div class="space-y-2">
                @foreach($this->pickupPoints as $point)
                <label class="flex items-start gap-3 p-3 rounded-xl border cursor-pointer transition-colors
                    {{ $pickupPointId == $point->id ? 'border-amber-400 bg-amber-50' : 'border-stone-200 hover:border-stone-300' }}">
                    <input type="radio" wire:model.live="pickupPointId" value="{{ $point->id }}"
                        class="mt-0.5 text-amber-500 focus:ring-amber-400"
                        {{ $point->seats_remaining < $spots ? 'disabled' : '' }}/>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-stone-800">{{ $point->name }}</p>
                        <p class="text-xs text-stone-400">{{ $point->address }} · {{ \Carbon\Carbon::parse($point->departure_time)->format('H:i') }}</p>
                        <p class="text-xs mt-0.5 {{ $point->seats_remaining > 0 ? 'text-green-700' : 'text-red-600' }}">
                            {{ $point->seats_remaining > 0 ? $point->seats_remaining . ' seats left' : 'Full' }}
                        </p>
                    </div>
                </label>
                @endforeach
            </div>
            @error('pickupPointId') <p class="mt-2 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>
        @endif

        {{-- Notes --}}
        <div class="bg-white rounded-2xl border border-stone-100 p-5">
            <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-stone-400 mb-2">
                Special requests <span class="text-stone-300 font-normal">(optional)</span>
            </label>
            <textarea wire:model="notes" rows="2"
                placeholder="Dietary requirements, medical info, etc."
                class="w-full px-3 py-2.5 border border-stone-200 rounded-xl text-stone-900 text-sm bg-white resize-none
                       focus:outline-none focus:ring-2 focus:ring-green-600/20 focus:border-green-700
                       placeholder:text-stone-300 transition-colors"></textarea>
            @error('notes') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        {{-- Pricing summary --}}
        <div class="bg-white rounded-2xl border border-stone-100 p-5">
            <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-stone-400 mb-3">Pricing summary</p>
            <div class="space-y-1.5">
                <div class="flex justify-between text-sm text-stone-600">
                    <span>Ticket × {{ $spots }}</span>
                    <span>R{{ number_format($this->hike->price * $spots, 2) }}</span>
                </div>
                @if($this->discountAmount > 0)
                <div class="flex justify-between text-sm text-green-700">
                    <span>{{ $this->member->explorerLevel->name }} discount ({{ $this->discountPct }}%)</span>
                    <span>− R{{ number_format($this->discountAmount, 2) }}</span>
                </div>
                @endif
                @if($this->accommodationFee > 0)
                <div class="flex justify-between text-sm text-indigo-700">
                    <span>Accommodation ({{ $this->hike->nights }}n × {{ $spots }}p)</span>
                    <span>R{{ number_format($this->accommodationFee, 2) }}</span>
                </div>
                @endif
                @if($this->transportFee > 0)
                <div class="flex justify-between text-sm text-amber-700">
                    <span>Transport × {{ $spots }}</span>
                    <span>R{{ number_format($this->transportFee, 2) }}</span>
                </div>
                @endif
                <div class="flex justify-between font-bold text-stone-900 text-base pt-2 border-t border-stone-100">
                    <span>Total due</span>
                    <span>R{{ number_format($this->amountDue, 2) }}</span>
                </div>
            </div>
        </div>

        @if($this->member->explorerLevel)
        <p class="text-xs text-stone-500 text-center">
            Your level: <span class="font-semibold" style="color: {{ $this->member->explorerLevel->badge_color }}">{{ $this->member->explorerLevel->name }}</span>
            &bull; {{ number_format($this->member->total_points) }} pts
            @if($this->discountPct > 0)&bull; <span class="text-green-700">{{ $this->discountPct }}% discount applied</span>@endif
        </p>
        @endif

        <button type="submit" wire:loading.attr="disabled"
            class="w-full py-4 rounded-2xl font-bold text-base text-white transition-all
                   bg-green-700 hover:bg-green-800 active:scale-[0.99] disabled:opacity-60 shadow-sm shadow-green-900/20">
            <span wire:loading.remove>Reserve My Spot &rarr;</span>
            <span wire:loading>Reserving&hellip;</span>
        </button>

    </form>
</div>
