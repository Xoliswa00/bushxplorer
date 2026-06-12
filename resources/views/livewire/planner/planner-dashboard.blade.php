<div class="max-w-4xl mx-auto">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-stone-800">Event Planner</h1>
            <p class="text-sm text-stone-500 mt-1">Build your event idea into a live listing</p>
        </div>
        <a href="{{ route('planner.create') }}"
            class="bg-green-600 hover:bg-green-700 text-white font-semibold px-5 py-2.5 rounded-xl transition-colors">
            + New Event Plan
        </a>
    </div>

    @if($this->plans->isEmpty())
    <div class="text-center py-20 text-stone-400">
        <svg class="mx-auto w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        <p class="text-lg font-medium text-stone-500">No plans yet</p>
        <p class="text-sm mt-1">Hit "New Event Plan" to bring your first idea to life.</p>
    </div>
    @else
    <div class="space-y-3">
        @foreach($this->plans as $plan)
        <div wire:key="plan-{{ $plan->id }}" class="bg-white rounded-2xl shadow-sm border border-stone-100 p-5 flex items-center gap-4">

            {{-- Color swatch --}}
            <div class="w-12 h-12 rounded-xl flex-shrink-0 flex items-center justify-center text-white font-bold text-lg"
                style="background-color: {{ $plan->cover_color }}">
                {{ strtoupper(substr($plan->title, 0, 1)) }}
            </div>

            {{-- Info --}}
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-stone-800 truncate">{{ $plan->title }}</p>
                <p class="text-sm text-stone-500 mt-0.5">
                    <span class="capitalize">{{ str_replace('_', ' ', $plan->type) }}</span>
                    @if($plan->departs_at)
                        &bull; {{ $plan->departs_at->format('d M Y') }}
                    @endif
                    @if($plan->location)
                        &bull; {{ $plan->location }}
                    @endif
                </p>
            </div>

            {{-- Status badge --}}
            @php
                $statusColors = [
                    'ideation'   => 'bg-stone-100 text-stone-600',
                    'logistics'  => 'bg-blue-100 text-blue-700',
                    'capacity'   => 'bg-purple-100 text-purple-700',
                    'financials' => 'bg-amber-100 text-amber-700',
                    'review'     => 'bg-orange-100 text-orange-700',
                    'published'  => 'bg-green-100 text-green-700',
                ];
                $sc = $statusColors[$plan->status] ?? 'bg-stone-100 text-stone-600';
            @endphp
            <span class="flex-shrink-0 text-xs font-semibold px-3 py-1 rounded-full {{ $sc }} capitalize">
                {{ str_replace('_', ' ', $plan->status) }}
            </span>

            {{-- Progress indicator --}}
            <div class="flex-shrink-0 text-xs text-stone-400">
                Step {{ min($plan->current_step, 5) }}/5
            </div>

            {{-- Actions --}}
            <div class="flex-shrink-0 flex gap-2">
                @if($plan->status === 'published')
                    <a href="{{ route('planner.poster', $plan->id) }}"
                        class="text-xs bg-stone-100 hover:bg-stone-200 text-stone-700 font-semibold px-3 py-1.5 rounded-lg transition-colors">
                        Poster
                    </a>
                    <a href="{{ route('hikes.show', $plan->publishedHike) }}"
                        class="text-xs bg-green-100 hover:bg-green-200 text-green-800 font-semibold px-3 py-1.5 rounded-lg transition-colors">
                        View Event
                    </a>
                @else
                    <a href="{{ route('planner.edit', $plan->id) }}"
                        class="text-xs bg-green-600 hover:bg-green-700 text-white font-semibold px-3 py-1.5 rounded-lg transition-colors">
                        Continue
                    </a>
                @endif
                <button
                    wire:click="deletePlan({{ $plan->id }})"
                    wire:confirm="Delete this plan? This cannot be undone."
                    class="text-xs text-red-400 hover:text-red-600 px-2 py-1.5 rounded-lg transition-colors">
                    ✕
                </button>
            </div>
        </div>
        @endforeach
    </div>
    @endif

</div>
