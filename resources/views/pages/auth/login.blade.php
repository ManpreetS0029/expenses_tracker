<x-layouts::auth title="Log in">
    <div class="flex flex-col gap-6">
        {{-- Header --}}
        <div class="text-center">
            <h1 class="text-2xl font-bold text-zinc-50">Welcome back</h1>
            <p class="mt-2 text-sm text-zinc-400">Enter your credentials to access your account</p>
        </div>

        {{-- Session Status --}}
        @if (session('status'))
            <div
                class="rounded-lg bg-emerald-500/10 border border-emerald-500/20 px-4 py-3 text-sm text-emerald-400 text-center">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-5">
            @csrf

            {{-- Email --}}
            <div>
                <label for="email" class="block text-sm font-medium text-zinc-300 mb-1.5">Email address</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                    autocomplete="email" placeholder="email@example.com" class="input-field" />
                @error('email')
                    <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label for="password" class="block text-sm font-medium text-zinc-300">Password</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                            class="text-xs font-medium text-violet-400 hover:text-violet-300 transition-colors">Forgot
                            password?</a>
                    @endif
                </div>
                <input id="password" name="password" type="password" required autocomplete="current-password"
                    placeholder="••••••••" class="input-field" />
                @error('password')
                    <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Remember Me --}}
            <div class="flex items-center gap-2.5">
                <input id="remember" name="remember" type="checkbox" {{ old('remember') ? 'checked' : '' }}
                    class="h-4 w-4 rounded border-zinc-700 bg-zinc-800 text-violet-600 focus:ring-violet-500/40 focus:ring-offset-0" />
                <label for="remember" class="text-sm text-zinc-400 select-none">Remember me</label>
            </div>

            {{-- Submit --}}
            <button type="submit" class="btn-primary w-full py-2.5" data-test="login-button">
                Log in
            </button>
        </form>

        @if (Route::has('register'))
            <p class="text-center text-sm text-zinc-500">
                Don't have an account?
                <a href="{{ route('register') }}"
                    class="font-medium text-violet-400 hover:text-violet-300 transition-colors">Sign up</a>
            </p>
        @endif
    </div>
</x-layouts::auth>