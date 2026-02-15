<x-layouts::auth title="Register">
    <div class="flex flex-col gap-6">
        {{-- Header --}}
        <div class="text-center">
            <h1 class="text-2xl font-bold text-zinc-50">Create an account</h1>
            <p class="mt-2 text-sm text-zinc-400">Enter your details below to get started</p>
        </div>

        {{-- Session Status --}}
        @if (session('status'))
            <div
                class="rounded-lg bg-emerald-500/10 border border-emerald-500/20 px-4 py-3 text-sm text-emerald-400 text-center">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-5">
            @csrf

            {{-- Name --}}
            <div>
                <label for="name" class="block text-sm font-medium text-zinc-300 mb-1.5">Full name</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus
                    autocomplete="name" placeholder="John Doe" class="input-field" />
                @error('name')
                    <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="block text-sm font-medium text-zinc-300 mb-1.5">Email address</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email"
                    placeholder="email@example.com" class="input-field" />
                @error('email')
                    <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div>
                <label for="password" class="block text-sm font-medium text-zinc-300 mb-1.5">Password</label>
                <input id="password" name="password" type="password" required autocomplete="new-password"
                    placeholder="••••••••" class="input-field" />
                @error('password')
                    <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-zinc-300 mb-1.5">Confirm
                    password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required
                    autocomplete="new-password" placeholder="••••••••" class="input-field" />
                @error('password_confirmation')
                    <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Submit --}}
            <button type="submit" class="btn-primary w-full py-2.5" data-test="register-user-button">
                Create account
            </button>
        </form>

        <p class="text-center text-sm text-zinc-500">
            Already have an account?
            <a href="{{ route('login') }}"
                class="font-medium text-violet-400 hover:text-violet-300 transition-colors">Log in</a>
        </p>
    </div>
</x-layouts::auth>