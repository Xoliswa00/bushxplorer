<div class="min-h-[80vh] flex items-center justify-center px-4 py-10">
    <div class="w-full max-w-md">

        {{-- Header --}}
        <div class="text-center mb-8">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-4"
                style="background: rgba(201,168,76,0.1); border: 1px solid rgba(201,168,76,0.3);">🌿</div>
            <h1 class="text-3xl font-bold" style="font-family:'Cormorant Garamond',serif; color:#0d1e13;">Create your account</h1>
            <p class="text-sm mt-1.5" style="color:#7a9180;">Join BushXplorer and start your adventure</p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-stone-200 p-8">
            <form wire:submit="register" class="space-y-5">

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-stone-400 mb-1.5">
                            First name <span class="text-red-400">*</span>
                        </label>
                        <input type="text" wire:model="firstName" autofocus autocomplete="given-name"
                            class="w-full px-4 py-3 border border-stone-200 rounded-xl text-stone-900 text-sm bg-white
                                   focus:outline-none focus:ring-2 focus:ring-green-600/20 focus:border-green-700 transition-colors"
                            placeholder="Thabo"/>
                        @error('firstName') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-stone-400 mb-1.5">
                            Last name <span class="text-red-400">*</span>
                        </label>
                        <input type="text" wire:model="lastName" autocomplete="family-name"
                            class="w-full px-4 py-3 border border-stone-200 rounded-xl text-stone-900 text-sm bg-white
                                   focus:outline-none focus:ring-2 focus:ring-green-600/20 focus:border-green-700 transition-colors"
                            placeholder="Sithole"/>
                        @error('lastName') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-stone-400 mb-1.5">
                        Email <span class="text-red-400">*</span>
                    </label>
                    <input type="email" wire:model="email" autocomplete="email"
                        class="w-full px-4 py-3 border border-stone-200 rounded-xl text-stone-900 text-sm bg-white
                               focus:outline-none focus:ring-2 focus:ring-green-600/20 focus:border-green-700 transition-colors"
                        placeholder="thabo@example.com"/>
                    @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-stone-400 mb-1.5">
                        Phone <span class="text-stone-300 font-normal normal-case tracking-normal">(optional)</span>
                    </label>
                    <input type="tel" wire:model="phone" autocomplete="tel"
                        class="w-full px-4 py-3 border border-stone-200 rounded-xl text-stone-900 text-sm bg-white
                               focus:outline-none focus:ring-2 focus:ring-green-600/20 focus:border-green-700 transition-colors"
                        placeholder="071 234 5678"/>
                    @error('phone') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-stone-400 mb-1.5">
                        Password <span class="text-red-400">*</span>
                    </label>
                    <input type="password" wire:model="password" autocomplete="new-password"
                        class="w-full px-4 py-3 border border-stone-200 rounded-xl text-stone-900 text-sm bg-white
                               focus:outline-none focus:ring-2 focus:ring-green-600/20 focus:border-green-700 transition-colors"/>
                    @error('password') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-stone-400 mb-1.5">
                        Confirm password <span class="text-red-400">*</span>
                    </label>
                    <input type="password" wire:model="passwordConfirmation" autocomplete="new-password"
                        class="w-full px-4 py-3 border border-stone-200 rounded-xl text-stone-900 text-sm bg-white
                               focus:outline-none focus:ring-2 focus:ring-green-600/20 focus:border-green-700 transition-colors"/>
                    @error('passwordConfirmation') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <button type="submit" wire:loading.attr="disabled"
                    class="w-full py-3 rounded-xl font-semibold text-sm text-white transition-all
                           bg-green-700 hover:bg-green-800 active:scale-[0.99] disabled:opacity-60 shadow-sm">
                    <span wire:loading.remove>Create account &rarr;</span>
                    <span wire:loading>Creating account&hellip;</span>
                </button>

                <p class="text-center text-xs text-stone-400">
                    You'll start as a <span class="font-semibold text-stone-600">Trail Starter</span> — earn points on every hike to level up and unlock discounts.
                </p>

            </form>

            <p class="text-center text-sm text-stone-500 mt-6">
                Already have an account?
                <a href="{{ route('login') }}" class="font-semibold text-green-700 hover:text-green-800 transition-colors">
                    Sign in
                </a>
            </p>
        </div>
    </div>
</div>
