<div class="min-h-[80vh] flex items-center justify-center px-4">
    <div class="w-full max-w-md">

        {{-- Header --}}
        <div class="text-center mb-8">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-4"
                style="background: rgba(201,168,76,0.1); border: 1px solid rgba(201,168,76,0.3);">🌿</div>
            <h1 class="text-3xl font-bold" style="font-family:'Cormorant Garamond',serif; color:#0d1e13;">Welcome back</h1>
            <p class="text-sm mt-1.5" style="color:#7a9180;">Sign in to your BushXplorer account</p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-stone-200 p-8">
            <form wire:submit="login" class="space-y-5">

                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-stone-400 mb-1.5">Email</label>
                    <input type="email" wire:model="email" autofocus autocomplete="email"
                        class="w-full px-4 py-3 border border-stone-200 rounded-xl text-stone-900 text-sm bg-white
                               focus:outline-none focus:ring-2 focus:ring-green-600/20 focus:border-green-700 transition-colors"
                        placeholder="you@example.com"/>
                    @error('email') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-stone-400 mb-1.5">Password</label>
                    <input type="password" wire:model="password" autocomplete="current-password"
                        class="w-full px-4 py-3 border border-stone-200 rounded-xl text-stone-900 text-sm bg-white
                               focus:outline-none focus:ring-2 focus:ring-green-600/20 focus:border-green-700 transition-colors"/>
                    @error('password') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" wire:model="remember" class="w-4 h-4 rounded border-stone-300 text-green-600 focus:ring-green-500"/>
                        <span class="text-xs text-stone-500">Remember me</span>
                    </label>
                </div>

                <button type="submit" wire:loading.attr="disabled"
                    class="w-full py-3 rounded-xl font-semibold text-sm text-white transition-all
                           bg-green-700 hover:bg-green-800 active:scale-[0.99] disabled:opacity-60 shadow-sm">
                    <span wire:loading.remove>Sign in &rarr;</span>
                    <span wire:loading>Signing in&hellip;</span>
                </button>

            </form>

            <p class="text-center text-sm text-stone-500 mt-6">
                No account?
                <a href="{{ route('register') }}" class="font-semibold text-green-700 hover:text-green-800 transition-colors">
                    Join BushXplorer
                </a>
            </p>
        </div>
    </div>
</div>
