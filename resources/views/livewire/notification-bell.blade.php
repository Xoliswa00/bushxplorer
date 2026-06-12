<div class="relative" x-data @click.outside="$wire.open = false">

    {{-- Bell button --}}
    <button
        wire:click="toggleOpen"
        class="relative p-2 rounded-full hover:bg-green-700 transition-colors focus:outline-none"
        aria-label="Notifications"
    >
        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>

        @if($this->unreadCount > 0)
        <span class="absolute top-0.5 right-0.5 min-w-[18px] h-[18px] bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center px-1 leading-none">
            {{ $this->unreadCount > 99 ? '99+' : $this->unreadCount }}
        </span>
        @endif
    </button>

    {{-- Dropdown panel --}}
    @if($open)
    <div class="absolute right-0 mt-2 w-96 max-h-[520px] bg-white rounded-2xl shadow-xl border border-stone-200 flex flex-col z-50 overflow-hidden">

        {{-- Header --}}
        <div class="flex items-center justify-between px-4 py-3 border-b border-stone-100">
            <h3 class="font-semibold text-stone-800">Notifications</h3>
            @if($this->unreadCount > 0)
            <button
                wire:click="markAllRead"
                class="text-xs text-green-700 hover:text-green-900 font-medium"
            >
                Mark all read
            </button>
            @endif
        </div>

        {{-- List --}}
        <div class="overflow-y-auto flex-1 divide-y divide-stone-100">
            @forelse($this->notifications as $n)
            <div
                wire:key="notif-{{ $n->id }}"
                class="flex gap-3 px-4 py-3 hover:bg-stone-50 transition-colors {{ $n->is_read ? '' : 'bg-green-50' }}"
            >
                {{-- Type icon --}}
                <div class="flex-shrink-0 mt-0.5">
                    @switch($n->type)
                        @case('booking_confirmed')
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-100 text-green-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </span>
                            @break
                        @case('payment_pending')
                        @case('payment_uploaded')
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-2.21 0-4 1.343-4 3s1.79 3 4 3 4 1.343 4 3-1.79 3-4 3m0-18v2m0 14v2"/></svg>
                            </span>
                            @break
                        @case('payment_rejected')
                        @case('booking_cancelled')
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-100 text-red-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </span>
                            @break
                        @default
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-stone-100 text-stone-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01"/></svg>
                            </span>
                    @endswitch
                </div>

                {{-- Content --}}
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-stone-800 {{ $n->is_read ? 'font-medium' : '' }}">
                        {{ $n->title }}
                    </p>
                    <p class="text-xs text-stone-600 mt-0.5 leading-snug">{{ $n->body }}</p>
                    <p class="text-[11px] text-stone-400 mt-1">{{ $n->created_at->diffForHumans() }}</p>
                </div>

                {{-- Mark read dot --}}
                @if(! $n->is_read)
                <button
                    wire:click="markRead({{ $n->id }})"
                    class="flex-shrink-0 self-start mt-1 w-2.5 h-2.5 rounded-full bg-green-500 hover:bg-green-700 transition-colors"
                    title="Mark as read"
                ></button>
                @endif
            </div>
            @empty
            <div class="px-4 py-10 text-center text-sm text-stone-400">
                <svg class="mx-auto w-8 h-8 mb-2 text-stone-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                No notifications yet
            </div>
            @endforelse
        </div>

    </div>
    @endif

</div>
