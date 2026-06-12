@php
$stepMeta = [
    1 => ['icon' => '✦', 'sub' => 'Name, type & poster colour'],
    2 => ['icon' => '◎', 'sub' => 'Date, place & difficulty'],
    3 => ['icon' => '◈', 'sub' => 'Headcount & transport'],
    4 => ['icon' => '◉', 'sub' => 'Budget, pricing & forecast'],
    5 => ['icon' => '⬡', 'sub' => 'Preview & go live'],
];
@endphp

<div class="max-w-5xl mx-auto">

    {{-- Back breadcrumb --}}
    <a href="{{ route('planner.index') }}"
        class="inline-flex items-center gap-1.5 text-xs text-stone-400 hover:text-stone-600 mb-4 transition-colors">
        &#8592; All plans
    </a>

    {{-- Two-panel wrapper --}}
    <div class="flex rounded-2xl overflow-hidden shadow-2xl" style="min-height: 620px; border: 1px solid #1a2e20;">

        {{-- ════ LEFT SIDEBAR ════ --}}
        <div class="w-64 flex-shrink-0 flex flex-col" style="background: #0d1e13;">

            {{-- Brand header --}}
            <div class="px-6 pt-7 pb-5" style="border-bottom: 1px solid rgba(201,168,76,0.12);">
                <div class="flex items-center gap-2.5 mb-1">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-sm"
                        style="border: 1px solid rgba(201,168,76,0.4); background: rgba(201,168,76,0.08);">🌿</div>
                    <div>
                        <p class="text-xs font-bold tracking-widest uppercase" style="color: #c9a84c; letter-spacing: 0.15em;">BushXplorer</p>
                        <p class="text-[10px]" style="color: #5a7060;">Event Planner</p>
                    </div>
                </div>

                {{-- Plan title chip --}}
                @if($title)
                <div class="mt-3 px-3 py-2 rounded-lg" style="background: rgba(201,168,76,0.07); border: 1px solid rgba(201,168,76,0.18);">
                    <p class="text-[10px] font-bold uppercase tracking-widest mb-0.5" style="color: #c9a84c; letter-spacing: 0.15em;">Working on</p>
                    <p class="text-xs font-semibold truncate" style="color: #d4cfc8;">{{ $title }}</p>
                </div>
                @endif
            </div>

            {{-- Steps navigation --}}
            <nav class="flex-1 px-4 py-5 space-y-0.5">
                @foreach($this->stepLabels as $num => $label)
                @php
                    $planMaxStep = $planId ? ($plan?->current_step ?? $step) : $step;
                    $isDone   = $num < $step || ($planId && $planMaxStep > $num);
                    $isActive = $num === $step;
                    $canClick = $isDone || $isActive;
                @endphp

                {{-- Step button --}}
                <button
                    @if($canClick) wire:click="goToStep({{ $num }})" @endif
                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all text-left
                           {{ $isActive ? 'cursor-default' : ($canClick ? 'hover:bg-white/5 cursor-pointer' : 'cursor-not-allowed opacity-40') }}"
                    style="{{ $isActive ? 'background: rgba(201,168,76,0.1); border: 1px solid rgba(201,168,76,0.25);' : 'border: 1px solid transparent;' }}"
                >
                    {{-- Circle --}}
                    <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 transition-all"
                        style="
                            {{ $isDone   ? 'background: #c9a84c; color: #0a0a0a;' : '' }}
                            {{ $isActive ? 'border: 2px solid #c9a84c; color: #c9a84c; background: transparent;' : '' }}
                            {{ !$isDone && !$isActive ? 'border: 1px solid #2d4435; color: #3d5545;' : '' }}
                        ">
                        @if($isDone)
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                            </svg>
                        @else
                            {{ $num }}
                        @endif
                    </div>

                    {{-- Label --}}
                    <div class="min-w-0">
                        <p class="text-xs font-semibold truncate transition-colors"
                            style="{{ $isActive ? 'color: #c9a84c;' : ($isDone ? 'color: #b5bfb0;' : 'color: #3d5040;') }}">
                            {{ $label }}
                        </p>
                        <p class="text-[10px] truncate mt-0.5" style="color: #2d4030;">
                            {{ $stepMeta[$num]['sub'] }}
                        </p>
                    </div>
                </button>

                {{-- Connector line --}}
                @if(!$loop->last)
                <div class="ml-[24px] w-px h-2.5 transition-colors"
                    style="{{ $num < $step ? 'background: rgba(201,168,76,0.35);' : 'background: #1a2e1e;' }}">
                </div>
                @endif

                @endforeach
            </nav>

            {{-- Bottom saved indicator --}}
            <div class="px-6 py-4" style="border-top: 1px solid rgba(255,255,255,0.05);">
                @if($planId)
                <div class="flex items-center gap-2">
                    <div class="w-1.5 h-1.5 rounded-full bg-green-400"></div>
                    <span class="text-[10px]" style="color: #4a6050;">Progress saved</span>
                </div>
                @else
                <p class="text-[10px]" style="color: #3a4d3e;">Saves on each step</p>
                @endif
            </div>

        </div>{{-- /sidebar --}}

        {{-- ════ RIGHT PANEL ════ --}}
        <div class="flex-1 bg-white flex flex-col min-w-0">

            {{-- Step header --}}
            <div class="px-8 pt-7 pb-5 border-b border-stone-100">
                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-amber-600 mb-1">
                    Step {{ $step }} &nbsp;/&nbsp; 5
                </p>
                <h2 class="text-2xl font-bold text-stone-900 tracking-tight">{{ $this->stepLabels[$step] }}</h2>
                <p class="text-sm text-stone-400 mt-0.5">{{ $stepMeta[$step]['sub'] }}</p>
            </div>

            {{-- ─────────────────────────────────────────────────── --}}
            {{-- STEP CONTENT                                         --}}
            {{-- ─────────────────────────────────────────────────── --}}
            <div class="flex-1 px-8 py-6 overflow-y-auto">

                @php
                    function plannerLabel(string $text, bool $required = false): string {
                        $req = $required ? '<span style="color:#ef4444;">*</span>' : '';
                        return '<label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-stone-400 mb-1.5">'.$text.' '.$req.'</label>';
                    }
                @endphp

                {{-- ─── STEP 1: Concept ────────────────────────── --}}
                @if($step === 1)
                <div class="space-y-5 max-w-xl">

                    <div class="grid grid-cols-5 gap-4">
                        <div class="col-span-3">
                            <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-stone-400 mb-1.5">
                                Event title <span class="text-red-400">*</span>
                            </label>
                            <input type="text" wire:model="title"
                                placeholder="e.g. Drakensberg Sunrise Hike"
                                class="w-full px-4 py-3 border border-stone-200 rounded-xl text-stone-900 font-medium text-sm bg-white
                                       focus:outline-none focus:ring-2 focus:ring-green-600/20 focus:border-green-700
                                       placeholder:text-stone-300 transition-colors"/>
                            @error('title') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div class="col-span-2">
                            <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-stone-400 mb-1.5">
                                Type <span class="text-red-400">*</span>
                            </label>
                            <select wire:model="type"
                                class="w-full px-4 py-3 border border-stone-200 rounded-xl text-stone-900 font-medium text-sm bg-white
                                       focus:outline-none focus:ring-2 focus:ring-green-600/20 focus:border-green-700
                                       transition-colors appearance-none">
                                @foreach(['hike'=>'Hike','workshop'=>'Workshop','social'=>'Social','corporate'=>'Corporate','multi_day'=>'Multi-Day'] as $v=>$l)
                                <option value="{{ $v }}">{{ $l }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-stone-400 mb-1.5">
                            Tagline <span class="text-stone-300 normal-case tracking-normal font-normal">(appears on the poster)</span>
                        </label>
                        <input type="text" wire:model="tagline"
                            placeholder="Push your limits. Find your trail."
                            class="w-full px-4 py-3 border border-stone-200 rounded-xl text-stone-900 font-medium text-sm bg-white
                                   focus:outline-none focus:ring-2 focus:ring-green-600/20 focus:border-green-700
                                   placeholder:text-stone-300 transition-colors"/>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-stone-400 mb-1.5">
                            Description <span class="text-stone-300 normal-case tracking-normal font-normal">(shown on the booking page)</span>
                        </label>
                        <textarea wire:model="description" rows="3"
                            placeholder="What makes this event special? What will participants experience?"
                            class="w-full px-4 py-3 border border-stone-200 rounded-xl text-stone-900 font-medium text-sm bg-white
                                   focus:outline-none focus:ring-2 focus:ring-green-600/20 focus:border-green-700
                                   placeholder:text-stone-300 transition-colors resize-none leading-relaxed"></textarea>
                    </div>

                    <div class="p-4 rounded-xl bg-amber-50/60 border border-amber-100">
                        <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-amber-600 mb-1.5">
                            Concept notes <span class="text-amber-400 normal-case tracking-normal font-normal">(private — your raw idea)</span>
                        </label>
                        <textarea wire:model="conceptNotes" rows="2"
                            placeholder="Why this route? What vibe? Any inspiration or references?"
                            class="w-full px-4 py-3 border border-amber-200 rounded-xl text-stone-800 font-medium text-sm bg-amber-50
                                   focus:outline-none focus:ring-2 focus:ring-amber-400/20 focus:border-amber-400
                                   placeholder:text-amber-300 transition-colors resize-none leading-relaxed"></textarea>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-stone-400 mb-3">
                            Poster colour theme
                        </label>
                        <div class="flex gap-3 flex-wrap">
                            @foreach([
                                '#166534' => ['name'=>'Forest',  'light'=>'#dcfce7'],
                                '#1e3a5f' => ['name'=>'Navy',    'light'=>'#dbeafe'],
                                '#7c2d12' => ['name'=>'Rust',    'light'=>'#fee2e2'],
                                '#4a044e' => ['name'=>'Plum',    'light'=>'#f3e8ff'],
                                '#134e4a' => ['name'=>'Teal',    'light'=>'#ccfbf1'],
                                '#1c1917' => ['name'=>'Onyx',    'light'=>'#e7e5e4'],
                                '#713f12' => ['name'=>'Bourbon', 'light'=>'#fef3c7'],
                                '#0c4a6e' => ['name'=>'Ocean',   'light'=>'#e0f2fe'],
                            ] as $hex => $meta)
                            <button type="button" wire:click="$set('coverColor','{{ $hex }}')"
                                class="flex flex-col items-center gap-1.5 transition-all">
                                <div class="w-9 h-9 rounded-full transition-all shadow-md {{ $coverColor === $hex ? 'ring-2 ring-offset-2 ring-stone-800 scale-110' : 'hover:scale-105' }}"
                                    style="background-color: {{ $hex }}"></div>
                                <span class="text-[9px] font-semibold uppercase tracking-wide {{ $coverColor === $hex ? 'text-stone-700' : 'text-stone-400' }}">
                                    {{ $meta['name'] }}
                                </span>
                            </button>
                            @endforeach
                        </div>
                    </div>

                </div>

                {{-- ─── STEP 2: Logistics ──────────────────────── --}}
                @elseif($step === 2)
                <div class="space-y-5 max-w-xl">

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-stone-400 mb-1.5">
                                Location / Area <span class="text-red-400">*</span>
                            </label>
                            <input type="text" wire:model="location"
                                placeholder="e.g. Drakensberg, KZN"
                                class="w-full px-4 py-3 border border-stone-200 rounded-xl text-stone-900 font-medium text-sm bg-white
                                       focus:outline-none focus:ring-2 focus:ring-green-600/20 focus:border-green-700
                                       placeholder:text-stone-300 transition-colors"/>
                            @error('location') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-stone-400 mb-1.5">
                                Trail Name <span class="text-stone-300 normal-case tracking-normal font-normal">(optional)</span>
                            </label>
                            <input type="text" wire:model="trailName"
                                placeholder="e.g. Tugela Falls Trail"
                                class="w-full px-4 py-3 border border-stone-200 rounded-xl text-stone-900 font-medium text-sm bg-white
                                       focus:outline-none focus:ring-2 focus:ring-green-600/20 focus:border-green-700
                                       placeholder:text-stone-300 transition-colors"/>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-stone-400 mb-1.5">
                            Difficulty <span class="text-red-400">*</span>
                        </label>
                        <div class="grid grid-cols-4 gap-2">
                            @foreach(['easy'=>['🟢','text-green-700','bg-green-50 border-green-300'],'moderate'=>['🟡','text-amber-700','bg-amber-50 border-amber-300'],'hard'=>['🟠','text-orange-700','bg-orange-50 border-orange-300'],'extreme'=>['🔴','text-red-700','bg-red-50 border-red-300']] as $val=>[$emoji,$tc,$bc])
                            <button type="button" wire:click="$set('difficulty','{{ $val }}')"
                                class="py-2.5 rounded-xl border text-xs font-bold uppercase tracking-wide transition-all
                                       {{ $difficulty === $val ? $bc.' '.$tc.' ring-2 ring-offset-1 ring-current' : 'border-stone-200 text-stone-400 bg-white hover:border-stone-300' }}">
                                {{ $emoji }} {{ ucfirst($val) }}
                            </button>
                            @endforeach
                        </div>
                        @error('difficulty') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-stone-400 mb-1.5">
                                Departs <span class="text-red-400">*</span>
                            </label>
                            <input type="datetime-local" wire:model="departsAt"
                                class="w-full px-4 py-3 border border-stone-200 rounded-xl text-stone-900 font-medium text-sm bg-white
                                       focus:outline-none focus:ring-2 focus:ring-green-600/20 focus:border-green-700
                                       transition-colors"/>
                            @error('departsAt') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-stone-400 mb-1.5">
                                Returns <span class="text-stone-300 normal-case tracking-normal font-normal">(optional)</span>
                            </label>
                            <input type="datetime-local" wire:model="returnsAt"
                                class="w-full px-4 py-3 border border-stone-200 rounded-xl text-stone-900 font-medium text-sm bg-white
                                       focus:outline-none focus:ring-2 focus:ring-green-600/20 focus:border-green-700
                                       transition-colors"/>
                            @error('returnsAt') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-stone-400 mb-1.5">
                            Meeting Point
                        </label>
                        <input type="text" wire:model="meetingPoint"
                            placeholder="e.g. Amphitheatre parking area, Royal Natal"
                            class="w-full px-4 py-3 border border-stone-200 rounded-xl text-stone-900 font-medium text-sm bg-white
                                   focus:outline-none focus:ring-2 focus:ring-green-600/20 focus:border-green-700
                                   placeholder:text-stone-300 transition-colors"/>
                    </div>

                </div>

                {{-- ─── STEP 3: Capacity & Transport ───────────── --}}
                @elseif($step === 3)
                <div class="space-y-5 max-w-xl">

                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-stone-400 mb-1.5">
                                Max spots <span class="text-red-400">*</span>
                            </label>
                            <input type="number" wire:model="maxCapacity" min="2"
                                class="w-full px-4 py-3 border border-stone-200 rounded-xl text-stone-900 font-bold text-xl text-center bg-white
                                       focus:outline-none focus:ring-2 focus:ring-green-600/20 focus:border-green-700 transition-colors"/>
                            @error('maxCapacity') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-stone-400 mb-1.5">
                                Min to run <span class="text-red-400">*</span>
                            </label>
                            <input type="number" wire:model="minCapacity" min="1"
                                class="w-full px-4 py-3 border border-stone-200 rounded-xl text-stone-900 font-bold text-xl text-center bg-white
                                       focus:outline-none focus:ring-2 focus:ring-green-600/20 focus:border-green-700 transition-colors"/>
                            @error('minCapacity') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-stone-400 mb-1.5">
                                Points awarded
                            </label>
                            <input type="number" wire:model="pointsAwarded" min="0"
                                class="w-full px-4 py-3 border border-stone-200 rounded-xl text-stone-900 font-bold text-xl text-center bg-white
                                       focus:outline-none focus:ring-2 focus:ring-green-600/20 focus:border-green-700 transition-colors"/>
                        </div>
                    </div>

                    {{-- ── Transport toggle ── --}}
                    <div class="rounded-xl overflow-hidden border {{ $includesTransport ? 'border-amber-300' : 'border-stone-200' }} transition-colors">
                        <button type="button" wire:click="$set('includesTransport', {{ $includesTransport ? 'false' : 'true' }})"
                            class="w-full flex items-center justify-between p-4 text-left transition-colors
                                   {{ $includesTransport ? 'bg-amber-50' : 'bg-stone-50 hover:bg-stone-100' }}">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center text-lg
                                            {{ $includesTransport ? 'bg-amber-100' : 'bg-stone-200' }}">🚌</div>
                                <div>
                                    <p class="text-sm font-semibold text-stone-800">Include transport</p>
                                    <p class="text-xs text-stone-400 mt-0.5">Members opt in at booking &amp; choose pickup point</p>
                                </div>
                            </div>
                            <div class="w-12 h-6 rounded-full flex items-center transition-all flex-shrink-0
                                        {{ $includesTransport ? 'bg-amber-400 justify-end pr-0.5' : 'bg-stone-300 justify-start pl-0.5' }}">
                                <div class="w-5 h-5 rounded-full bg-white shadow-sm"></div>
                            </div>
                        </button>

                        @if($includesTransport)
                        <div class="p-4 border-t border-amber-200 bg-white space-y-4">
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-stone-400 mb-1.5">
                                    Transport fee per person (R)
                                </label>
                                <div class="relative w-36">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-stone-400 font-semibold text-sm">R</span>
                                    <input type="number" wire:model="transportFee" min="0" step="0.01"
                                        class="w-full pl-8 pr-4 py-3 border border-stone-200 rounded-xl text-stone-900 font-bold text-center bg-white
                                               focus:outline-none focus:ring-2 focus:ring-amber-400/30 focus:border-amber-400 transition-colors"/>
                                </div>
                                @error('transportFee') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            {{-- Pickup points --}}
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-stone-400">Pickup Points</label>
                                    <button type="button" wire:click="addPickupPoint"
                                        class="text-xs font-semibold text-amber-700 hover:text-amber-900 flex items-center gap-1 transition-colors">
                                        + Add stop
                                    </button>
                                </div>

                                @forelse($pickupPoints as $idx => $pp)
                                <div wire:key="pp-{{ $idx }}" class="grid grid-cols-12 gap-2 mb-2 p-3 bg-stone-50 rounded-xl border border-stone-100">
                                    <div class="col-span-4">
                                        <p class="text-[9px] font-bold uppercase tracking-wide text-stone-400 mb-1">Name</p>
                                        <input type="text" wire:model="pickupPoints.{{ $idx }}.name" placeholder="e.g. Sandton City"
                                            class="w-full px-2.5 py-1.5 text-xs border border-stone-200 rounded-lg bg-white focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400/20"/>
                                    </div>
                                    <div class="col-span-3">
                                        <p class="text-[9px] font-bold uppercase tracking-wide text-stone-400 mb-1">Address</p>
                                        <input type="text" wire:model="pickupPoints.{{ $idx }}.address" placeholder="Street / landmark"
                                            class="w-full px-2.5 py-1.5 text-xs border border-stone-200 rounded-lg bg-white focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400/20"/>
                                    </div>
                                    <div class="col-span-2">
                                        <p class="text-[9px] font-bold uppercase tracking-wide text-stone-400 mb-1">Time</p>
                                        <input type="time" wire:model="pickupPoints.{{ $idx }}.departure_time"
                                            class="w-full px-2 py-1.5 text-xs border border-stone-200 rounded-lg bg-white focus:outline-none focus:border-amber-400"/>
                                    </div>
                                    <div class="col-span-2">
                                        <p class="text-[9px] font-bold uppercase tracking-wide text-stone-400 mb-1">Seats</p>
                                        <input type="number" wire:model="pickupPoints.{{ $idx }}.max_seats" min="1"
                                            class="w-full px-2 py-1.5 text-xs border border-stone-200 rounded-lg bg-white text-center focus:outline-none focus:border-amber-400"/>
                                    </div>
                                    <div class="col-span-1 flex items-end justify-center pb-1">
                                        <button type="button" wire:click="removePickupPoint({{ $idx }})"
                                            class="text-stone-300 hover:text-red-400 transition-colors text-sm leading-none">✕</button>
                                    </div>
                                </div>
                                @empty
                                <p class="text-xs text-stone-300 italic py-2">No stops yet — add at least one pickup point.</p>
                                @endforelse
                            </div>
                        </div>
                        @endif
                    </div>

                    {{-- ── Accommodation toggle ── --}}
                    <div class="rounded-xl overflow-hidden border {{ $includesAccommodation ? 'border-indigo-300' : 'border-stone-200' }} transition-colors">
                        <button type="button" wire:click="$set('includesAccommodation', {{ $includesAccommodation ? 'false' : 'true' }})"
                            class="w-full flex items-center justify-between p-4 text-left transition-colors
                                   {{ $includesAccommodation ? 'bg-indigo-50' : 'bg-stone-50 hover:bg-stone-100' }}">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center text-lg
                                            {{ $includesAccommodation ? 'bg-indigo-100' : 'bg-stone-200' }}">🛏</div>
                                <div>
                                    <p class="text-sm font-semibold text-stone-800">Include overnight accommodation</p>
                                    <p class="text-xs text-stone-400 mt-0.5">Weekend or multi-day trip — lodge/camp cost added per person</p>
                                </div>
                            </div>
                            <div class="w-12 h-6 rounded-full flex items-center transition-all flex-shrink-0
                                        {{ $includesAccommodation ? 'bg-indigo-500 justify-end pr-0.5' : 'bg-stone-300 justify-start pl-0.5' }}">
                                <div class="w-5 h-5 rounded-full bg-white shadow-sm"></div>
                            </div>
                        </button>

                        @if($includesAccommodation)
                        <div class="p-4 border-t border-indigo-100 bg-white space-y-4">

                            {{-- Nights + cost row --}}
                            <div class="flex items-end gap-4">
                                <div>
                                    <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-stone-400 mb-1.5">
                                        Number of nights
                                    </label>
                                    <div class="flex items-center gap-2">
                                        <button type="button" wire:click="$set('nights', max(1, nights - 1))"
                                            class="w-8 h-8 rounded-lg bg-stone-100 hover:bg-stone-200 text-stone-600 font-bold text-base flex items-center justify-center transition-colors">−</button>
                                        <span class="w-8 text-center font-bold text-stone-900 text-lg">{{ $nights }}</span>
                                        <button type="button" wire:click="$set('nights', nights + 1)"
                                            class="w-8 h-8 rounded-lg bg-stone-100 hover:bg-stone-200 text-stone-600 font-bold text-base flex items-center justify-center transition-colors">+</button>
                                    </div>
                                    @error('nights') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                </div>
                                <div class="flex-1">
                                    <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-stone-400 mb-1.5">
                                        Accommodation cost per person / night (R)
                                    </label>
                                    <div class="relative w-40">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-stone-400 font-semibold text-sm">R</span>
                                        <input type="number" wire:model="accommodationCostPerPerson" min="0" step="0.01"
                                            class="w-full pl-8 pr-4 py-3 border border-stone-200 rounded-xl text-stone-900 font-bold text-center bg-white
                                                   focus:outline-none focus:ring-2 focus:ring-indigo-400/30 focus:border-indigo-400 transition-colors"/>
                                    </div>
                                    @error('accommodationCostPerPerson') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                </div>
                                @if((float)$accommodationCostPerPerson > 0)
                                <div class="pb-1">
                                    <p class="text-[10px] text-stone-400 uppercase tracking-wide font-bold">Total / person</p>
                                    <p class="text-lg font-bold text-indigo-700">R{{ number_format((float)$accommodationCostPerPerson * $nights, 2) }}</p>
                                    <p class="text-[10px] text-stone-400">{{ $nights }} night{{ $nights > 1 ? 's' : '' }}</p>
                                </div>
                                @endif
                            </div>

                            {{-- Lodge name --}}
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-stone-400 mb-1.5">
                                    Accommodation name / venue
                                </label>
                                <input type="text" wire:model="accommodationName"
                                    placeholder="e.g. Magalies Mountain Lodge, Camp Shongwe"
                                    class="w-full px-4 py-3 border border-stone-200 rounded-xl text-stone-900 font-medium text-sm bg-white
                                           focus:outline-none focus:ring-2 focus:ring-indigo-400/20 focus:border-indigo-400
                                           placeholder:text-stone-300 transition-colors"/>
                            </div>

                            {{-- What is included / What to bring --}}
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-stone-400 mb-1.5">
                                        What's included
                                    </label>
                                    <textarea wire:model="whatIsIncluded" rows="3"
                                        placeholder="e.g. 2 nights accommodation, Friday dinner, Saturday breakfast &amp; lunch, guided hike"
                                        class="w-full px-3 py-2.5 border border-stone-200 rounded-xl text-stone-900 text-xs bg-white resize-none
                                               focus:outline-none focus:ring-2 focus:ring-indigo-400/20 focus:border-indigo-400
                                               placeholder:text-stone-300 transition-colors"></textarea>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-stone-400 mb-1.5">
                                        What to bring
                                    </label>
                                    <textarea wire:model="whatToBring" rows="3"
                                        placeholder="e.g. Sleeping bag, toiletries, torch, snacks, change of clothes"
                                        class="w-full px-3 py-2.5 border border-stone-200 rounded-xl text-stone-900 text-xs bg-white resize-none
                                               focus:outline-none focus:ring-2 focus:ring-indigo-400/20 focus:border-indigo-400
                                               placeholder:text-stone-300 transition-colors"></textarea>
                                </div>
                            </div>

                        </div>
                        @endif
                    </div>

                </div>

                {{-- ─── STEP 4: Financials ─────────────────────── --}}
                @elseif($step === 4)
                <div class="flex gap-5" style="min-height: 380px;">

                    {{-- Left: expense builder --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between mb-3">
                            <label class="text-[10px] font-bold uppercase tracking-[0.15em] text-stone-400">Expense Budget</label>
                            <button type="button" wire:click="addExpense"
                                class="text-xs font-semibold text-green-700 hover:text-green-900 flex items-center gap-1 transition-colors">
                                + Add expense
                            </button>
                        </div>

                        @forelse($expenses as $idx => $expense)
                        <div wire:key="exp-{{ $idx }}" class="grid grid-cols-12 gap-1.5 mb-2 items-center">
                            <div class="col-span-5">
                                <input type="text" wire:model.live="expenses.{{ $idx }}.description"
                                    placeholder="e.g. Bus hire"
                                    class="w-full px-3 py-2 text-xs border border-stone-200 rounded-lg bg-white text-stone-900 font-medium
                                           focus:outline-none focus:ring-1 focus:ring-green-600/20 focus:border-green-600 placeholder:text-stone-300"/>
                            </div>
                            <div class="col-span-3">
                                <select wire:model="expenses.{{ $idx }}.category"
                                    class="w-full px-2 py-2 text-xs border border-stone-200 rounded-lg bg-white text-stone-700
                                           focus:outline-none focus:border-green-600 appearance-none">
                                    @foreach(['transport'=>'Transport','permits'=>'Permits','equipment'=>'Equipment','refreshments'=>'Food','accommodation'=>'Accommodation','marketing'=>'Marketing','other'=>'Other'] as $v=>$l)
                                    <option value="{{ $v }}">{{ $l }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-span-3">
                                <div class="relative">
                                    <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-stone-400 text-xs">R</span>
                                    <input type="number" wire:model.live="expenses.{{ $idx }}.amount"
                                        placeholder="0" step="0.01" min="0"
                                        class="w-full pl-6 pr-2 py-2 text-xs border border-stone-200 rounded-lg bg-white text-stone-900 font-semibold
                                               focus:outline-none focus:ring-1 focus:ring-green-600/20 focus:border-green-600"/>
                                </div>
                            </div>
                            <div class="col-span-1 flex justify-center">
                                <button type="button" wire:click="removeExpense({{ $idx }})"
                                    class="text-stone-300 hover:text-red-400 transition-colors text-sm">✕</button>
                            </div>
                        </div>
                        @empty
                        <div class="py-6 text-center border-2 border-dashed border-stone-200 rounded-xl">
                            <p class="text-xs text-stone-400">No expenses yet</p>
                            <p class="text-[10px] text-stone-300 mt-0.5">Add costs to get an accurate price</p>
                        </div>
                        @endforelse

                        {{-- Total --}}
                        <div class="flex justify-end items-center gap-3 pt-3 mt-1 border-t border-stone-100">
                            <span class="text-xs text-stone-400 uppercase tracking-wide font-semibold">Total expenses</span>
                            <span class="text-base font-bold text-stone-900">R{{ number_format($this->totalExpenses, 2) }}</span>
                        </div>

                        {{-- Margin + manual price --}}
                        <div class="mt-5 space-y-3">
                            <div class="flex items-center gap-4">
                                <div class="flex-1">
                                    <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-stone-400 mb-1.5">
                                        Profit margin target
                                    </label>
                                    <div class="flex items-center gap-2">
                                        <input type="range" wire:model.live="targetMarginPct" min="0" max="150" step="5"
                                            class="flex-1 accent-green-700"/>
                                        <span class="text-sm font-bold text-stone-800 w-12 text-right">{{ $targetMarginPct }}%</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <button type="button" wire:click="$toggle('useManualPrice')"
                                    class="relative w-10 h-5 rounded-full transition-all flex-shrink-0
                                           {{ $useManualPrice ? 'bg-green-600' : 'bg-stone-300' }}">
                                    <span class="absolute top-0.5 w-4 h-4 rounded-full bg-white shadow transition-all
                                                 {{ $useManualPrice ? 'right-0.5' : 'left-0.5' }}"></span>
                                </button>
                                <label class="text-xs text-stone-600 font-medium cursor-pointer" wire:click="$toggle('useManualPrice')">
                                    Set ticket price manually
                                </label>
                                @if($useManualPrice)
                                <div class="relative ml-2">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-stone-500 text-sm font-semibold">R</span>
                                    <input type="number" wire:model.live="price" min="0" step="0.01"
                                        class="pl-7 pr-3 py-2 w-28 border border-green-400 rounded-lg bg-white text-stone-900 font-bold text-sm
                                               focus:outline-none focus:ring-2 focus:ring-green-600/20 focus:border-green-700"/>
                                </div>
                                @error('price') <p class="text-xs text-red-500 ml-2">{{ $message }}</p> @enderror
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Right: live calculator panel --}}
                    <div class="w-52 flex-shrink-0 rounded-2xl flex flex-col" style="background: #0d1e13;">
                        <div class="px-4 py-4" style="border-bottom: 1px solid rgba(201,168,76,0.15);">
                            <p class="text-[9px] font-bold uppercase tracking-[0.2em]" style="color: #c9a84c;">Pricing Calculator</p>
                        </div>

                        <div class="flex-1 px-4 py-4 space-y-4">
                            {{-- Break even --}}
                            <div>
                                <p class="text-[9px] uppercase tracking-widest font-bold mb-1" style="color: #4a6a52;">Break-even price</p>
                                <p class="text-2xl font-bold" style="color: #f5f0e8;">R{{ number_format($this->breakEvenPrice, 2) }}</p>
                                <p class="text-[10px] mt-0.5" style="color: #3d5545;">at min {{ $minCapacity }} people</p>
                            </div>

                            <div style="height:1px; background: rgba(201,168,76,0.12);"></div>

                            {{-- Suggested --}}
                            <div>
                                <p class="text-[9px] uppercase tracking-widest font-bold mb-1" style="color: #4a6a52;">Suggested price</p>
                                <p class="text-3xl font-bold" style="color: #c9a84c;">R{{ number_format($this->suggestedPrice, 2) }}</p>
                                <p class="text-[10px] mt-0.5" style="color: #3d5545;">+{{ $targetMarginPct }}% margin</p>
                            </div>

                            @if($useManualPrice && $price !== '')
                            <div class="px-3 py-2 rounded-lg" style="background: rgba(201,168,76,0.1); border: 1px solid rgba(201,168,76,0.25);">
                                <p class="text-[9px] uppercase tracking-widest font-bold mb-0.5" style="color: #c9a84c;">Your price</p>
                                <p class="text-xl font-bold" style="color: #e8d08a;">R{{ number_format((float)$price, 2) }}</p>
                            </div>
                            @endif

                            <div style="height:1px; background: rgba(201,168,76,0.12);"></div>

                            {{-- Revenue forecast --}}
                            <div>
                                <p class="text-[9px] uppercase tracking-widest font-bold mb-2" style="color: #4a6a52;">Forecast @ R{{ number_format($this->effectivePrice, 0) }}</p>
                                @foreach($this->revenueForecast as $scenario => $data)
                                @php $positive = $data['profit'] >= 0; @endphp
                                <div class="flex items-center justify-between mb-1.5">
                                    <div>
                                        <span class="text-[9px] uppercase font-bold {{ $scenario === 'expected' ? '' : '' }}" style="color: {{ $scenario === 'expected' ? '#c9a84c' : '#4a5e4e' }};">
                                            {{ ucfirst($scenario) }}
                                        </span>
                                        <span class="text-[9px] ml-1" style="color: #3a5040;">{{ $data['headcount'] }}p</span>
                                    </div>
                                    <span class="text-xs font-bold {{ $positive ? '' : 'text-red-400' }}" style="{{ $positive ? 'color: #6db98a;' : '' }}">
                                        {{ $positive ? '+' : '-' }}R{{ number_format(abs($data['profit']), 0) }}
                                    </span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                </div>

                {{-- ─── STEP 5: Review & Publish ───────────────── --}}
                @elseif($step === 5)
                @php $p = $plan; @endphp
                @if($p)
                <div class="max-w-xl space-y-4">

                    {{-- Title + type --}}
                    <div class="p-4 rounded-xl" style="background: #0d1e13;">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl flex-shrink-0"
                                style="background: {{ $p->cover_color }}"></div>
                            <div>
                                <p class="font-bold text-white text-lg leading-tight">{{ $p->title }}</p>
                                @if($p->tagline)<p class="text-sm italic mt-0.5" style="color: #c9a84c;">{{ $p->tagline }}</p>@endif
                                <p class="text-xs mt-1 capitalize" style="color: #5a7060;">{{ str_replace('_',' ',$p->type) }}  &bull;  {{ ucfirst($p->difficulty ?? 'moderate') }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Info grid --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div class="p-3.5 rounded-xl bg-stone-50 border border-stone-100">
                            <p class="text-[9px] font-bold uppercase tracking-widest text-stone-400 mb-1.5">📅 Date &amp; Time</p>
                            <p class="text-sm font-semibold text-stone-800">{{ $p->departs_at?->format('D, d M Y') ?? '—' }}</p>
                            @if($p->departs_at)<p class="text-xs text-stone-500 mt-0.5">{{ $p->departs_at->format('H:i') }}{{ $p->returns_at ? ' – '.$p->returns_at->format('H:i') : '' }}</p>@endif
                        </div>
                        <div class="p-3.5 rounded-xl bg-stone-50 border border-stone-100">
                            <p class="text-[9px] font-bold uppercase tracking-widest text-stone-400 mb-1.5">📍 Location</p>
                            <p class="text-sm font-semibold text-stone-800">{{ $p->location ?? '—' }}</p>
                            @if($p->trail_name)<p class="text-xs text-stone-500 mt-0.5">{{ $p->trail_name }}</p>@endif
                        </div>
                        <div class="p-3.5 rounded-xl bg-stone-50 border border-stone-100">
                            <p class="text-[9px] font-bold uppercase tracking-widest text-stone-400 mb-1.5">👥 Capacity</p>
                            <p class="text-sm font-semibold text-stone-800">{{ $p->min_capacity }}–{{ $p->max_capacity }} people</p>
                            @if($p->meeting_point)<p class="text-xs text-stone-500 mt-0.5">Meet: {{ $p->meeting_point }}</p>@endif
                        </div>
                        <div class="p-3.5 rounded-xl border" style="background: rgba(201,168,76,0.06); border-color: rgba(201,168,76,0.3);">
                            <p class="text-[9px] font-bold uppercase tracking-widest mb-1.5" style="color: #c9a84c;">💰 Pricing</p>
                            <p class="text-xl font-bold" style="color: #0d2117;">R{{ number_format($p->price ?? $p->suggestedPrice(), 2) }}</p>
                            @if($p->includes_transport)<p class="text-xs text-amber-700 mt-0.5">+ R{{ number_format($p->transport_fee,2) }} transport</p>@endif
                            @if(($p->nights ?? 0) > 0)<p class="text-xs text-indigo-600 mt-0.5">+ R{{ number_format($p->accommodation_cost_per_person,2) }} × {{ $p->nights }} night{{ $p->nights > 1 ? 's' : '' }} accommodation</p>@endif
                        </div>
                    </div>

                    {{-- Accommodation summary --}}
                    @if(($p->nights ?? 0) > 0)
                    <div class="p-3.5 rounded-xl border border-indigo-100" style="background: rgba(99,102,241,0.04);">
                        <p class="text-[9px] font-bold uppercase tracking-widest text-indigo-400 mb-2">🛏 Overnight — {{ $p->nights }} night{{ $p->nights > 1 ? 's' : '' }}</p>
                        @if($p->accommodation_name)<p class="text-sm font-semibold text-stone-800">{{ $p->accommodation_name }}</p>@endif
                        @if($p->what_is_included)<p class="text-xs text-stone-500 mt-1">{{ $p->what_is_included }}</p>@endif
                        @if($p->what_to_bring)<p class="text-xs text-stone-400 mt-1"><span class="font-semibold text-stone-500">Bring:</span> {{ $p->what_to_bring }}</p>@endif
                    </div>
                    @endif

                    {{-- Expenses summary --}}
                    @if(!empty($p->expenses))
                    <div class="p-3.5 rounded-xl bg-stone-50 border border-stone-100">
                        <p class="text-[9px] font-bold uppercase tracking-widest text-stone-400 mb-2">Expenses</p>
                        @foreach($p->expenses as $e)
                        <div class="flex justify-between text-xs py-0.5 text-stone-600">
                            <span>{{ $e['description'] }}</span>
                            <span class="font-medium">R{{ number_format($e['amount'], 2) }}</span>
                        </div>
                        @endforeach
                        <div class="flex justify-between font-bold text-xs border-t border-stone-200 pt-1.5 mt-1.5 text-stone-900">
                            <span>Total</span><span>R{{ number_format($p->totalExpenses(), 2) }}</span>
                        </div>
                    </div>
                    @endif

                    @error('publish') <p class="text-sm text-red-500 p-3 bg-red-50 rounded-xl">{{ $message }}</p> @enderror

                    {{-- Publish button --}}
                    <button
                        wire:click="publish"
                        wire:confirm="Publish this event? It will go live immediately and bookings will open."
                        wire:loading.attr="disabled"
                        class="w-full py-4 rounded-xl font-bold text-base tracking-wide transition-all
                               bg-green-700 hover:bg-green-800 active:scale-[0.99] text-white disabled:opacity-60 shadow-lg shadow-green-900/20"
                    >
                        <span wire:loading.remove>🚀 &nbsp; Publish Event</span>
                        <span wire:loading>Publishing&hellip;</span>
                    </button>
                    <p class="text-center text-xs text-stone-400">The event will be visible to members immediately.</p>

                </div>
                @else
                <div class="text-center py-12 text-stone-400">
                    <p class="text-sm">Complete the previous steps to unlock the review.</p>
                </div>
                @endif

                @endif
                {{-- /step content --}}

            </div>{{-- /step content area --}}

            {{-- ── NAV BUTTONS ── --}}
            <div class="px-8 py-4 border-t border-stone-100 flex items-center justify-between bg-stone-50/50">
                <button
                    wire:click="previousStep"
                    class="{{ $step === 1 ? 'invisible' : '' }} flex items-center gap-2 text-sm font-semibold text-stone-500 hover:text-stone-800 px-4 py-2 rounded-xl hover:bg-stone-200/60 transition-all"
                >
                    &#8592; Back
                </button>

                @if($step < 5)
                <button
                    wire:click="nextStep"
                    wire:loading.attr="disabled"
                    class="flex items-center gap-2 text-sm font-semibold text-white bg-green-700 hover:bg-green-800 px-6 py-2.5 rounded-xl transition-all shadow-sm disabled:opacity-60"
                >
                    <span wire:loading.remove>Save &amp; Continue &#8594;</span>
                    <span wire:loading>Saving&hellip;</span>
                </button>
                @endif
            </div>

        </div>{{-- /right panel --}}

    </div>{{-- /two-panel --}}
</div>
