<div class="bg-white rounded-2xl shadow-md p-5">

    @if($uploaded)
    <div class="text-center py-6">
        <svg class="mx-auto w-12 h-12 text-green-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="font-semibold text-stone-800">Media uploaded!</p>
        <p class="text-sm text-stone-500 mt-1">It'll appear in the gallery once reviewed.</p>
        <button wire:click="$set('uploaded', false)" class="mt-4 text-sm text-green-700 hover:underline">
            Upload another
        </button>
    </div>
    @elseif(!$this->canUpload)
    <p class="text-sm text-stone-500 text-center py-4">
        Only members who attended this hike can upload photos and videos.
    </p>
    @else
    <h3 class="font-semibold text-stone-800 mb-4">Share Your Photos &amp; Videos</h3>

    <form wire:submit="upload" class="space-y-4">

        {{-- Drop zone --}}
        <div
            class="flex justify-center px-6 pt-5 pb-6 border-2 border-stone-300 border-dashed rounded-xl hover:border-green-400 transition-colors"
            x-data="{ dragging: false }"
            @dragover.prevent="dragging = true"
            @dragleave.prevent="dragging = false"
            @drop.prevent="dragging = false"
            :class="dragging ? 'border-green-500 bg-green-50' : ''"
        >
            <div class="space-y-1 text-center">
                <svg class="mx-auto h-10 w-10 text-stone-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <div class="flex text-sm text-stone-600 justify-center">
                    <label class="relative cursor-pointer rounded-md font-medium text-green-600 hover:text-green-500">
                        <span>Choose file</span>
                        <input type="file" wire:model="mediaFile" class="sr-only" accept=".jpg,.jpeg,.png,.gif,.mp4,.mov,.avi"/>
                    </label>
                    <p class="pl-1">or drag and drop</p>
                </div>
                <p class="text-xs text-stone-500">Photos: JPG, PNG, GIF &bull; Videos: MP4, MOV, AVI &bull; Max 100 MB</p>
            </div>
        </div>

        @if($mediaFile)
        <div class="flex items-center gap-2 text-sm text-stone-600">
            <svg class="w-4 h-4 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span class="truncate">{{ $mediaFile->getClientOriginalName() }}</span>
            <span class="text-stone-400 flex-shrink-0">({{ number_format($mediaFile->getSize() / 1024 / 1024, 1) }} MB)</span>
        </div>
        @endif

        <div wire:loading wire:target="mediaFile" class="text-sm text-stone-500">Processing&hellip;</div>

        @error('mediaFile') <p class="text-sm text-red-600">{{ $message }}</p> @enderror

        {{-- Caption --}}
        <div>
            <input
                type="text"
                wire:model="caption"
                placeholder="Add a caption (optional)"
                maxlength="300"
                class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm"
            />
        </div>

        <button
            type="submit"
            wire:loading.attr="disabled"
            wire:target="upload"
            class="w-full bg-green-600 hover:bg-green-700 disabled:opacity-50 text-white font-semibold py-2.5 px-4 rounded-xl transition-colors text-sm"
        >
            <span wire:loading.remove wire:target="upload">Upload &amp; Share</span>
            <span wire:loading wire:target="upload">Uploading&hellip;</span>
        </button>

    </form>
    @endif
</div>
