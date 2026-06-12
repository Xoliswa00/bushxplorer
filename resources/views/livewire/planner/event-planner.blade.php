<div class="max-w-3xl mx-auto">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('planner.index') }}" class="text-sm text-stone-500 hover:text-stone-700">
            &larr; Plans
        </a>
        <span class="text-stone-300">/</span>
        <h1 class="text-xl font-bold text-stone-800">
            {{ $planId ? ($title ?: 'Untitled Plan') : 'New Event Plan' }}
        </h1>
    </div>

    {{-- Progress bar --}}
    <div class="mb-8">
        <div class="flex items-center gap-0">
            @foreach($this->stepLabels as $num => $label)
            @php $done = $num < $step; $active = $num === $step; @endphp
            <button
                wire:click="goToStep({{ $num }})"
                class="flex-1 text-center group"
            >
                <div class="flex items-center {{ $loop->first ? '' : '-ml-px' }}">
                    {{-- Connector line left --}}
                    @if(!$loop->first)
                    <div class="flex-1 h-0.5 {{ $done || $active ? 'bg-green-500' : 'bg-stone-200' }} transition-colors"></div>
                    @endif

                    {{-- Circle --}}
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 border-2 transition-all
                        {{ $done   ? 'bg-green-600 border-green-600 text-white' : '' }}
                        {{ $active ? 'bg-white border-green-600 text-green-700' : '' }}
                        {{ !$done && !$active ? 'bg-white border-stone-300 text-stone-400' : '' }}">
                        @if($done)
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                        @else
                        {{ $num }}
                        @endif
                    </div>

                    {{-- Connector line right --}}
                    @if(!$loop->last)
                    <div class="flex-1 h-0.5 {{ $done ? 'bg-green-500' : 'bg-stone-200' }} transition-colors"></div>
                    @endif
                </div>
                <p class="text-[11px] mt-1.5 font-medium {{ $active ? 'text-green-700' : ($done ? 'text-stone-500' : 'text-stone-400') }}">
                    {{ $label }}
                </p>
            </button>
            @endforeach
        </div>
    </div>

    {{-- Step panels --}}
    <div class="bg-white rounded-2xl shadow-md p-6">

        {{-- ─── STEP 1: Concept ────────────────────────────────────────────── --}}
        @if($step === 1)
        <div>
            <h2 class="text-lg font-bold text-stone-800 mb-1">What's the idea?</h2>
            <p class="text-sm text-stone-500 mb-5">Describe your concept — we'll shape it into a full plan.</p>

            <div class="space-y-4">
                <div class="grid grid-cols-3 gap-4">
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-stone-700 mb-1">Event title <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="title" placeholder="e.g. Drakensberg Sunrise Hike"
                            class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"/>
                        @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-stone-700 mb-1">Type <span class="text-red-500">*</span></label>
                        <select wire:model="type" class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-green-500 bg-white focus:border-transparent capitalize">
                            @foreach(['hike','workshop','social','corporate','multi_day'] as $t)
                            <option value="{{ $t }}">{{ ucfirst(str_replace('_',' ',$t)) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-stone-700 mb-1">Tagline <span class="text-stone-400">(one-liner for the poster)</span></label>
                    <input type="text" wire:model="tagline" placeholder="Push your limits. Find your trail."
                        class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"/>
                </div>

                <div>
                    <label class="block text-sm font-medium text-stone-700 mb-1">Description <span class="text-stone-400">(shown on the booking page)</span></label>
                    <textarea wire:model="description" rows="3" placeholder="What makes this event special?"
                        class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent resize-none"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-stone-700 mb-1">Concept notes <span class="text-stone-400">(your raw idea — for your eyes only)</span></label>
                    <textarea wire:model="conceptNotes" rows="3"
                        placeholder="Why this route? What vibe are you going for? Any inspiration?"
                        class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent resize-none bg-amber-50 border-amber-200"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-stone-700 mb-2">Poster colour theme</label>
                    <div class="flex gap-2 flex-wrap">
                        @foreach(['#166534'=>'Forest','#1e3a5f'=>'Navy','#7c2d12'=>'Rust','#4a044e'=>'Plum','#134e4a'=>'Teal','#1c1917'=>'Onyx'] as $hex=>$name)
                        <button type="button" wire:click="$set('coverColor','{{ $hex }}')"
                            class="w-8 h-8 rounded-full border-2 transition-all {{ $coverColor === $hex ? 'border-white ring-2 ring-stone-800 scale-110' : 'border-white' }}"
                            style="background-color: {{ $hex }}" title="{{ $name }}"></button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- ─── STEP 2: Logistics ──────────────────────────────────────────── --}}
        @elseif($step === 2)
        <div>
            <h2 class="text-lg font-bold text-stone-800 mb-1">When and where?</h2>
            <p class="text-sm text-stone-500 mb-5">Lock down the time, location, and difficulty.</p>

            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-stone-700 mb-1">Location / area <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="location" placeholder="e.g. Drakensberg, KZN"
                            class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"/>
                        @error('location') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-stone-700 mb-1">Trail name <span class="text-stone-400">(optional)</span></label>
                        <input type="text" wire:model="trailName" placeholder="e.g. Tugela Falls Trail"
                            class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"/>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-stone-700 mb-1">Departs <span class="text-red-500">*</span></label>
                        <input type="datetime-local" wire:model="departsAt"
                            class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"/>
                        @error('departsAt') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-stone-700 mb-1">Returns <span class="text-stone-400">(optional)</span></label>
                        <input type="datetime-local" wire:model="returnsAt"
                            class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"/>
                        @error('returnsAt') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-stone-700 mb-1">Difficulty</label>
                        <select wire:model="difficulty" class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-green-500 bg-white">
                            <option value="easy">Easy</option>
                            <option value="moderate">Moderate</option>
                            <option value="hard">Hard</option>
                            <option value="extreme">Extreme</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-stone-700 mb-1">Meeting point</label>
                        <input type="text" wire:model="meetingPoint" placeholder="e.g. Amphitheatre car park"
                            class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"/>
                    </div>
                </div>
            </div>
        </div>

        {{-- ─── STEP 3: Capacity & Transport ───────────────────────────────── --}}
        @elseif($step === 3)
        <div>
            <h2 class="text-lg font-bold text-stone-800 mb-1">Capacity &amp; Transport</h2>
            <p class="text-sm text-stone-500 mb-5">How many people and how will they get there?</p>

            <div class="space-y-5">
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-stone-700 mb-1">Max spots <span class="text-red-500">*</span></label>
                        <input type="number" wire:model="maxCapacity" min="2"
                            class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-green-500 text-center"/>
                        @error('maxCapacity') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-stone-700 mb-1">Min to run <span class="text-red-500">*</span></label>
                        <input type="number" wire:model="minCapacity" min="1"
                            class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-green-500 text-center"/>
                        @error('minCapacity') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-stone-700 mb-1">Points awarded</label>
                        <input type="number" wire:model="pointsAwarded" min="0"
                            class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-green-500 text-center"/>
                    </div>
                </div>

                {{-- Transport toggle --}}
                <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" wire:model.live="includesTransport"
                            class="w-4 h-4 rounded border-stone-300 text-green-600 focus:ring-green-500"/>
                        <div>
                            <p class="text-sm font-semibold text-stone-800">Include transport in this event</p>
                            <p class="text-xs text-stone-500 mt-0.5">Members can opt in at booking and choose a pickup point</p>
                        </div>
                    </label>

                    @if($includesTransport)
                    <div class="mt-4 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-stone-700 mb-1">Transport fee per person (R)</label>
                            <input type="number" wire:model="transportFee" min="0" step="0.01"
                                class="w-40 px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-green-500 text-center"/>
                            @error('transportFee') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        {{-- Pickup points builder --}}
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-sm font-medium text-stone-700">Pick-up points</p>
                                <button type="button" wire:click="addPickupPoint"
                                    class="text-xs text-green-700 hover:text-green-900 font-semibold">+ Add point</button>
                            </div>

                            @forelse($pickupPoints as $idx => $point)
                            <div wire:key="pp-{{ $idx }}" class="grid grid-cols-12 gap-2 mb-2 items-start p-3 bg-white rounded-xl border border-stone-200">
                                <div class="col-span-4">
                                    <input type="text" wire:model="pickupPoints.{{ $idx }}.name" placeholder="Name (e.g. Sandton City)"
                                        class="w-full px-2 py-1.5 text-sm border border-stone-300 rounded-lg focus:ring-1 focus:ring-green-500"/>
                                </div>
                                <div class="col-span-3">
                                    <input type="text" wire:model="pickupPoints.{{ $idx }}.address" placeholder="Address"
                                        class="w-full px-2 py-1.5 text-sm border border-stone-300 rounded-lg focus:ring-1 focus:ring-green-500"/>
                                </div>
                                <div class="col-span-2">
                                    <input type="time" wire:model="pickupPoints.{{ $idx }}.departure_time"
                                        class="w-full px-2 py-1.5 text-sm border border-stone-300 rounded-lg focus:ring-1 focus:ring-green-500"/>
                                </div>
                                <div class="col-span-2">
                                    <input type="number" wire:model="pickupPoints.{{ $idx }}.max_seats" min="1" placeholder="Seats"
                                        class="w-full px-2 py-1.5 text-sm border border-stone-300 rounded-lg focus:ring-1 focus:ring-green-500 text-center"/>
                                </div>
                                <div class="col-span-1 flex justify-center pt-1">
                                    <button type="button" wire:click="removePickupPoint({{ $idx }})"
                                        class="text-stone-400 hover:text-red-500 transition-colors text-sm">✕</button>
                                </div>
                            </div>
                            @empty
                            <p class="text-xs text-stone-400 italic">No pickup points yet — add at least one.</p>
                            @endforelse
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ─── STEP 4: Financials ─────────────────────────────────────────── --}}
        @elseif($step === 4)
        <div>
            <h2 class="text-lg font-bold text-stone-800 mb-1">Financials</h2>
            <p class="text-sm text-stone-500 mb-5">Budget your expenses and set your ticket price.</p>

            <div class="space-y-6">

                {{-- Expense line items --}}
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold text-stone-700">Expense Budget</h3>
                        <button type="button" wire:click="addExpense"
                            class="text-xs text-green-700 hover:text-green-900 font-semibold">+ Add expense</button>
                    </div>

                    @forelse($expenses as $idx => $expense)
                    <div wire:key="exp-{{ $idx }}" class="grid grid-cols-12 gap-2 mb-2 items-center">
                        <div class="col-span-5">
                            <input type="text" wire:model.live="expenses.{{ $idx }}.description"
                                placeholder="e.g. Bus hire"
                                class="w-full px-3 py-2 text-sm border border-stone-300 rounded-lg focus:ring-1 focus:ring-green-500"/>
                        </div>
                        <div class="col-span-3">
                            <select wire:model="expenses.{{ $idx }}.category"
                                class="w-full px-2 py-2 text-sm border border-stone-300 rounded-lg focus:ring-1 focus:ring-green-500 bg-white">
                                @foreach(['transport','permits','equipment','refreshments','marketing','other'] as $cat)
                                <option value="{{ $cat }}">{{ ucfirst($cat) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-3">
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-sm text-stone-400">R</span>
                                <input type="number" wire:model.live="expenses.{{ $idx }}.amount"
                                    placeholder="0.00" step="0.01" min="0"
                                    class="w-full pl-7 pr-3 py-2 text-sm border border-stone-300 rounded-lg focus:ring-1 focus:ring-green-500"/>
                            </div>
                        </div>
                        <div class="col-span-1 flex justify-center">
                            <button type="button" wire:click="removeExpense({{ $idx }})"
                                class="text-stone-400 hover:text-red-500 text-sm transition-colors">✕</button>
                        </div>
                    </div>
                    @empty
                    <p class="text-xs text-stone-400 italic mb-2">No expenses added — enter your costs to get an accurate price.</p>
                    @endforelse

                    <div class="flex justify-end pt-2 border-t border-stone-100 mt-2">
                        <span class="text-sm font-semibold text-stone-800">
                            Total expenses: R{{ number_format($this->totalExpenses, 2) }}
                        </span>
                    </div>
                </div>

                {{-- Pricing calculator --}}
                <div class="p-4 bg-green-50 rounded-xl border border-green-200 space-y-3">
                    <h3 class="text-sm font-semibold text-stone-700">Pricing Calculator</h3>

                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-stone-500">Break-even (at {{ $minCapacity }} people)</p>
                            <p class="text-lg font-bold text-stone-800">R{{ number_format($this->breakEvenPrice, 2) }}</p>
                        </div>
                        <div>
                            <label class="block text-stone-500 mb-1">Profit margin target</label>
                            <div class="flex items-center gap-2">
                                <input type="number" wire:model.live="targetMarginPct" min="0" max="300" step="1"
                                    class="w-20 px-2 py-1.5 border border-stone-300 rounded-lg focus:ring-1 focus:ring-green-500 text-center"/>
                                <span class="text-stone-500">%</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between py-2 border-t border-green-200">
                        <div>
                            <p class="text-sm text-stone-600">Suggested ticket price</p>
                            <p class="text-2xl font-bold text-green-700">R{{ number_format($this->suggestedPrice, 2) }}</p>
                        </div>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" wire:model.live="useManualPrice"
                                class="w-4 h-4 rounded border-stone-300 text-green-600"/>
                            <span class="text-sm text-stone-600">Set manually</span>
                        </label>
                    </div>

                    @if($useManualPrice)
                    <div class="flex items-center gap-2">
                        <span class="text-stone-500 font-medium">R</span>
                        <input type="number" wire:model.live="price" min="0" step="0.01" placeholder="{{ $this->suggestedPrice }}"
                            class="w-36 px-3 py-2 border border-green-400 rounded-lg focus:ring-2 focus:ring-green-500 font-bold text-lg"/>
                        @error('price') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    @endif
                </div>

                {{-- Revenue forecast --}}
                <div>
                    <h3 class="text-sm font-semibold text-stone-700 mb-3">Revenue Forecast at R{{ number_format($this->effectivePrice, 2) }}/ticket</h3>
                    <div class="grid grid-cols-3 gap-3">
                        @foreach($this->revenueForecast as $scenario => $data)
                        @php
                            $positive = $data['profit'] >= 0;
                            $bg = $scenario === 'expected' ? 'bg-green-50 border-green-300' : 'bg-stone-50 border-stone-200';
                        @endphp
                        <div class="p-3 rounded-xl border {{ $bg }} text-center">
                            <p class="text-xs text-stone-500 uppercase tracking-wide font-semibold">{{ ucfirst($scenario) }}</p>
                            <p class="text-sm text-stone-600 mt-1">{{ $data['headcount'] }} people</p>
                            <p class="text-lg font-bold mt-1 {{ $positive ? 'text-green-700' : 'text-red-600' }}">
                                {{ $positive ? '+' : '' }}R{{ number_format(abs($data['profit']), 0) }}
                            </p>
                            <p class="text-xs text-stone-400 mt-0.5">{{ $positive ? 'profit' : 'loss' }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>

        {{-- ─── STEP 5: Review & Publish ───────────────────────────────────── --}}
        @elseif($step === 5)
        <div>
            <h2 class="text-lg font-bold text-stone-800 mb-1">Review &amp; Publish</h2>
            <p class="text-sm text-stone-500 mb-5">Everything looks good? Hit publish to go live.</p>

            @php $p = $plan; @endphp
            @if($p)
            <div class="space-y-4 text-sm">

                <div class="grid grid-cols-2 gap-4">
                    <div class="p-4 bg-stone-50 rounded-xl">
                        <p class="text-xs font-semibold text-stone-500 uppercase tracking-wide mb-2">Event</p>
                        <p class="font-bold text-stone-800 text-base">{{ $p->title }}</p>
                        @if($p->tagline) <p class="text-stone-500 italic mt-0.5">{{ $p->tagline }}</p> @endif
                        <p class="mt-1 capitalize text-stone-600">{{ str_replace('_',' ',$p->type) }}</p>
                    </div>
                    <div class="p-4 bg-stone-50 rounded-xl">
                        <p class="text-xs font-semibold text-stone-500 uppercase tracking-wide mb-2">Date &amp; Place</p>
                        <p class="font-medium text-stone-800">{{ $p->departs_at?->format('D, d M Y H:i') ?? '—' }}</p>
                        <p class="text-stone-600 mt-0.5">{{ $p->location ?? '—' }}</p>
                        @if($p->meeting_point) <p class="text-stone-500 mt-0.5">Meet: {{ $p->meeting_point }}</p> @endif
                    </div>
                    <div class="p-4 bg-stone-50 rounded-xl">
                        <p class="text-xs font-semibold text-stone-500 uppercase tracking-wide mb-2">Capacity</p>
                        <p class="font-medium text-stone-800">{{ $p->min_capacity }}–{{ $p->max_capacity }} people</p>
                        <p class="capitalize text-stone-600">{{ $p->difficulty ?? 'moderate' }}</p>
                    </div>
                    <div class="p-4 bg-green-50 rounded-xl border border-green-200">
                        <p class="text-xs font-semibold text-stone-500 uppercase tracking-wide mb-2">Pricing</p>
                        <p class="font-bold text-green-700 text-lg">R{{ number_format($p->price ?? $p->suggestedPrice(), 2) }}</p>
                        @if($p->includes_transport)
                        <p class="text-amber-700 mt-0.5">+ R{{ number_format($p->transport_fee, 2) }} transport</p>
                        @endif
                    </div>
                </div>

                @if(collect($p->expenses ?? [])->isNotEmpty())
                <div class="p-4 bg-stone-50 rounded-xl">
                    <p class="text-xs font-semibold text-stone-500 uppercase tracking-wide mb-2">Expenses</p>
                    @foreach($p->expenses as $e)
                    <div class="flex justify-between text-stone-600 py-0.5">
                        <span>{{ $e['description'] }}</span>
                        <span class="font-medium">R{{ number_format($e['amount'], 2) }}</span>
                    </div>
                    @endforeach
                    <div class="flex justify-between font-bold text-stone-800 border-t border-stone-200 pt-1 mt-1">
                        <span>Total</span>
                        <span>R{{ number_format($p->totalExpenses(), 2) }}</span>
                    </div>
                </div>
                @endif

                @if($p->includes_transport && !empty($p->transport_pickup_points))
                <div class="p-4 bg-amber-50 rounded-xl border border-amber-200">
                    <p class="text-xs font-semibold text-stone-500 uppercase tracking-wide mb-2">Pick-up Points</p>
                    @foreach($p->transport_pickup_points as $pp)
                    <div class="flex justify-between text-stone-600 py-0.5">
                        <span>{{ $pp['name'] }}</span>
                        <span>{{ $pp['departure_time'] }} &bull; {{ $pp['max_seats'] }} seats</span>
                    </div>
                    @endforeach
                </div>
                @endif

                @error('publish') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror

                <button
                    wire:click="publish"
                    wire:confirm="Publish this event? It will go live immediately."
                    wire:loading.attr="disabled"
                    class="w-full bg-green-600 hover:bg-green-700 disabled:opacity-50 text-white font-bold py-3.5 rounded-xl transition-colors text-base"
                >
                    <span wire:loading.remove>🚀 Publish Event</span>
                    <span wire:loading>Publishing&hellip;</span>
                </button>
            </div>
            @else
            <p class="text-stone-500 text-sm">Save the previous steps to unlock the review.</p>
            @endif
        </div>
        @endif

    </div>{{-- /panel --}}

    {{-- Navigation buttons --}}
    <div class="flex justify-between mt-5">
        <button
            wire:click="previousStep"
            class="{{ $step === 1 ? 'invisible' : '' }} flex items-center gap-2 text-sm text-stone-600 hover:text-stone-800 px-4 py-2.5 rounded-xl hover:bg-white transition-colors"
        >
            &larr; Back
        </button>

        @if($step < 5)
        <button
            wire:click="nextStep"
            wire:loading.attr="disabled"
            class="bg-green-600 hover:bg-green-700 disabled:opacity-50 text-white font-semibold px-6 py-2.5 rounded-xl transition-colors"
        >
            <span wire:loading.remove>Save &amp; Continue &rarr;</span>
            <span wire:loading>Saving&hellip;</span>
        </button>
        @endif
    </div>

</div>
