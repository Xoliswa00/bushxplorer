<div class="space-y-4">

    @if(session('success'))
    <div class="p-3 bg-green-50 border border-green-200 text-green-800 rounded-xl text-sm">
        {{ session('success') }}
    </div>
    @endif

    {{-- Tabs --}}
    <div class="flex gap-1 bg-stone-100 p-1 rounded-xl w-fit">
        @foreach(['pending' => 'Pending', 'approved' => 'Approved', 'published' => 'Published'] as $key => $label)
        <button
            wire:click="$set('tab', '{{ $key }}')"
            class="px-4 py-1.5 rounded-lg text-sm font-medium transition-colors
                {{ $tab === $key ? 'bg-white shadow text-stone-800' : 'text-stone-500 hover:text-stone-700' }}"
        >
            {{ $label }}
        </button>
        @endforeach
    </div>

    {{-- Grid --}}
    @if($this->items->isEmpty())
    <p class="text-sm text-stone-400 py-8 text-center">Nothing here.</p>
    @else
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
        @foreach($this->items as $item)
        <div wire:key="mgr-{{ $item->id }}" class="bg-white rounded-xl overflow-hidden border border-stone-200 shadow-sm">

            {{-- Thumbnail --}}
            <div class="aspect-video bg-stone-100 flex items-center justify-center relative">
                @if($item->type === 'video')
                <svg class="w-8 h-8 text-stone-400" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                @else
                <img
                    src="{{ Storage::url($item->thumbnail_path ?? $item->file_path) }}"
                    class="w-full h-full object-cover"
                    loading="lazy"
                />
                @endif
                <span class="absolute top-1.5 left-1.5 px-2 py-0.5 text-[10px] font-bold rounded-full uppercase
                    {{ $item->type === 'video' ? 'bg-purple-100 text-purple-800' : 'bg-stone-100 text-stone-700' }}">
                    {{ $item->type }}
                </span>
            </div>

            {{-- Info --}}
            <div class="p-3">
                <p class="text-xs text-stone-500 truncate">{{ $item->hike?->title ?? '—' }}</p>
                @if($item->caption)
                <p class="text-xs text-stone-700 mt-1 line-clamp-2">{{ $item->caption }}</p>
                @endif
                <p class="text-[11px] text-stone-400 mt-1">{{ $item->created_at->diffForHumans() }}</p>

                {{-- Social badges --}}
                @if($item->social_platforms)
                <div class="flex gap-1 mt-2 flex-wrap">
                    @foreach($item->social_platforms as $platform => $data)
                    <span class="inline-flex items-center gap-1 text-[10px] font-semibold px-2 py-0.5 rounded-full
                        {{ $platform === 'instagram' ? 'bg-pink-100 text-pink-800' : 'bg-blue-100 text-blue-800' }}">
                        {{ ucfirst($platform) }}
                    </span>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Actions --}}
            <div class="px-3 pb-3 flex gap-2">
                @if($tab === 'pending')
                <button
                    wire:click="approve({{ $item->id }})"
                    wire:confirm="Approve this item?"
                    class="flex-1 text-xs bg-green-600 hover:bg-green-700 text-white font-semibold py-1.5 rounded-lg transition-colors"
                >
                    Approve
                </button>
                <button
                    wire:click="reject({{ $item->id }})"
                    wire:confirm="Reject and remove this item?"
                    class="flex-1 text-xs bg-red-100 hover:bg-red-200 text-red-700 font-semibold py-1.5 rounded-lg transition-colors"
                >
                    Reject
                </button>
                @elseif($tab === 'approved')
                <button
                    wire:click="openPublishModal({{ $item->id }})"
                    class="flex-1 text-xs bg-blue-600 hover:bg-blue-700 text-white font-semibold py-1.5 rounded-lg transition-colors"
                >
                    Publish to Social
                </button>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <div>{{ $this->items->links() }}</div>
    @endif

    {{-- Publish modal --}}
    @if($publishId)
    <div class="fixed inset-0 z-50 bg-black/60 flex items-center justify-center p-4" wire:click.self="closePublishModal">
        <div class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-sm">
            <h3 class="font-bold text-stone-800 text-lg mb-4">Publish to Social</h3>

            <p class="text-sm text-stone-500 mb-4">Choose where to publish. Posts are queued and sent within ~1 minute.</p>

            <div class="space-y-3 mb-6">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" wire:model="platforms" value="instagram"
                        class="w-4 h-4 rounded text-pink-600 focus:ring-pink-500"/>
                    <span class="text-sm font-medium text-stone-800">Instagram</span>
                </label>
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" wire:model="platforms" value="facebook"
                        class="w-4 h-4 rounded text-blue-600 focus:ring-blue-500"/>
                    <span class="text-sm font-medium text-stone-800">Facebook Page</span>
                </label>
            </div>

            @error('platforms') <p class="text-sm text-red-600 mb-3">{{ $message }}</p> @enderror

            <div class="flex gap-3">
                <button
                    wire:click="closePublishModal"
                    class="flex-1 py-2 rounded-xl border border-stone-200 text-sm text-stone-600 hover:bg-stone-50"
                >
                    Cancel
                </button>
                <button
                    wire:click="publish"
                    wire:loading.attr="disabled"
                    class="flex-1 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white text-sm font-semibold transition-colors"
                >
                    <span wire:loading.remove wire:target="publish">Publish</span>
                    <span wire:loading wire:target="publish">Queuing&hellip;</span>
                </button>
            </div>
        </div>
    </div>
    @endif

</div>
