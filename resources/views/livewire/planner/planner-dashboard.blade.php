<div class="max-w-5xl mx-auto">

    {{-- Page header --}}
    <div class="flex items-end justify-between mb-7">
        <div>
            <p class="text-[10px] font-bold uppercase tracking-[0.2em] mb-1" style="color: #c9a84c;">BushXplorer</p>
            <h1 class="text-3xl font-bold text-stone-900 tracking-tight" style="font-family: 'Cormorant Garamond', serif;">Event Planner</h1>
            <p class="text-sm text-stone-500 mt-1">Turn your ideas into live adventure events.</p>
        </div>
        <a href="{{ route('planner.create') }}"
            class="flex items-center gap-2 font-semibold px-5 py-2.5 rounded-xl transition-all shadow-sm text-sm"
            style="background: #0f2117; color: #c9a84c; border: 1px solid rgba(201,168,76,0.3);">
            <span class="text-base leading-none">+</span> New Plan
        </a>
    </div>

    @if($this->plans->isEmpty())
    {{-- Empty state --}}
    <div class="rounded-2xl p-16 text-center" style="background: #0f2117; border: 1px solid rgba(201,168,76,0.15);">
        <div class="w-16 h-16 rounded-2xl mx-auto mb-4 flex items-center justify-center text-3xl"
            style="background: rgba(201,168,76,0.1); border: 1px solid rgba(201,168,76,0.2);">
            🗺
        </div>
        <h3 class="text-xl font-bold mb-2" style="font-family: 'Cormorant Garamond', serif; color: #e8d08a;">No plans yet</h3>
        <p class="text-sm mb-6" style="color: #4a6a52;">Every great adventure starts with an idea. Start planning yours.</p>
        <a href="{{ route('planner.create') }}"
            class="inline-flex items-center gap-2 font-semibold px-6 py-2.5 rounded-xl text-sm transition-all"
            style="background: #c9a84c; color: #0a0a0a;">
            Create your first plan
        </a>
    </div>

    @else
    {{-- Stats bar --}}
    @php
        $total      = $this->plans->count();
        $published  = $this->plans->where('status', 'published')->count();
        $inProgress = $total - $published;
    @endphp
    <div class="grid grid-cols-3 gap-4 mb-6">
        @foreach([['Total Plans', $total, '📋'], ['Published', $published, '🚀'], ['In Progress', $inProgress, '✏️']] as [$label, $val, $icon])
        <div class="p-4 rounded-xl bg-white border border-stone-100 shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-stone-50 flex items-center justify-center text-xl flex-shrink-0">{{ $icon }}</div>
            <div>
                <p class="text-2xl font-bold text-stone-900">{{ $val }}</p>
                <p class="text-xs text-stone-400 font-medium uppercase tracking-wide">{{ $label }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Plan cards grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($this->plans as $plan)
        @php
            $statusConfig = [
                'ideation'   => ['bg-stone-100',  'text-stone-600',  'Concept'],
                'logistics'  => ['bg-blue-50',    'text-blue-700',   'Logistics'],
                'capacity'   => ['bg-purple-50',  'text-purple-700', 'Capacity'],
                'financials' => ['bg-amber-50',   'text-amber-700',  'Financials'],
                'review'     => ['bg-orange-50',  'text-orange-700', 'Review'],
                'published'  => ['bg-green-50',   'text-green-700',  'Live ✓'],
            ];
            [$sbg, $stc, $slabel] = $statusConfig[$plan->status] ?? ['bg-stone-100','text-stone-600','Draft'];
            $stepNum = min($plan->current_step, 5);
        @endphp

        <div wire:key="plan-{{ $plan->id }}" class="bg-white rounded-2xl border border-stone-100 shadow-sm overflow-hidden group hover:shadow-md transition-shadow">

            {{-- Color accent bar + info --}}
            <div class="h-1.5" style="background: linear-gradient(90deg, {{ $plan->cover_color }}, transparent);"></div>

            <div class="p-5">
                {{-- Top row --}}
                <div class="flex items-start justify-between gap-3 mb-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 rounded-xl flex-shrink-0 flex items-center justify-center text-white font-bold text-base shadow-sm"
                            style="background: {{ $plan->cover_color }};">
                            {{ strtoupper(substr($plan->title, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <h3 class="font-bold text-stone-900 text-base truncate leading-snug">{{ $plan->title }}</h3>
                            <p class="text-xs text-stone-400 mt-0.5 capitalize">
                                {{ str_replace('_', ' ', $plan->type) }}
                                @if($plan->difficulty) &bull; {{ ucfirst($plan->difficulty) }} @endif
                            </p>
                        </div>
                    </div>
                    <span class="flex-shrink-0 text-[10px] font-bold uppercase tracking-wide px-2.5 py-1 rounded-full {{ $sbg }} {{ $stc }}">
                        {{ $slabel }}
                    </span>
                </div>

                {{-- Meta chips --}}
                <div class="flex flex-wrap gap-1.5 mb-4">
                    @if($plan->departs_at)
                    <span class="inline-flex items-center gap-1 text-[10px] font-medium px-2.5 py-1 bg-stone-50 rounded-full text-stone-600 border border-stone-100">
                        📅 {{ $plan->departs_at->format('d M Y') }}
                    </span>
                    @endif
                    @if($plan->location)
                    <span class="inline-flex items-center gap-1 text-[10px] font-medium px-2.5 py-1 bg-stone-50 rounded-full text-stone-600 border border-stone-100">
                        📍 {{ $plan->location }}
                    </span>
                    @endif
                    @if($plan->price)
                    <span class="inline-flex items-center gap-1 text-[10px] font-semibold px-2.5 py-1 bg-green-50 rounded-full text-green-700 border border-green-100">
                        💰 R{{ number_format($plan->price, 0) }}
                    </span>
                    @endif
                </div>

                {{-- Step progress dots --}}
                <div class="flex items-center gap-1.5 mb-4">
                    @for($i = 1; $i <= 5; $i++)
                    <div class="h-1.5 flex-1 rounded-full transition-all
                                {{ $i <= $stepNum ? '' : 'bg-stone-100' }}"
                        style="{{ $i <= $stepNum ? 'background: '.$plan->cover_color.';' : '' }}">
                    </div>
                    @endfor
                    <span class="text-[10px] text-stone-400 font-medium ml-1 flex-shrink-0">{{ $stepNum }}/5</span>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-2">
                    @if($plan->status === 'published')
                        <a href="{{ route('planner.poster', $plan->id) }}"
                            class="flex-1 text-center text-xs font-semibold py-2 rounded-xl border border-stone-200 text-stone-600 hover:bg-stone-50 transition-colors">
                            View Poster
                        </a>
                        @if($plan->publishedHike)
                        <a href="{{ route('hikes.show', $plan->publishedHike) }}"
                            class="flex-1 text-center text-xs font-semibold py-2 rounded-xl text-white transition-colors"
                            style="background: #0f2117;">
                            View Event
                        </a>
                        @endif
                    @else
                        <a href="{{ route('planner.edit', $plan->id) }}"
                            class="flex-1 text-center text-xs font-bold py-2 rounded-xl text-white transition-colors shadow-sm"
                            style="background: #c9a84c; color: #0a0a0a;">
                            Continue &rarr;
                        </a>
                    @endif
                    <button
                        wire:click="deletePlan({{ $plan->id }})"
                        wire:confirm="Delete '{{ $plan->title }}'? This cannot be undone."
                        class="p-2 rounded-xl text-stone-300 hover:text-red-400 hover:bg-red-50 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </div>

        </div>
        @endforeach
    </div>
    @endif

</div>
