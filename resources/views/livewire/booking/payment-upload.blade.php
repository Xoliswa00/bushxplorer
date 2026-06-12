<div class="max-w-2xl mx-auto p-6 bg-white rounded-2xl shadow-md">

    <h1 class="text-2xl font-bold text-stone-800 mb-1">Upload Proof of Payment</h1>
    <p class="text-sm text-stone-500 mb-6">
        Booking <span class="font-mono font-semibold text-green-700">{{ $this->booking->booking_ref }}</span>
        &bull; R{{ number_format($this->booking->amount_due, 2) }} due
    </p>

    {{-- Bank details --}}
    <div class="mb-6 p-4 bg-stone-50 rounded-xl border border-stone-200">
        <h3 class="text-sm font-semibold text-stone-700 mb-2">Bank Transfer Details</h3>
        <dl class="grid grid-cols-2 gap-x-4 gap-y-1 text-sm">
            <dt class="text-stone-500">Bank</dt>
            <dd class="text-stone-800 font-medium">{{ $this->bankDetails['bank'] }}</dd>
            <dt class="text-stone-500">Account</dt>
            <dd class="text-stone-800 font-medium font-mono">{{ $this->bankDetails['account'] }}</dd>
            <dt class="text-stone-500">Branch code</dt>
            <dd class="text-stone-800 font-medium font-mono">{{ $this->bankDetails['branch'] }}</dd>
            <dt class="text-stone-500">Reference</dt>
            <dd class="text-stone-800 font-semibold font-mono text-green-700">{{ $this->bankDetails['reference'] }}</dd>
        </dl>
    </div>

    <form wire:submit="upload" class="space-y-5">

        {{-- Payment method --}}
        <div>
            <label class="block text-sm font-medium text-stone-700 mb-1">Payment method</label>
            <select
                wire:model="paymentMethod"
                class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white"
            >
                <option value="eft">EFT / Bank Transfer</option>
                <option value="cash">Cash</option>
                <option value="card">Card</option>
                <option value="other">Other</option>
            </select>
        </div>

        {{-- Bank reference --}}
        <div>
            <label class="block text-sm font-medium text-stone-700 mb-1">
                Your bank reference <span class="text-stone-400">(optional)</span>
            </label>
            <input
                type="text"
                wire:model="reference"
                placeholder="e.g. BXB-2025-00042"
                class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent font-mono"
            />
        </div>

        {{-- File upload --}}
        <div>
            <label class="block text-sm font-medium text-stone-700 mb-1">
                Proof of payment <span class="text-red-500">*</span>
            </label>

            <div
                class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-stone-300 border-dashed rounded-xl hover:border-green-400 transition-colors"
                x-data="{ dragging: false }"
                @dragover.prevent="dragging = true"
                @dragleave.prevent="dragging = false"
                @drop.prevent="dragging = false"
                :class="dragging ? 'border-green-500 bg-green-50' : ''"
            >
                <div class="space-y-1 text-center">
                    <svg class="mx-auto h-10 w-10 text-stone-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <div class="flex text-sm text-stone-600 justify-center">
                        <label class="relative cursor-pointer rounded-md font-medium text-green-600 hover:text-green-500">
                            <span>Upload a file</span>
                            <input type="file" wire:model="proofFile" class="sr-only" accept=".jpg,.jpeg,.png,.pdf" />
                        </label>
                        <p class="pl-1">or drag and drop</p>
                    </div>
                    <p class="text-xs text-stone-500">PNG, JPG, PDF up to 5MB</p>
                </div>
            </div>

            {{-- File preview --}}
            @if($proofFile)
            <div class="mt-2 flex items-center gap-2 text-sm text-stone-600">
                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ $proofFile->getClientOriginalName() }}</span>
                <span class="text-stone-400">({{ number_format($proofFile->getSize() / 1024, 1) }} KB)</span>
            </div>
            @endif

            <div wire:loading wire:target="proofFile" class="mt-2 text-sm text-stone-500">
                Uploading&hellip;
            </div>

            @error('proofFile') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <button
            type="submit"
            wire:loading.attr="disabled"
            wire:target="upload"
            class="w-full bg-green-600 hover:bg-green-700 disabled:opacity-50 text-white font-semibold py-3 px-6 rounded-xl transition-colors"
        >
            <span wire:loading.remove wire:target="upload">Submit Payment Proof &rarr;</span>
            <span wire:loading wire:target="upload">Submitting&hellip;</span>
        </button>

    </form>
</div>
