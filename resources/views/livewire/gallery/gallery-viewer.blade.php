<div>

    {{-- Filters --}}
    <div class="flex items-center gap-2 mb-4">
        @foreach(['all' => 'All', 'photos' => 'Photos', 'videos' => 'Videos'] as $val => $label)
        <button
            wire:click="$set('filter', '{{ $val }}')"
            class="px-4 py-1.5 rounded-full text-sm font-medium transition-colors
                {{ $filter === $val ? 'bg-green-600 text-white' : 'bg-white text-stone-600 hover:bg-stone-100 border border-stone-200' }}"
        >
            {{ $label }}
        </button>
        @endforeach
    </div>

    {{-- Grid --}}
    @if($this->items->isEmpty())
    <div class="text-center py-16 text-stone-400">
        <svg class="mx-auto w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
        No media yet. Be the first to share!
    </div>
    @else
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
        @foreach($this->items as $item)
        <div
            wire:key="gallery-{{ $item->id }}"
            wire:click="openLightbox({{ $item->id }})"
            class="relative aspect-square rounded-xl overflow-hidden cursor-pointer group bg-stone-100"
        >
            @if($item->type === 'video')
            <div class="w-full h-full flex items-center justify-center bg-stone-800">
                <svg class="w-10 h-10 text-white opacity-80" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M8 5v14l11-7z"/>
                </svg>
            </div>
            @else
            <img
                src="{{ Storage::url($item->thumbnail_path ?? $item->file_path) }}"
                alt="{{ $item->caption }}"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                loading="lazy"
            />
            @endif

            {{-- Featured badge --}}
            @if($item->is_featured)
            <span class="absolute top-2 left-2 bg-yellow-400 text-yellow-900 text-[10px] font-bold px-2 py-0.5 rounded-full">Featured</span>
            @endif

            {{-- Tags count --}}
            @if($item->approvedTags->count() > 0)
            <span class="absolute bottom-2 right-2 bg-black/60 text-white text-xs px-2 py-0.5 rounded-full">
                {{ $item->approvedTags->count() }} tagged
            </span>
            @endif

            {{-- Social published indicator --}}
            @if($item->social_published_at)
            <span class="absolute top-2 right-2 w-6 h-6 bg-white/90 rounded-full flex items-center justify-center">
                <svg class="w-3.5 h-3.5 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                </svg>
            </span>
            @endif
        </div>
        @endforeach
    </div>

    <div class="mt-4">{{ $this->items->links() }}</div>
    @endif

    {{-- Lightbox --}}
    @if($this->lightboxItem)
    <div
        class="fixed inset-0 z-50 bg-black/80 flex items-center justify-center p-4"
        wire:click.self="closeLightbox"
    >
        <div class="bg-white rounded-2xl overflow-hidden max-w-2xl w-full max-h-[90vh] flex flex-col shadow-2xl">

            {{-- Media --}}
            <div class="bg-stone-900 flex items-center justify-center min-h-[300px] relative">
                @if($this->lightboxItem->type === 'video')
                <video
                    src="{{ Storage::url($this->lightboxItem->file_path) }}"
                    controls
                    class="max-h-[60vh] w-full"
                ></video>
                @else
                <img
                    src="{{ Storage::url($this->lightboxItem->file_path) }}"
                    alt="{{ $this->lightboxItem->caption }}"
                    class="max-h-[60vh] w-auto object-contain"
                />
                @endif

                <button
                    wire:click="closeLightbox"
                    class="absolute top-3 right-3 w-8 h-8 bg-black/50 hover:bg-black/70 rounded-full flex items-center justify-center text-white transition-colors"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Info panel --}}
            <div class="p-4 overflow-y-auto">
                @if($this->lightboxItem->caption)
                <p class="text-stone-800 mb-3">{{ $this->lightboxItem->caption }}</p>
                @endif

                {{-- Tagged members --}}
                @if($this->lightboxItem->approvedTags->isNotEmpty())
                <div class="flex flex-wrap gap-2 mb-3">
                    @foreach($this->lightboxItem->approvedTags as $tag)
                    <span class="inline-flex items-center gap-1 bg-green-100 text-green-800 text-xs font-medium px-2.5 py-1 rounded-full">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        {{ $tag->member->full_name }}
                    </span>
                    @endforeach
                </div>
                @endif

                {{-- Self-tag action --}}
                @auth
                @if($this->attended && $this->member)
                    @php
                        $alreadyTagged = $this->lightboxItem->approvedTags
                            ->contains('member_id', $this->member->id);
                    @endphp

                    @if($alreadyTagged)
                    <button
                        wire:click="untagMyself({{ $this->lightboxItem->id }})"
                        class="text-xs text-stone-500 hover:text-red-600 transition-colors"
                    >
                        Remove my tag
                    </button>
                    @else
                    <button
                        wire:click="tagMyself({{ $this->lightboxItem->id }})"
                        class="text-xs text-green-700 hover:text-green-900 font-medium transition-colors"
                    >
                        + Tag myself in this photo
                    </button>
                    @endif
                @endif
                @endauth
            </div>
        </div>
    </div>
    @endif

</div>
