@extends('layouts.app')

@section('title', 'Profile')

@section('content')
    <div class="space-y-5">
        <div>
            <h1 class="text-lg font-semibold text-zinc-900 dark:text-zinc-50">Settings</h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Manage your account</p>
        </div>

        <div class="flex flex-col gap-5 md:flex-row md:items-start">
            @include('partials.settings-nav')
            <div class="flex-1 min-w-0 space-y-5">
                <div class="card p-5">
                    <h2 class="text-sm font-semibold text-zinc-900 dark:text-zinc-50">Profile</h2>
                    <p class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-400">Update your account information.</p>

                    <form method="POST" action="{{ url('/user/profile-information') }}" class="mt-5 space-y-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <label for="name"
                                class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', auth()->user()->name) }}"
                                required class="input-field" placeholder="Your name">
                            @error('name')<span class="mt-1 block text-xs text-red-500">{{ $message }}</span>@enderror
                        </div>
                        <div>
                            <label for="email"
                                class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email) }}"
                                required class="input-field" placeholder="you@example.com">
                            @error('email')<span class="mt-1 block text-xs text-red-500">{{ $message }}</span>@enderror
                        </div>
                        <button type="submit" class="btn-primary">Save changes</button>
                    </form>
                </div>

                <div class="card p-5">
                    <h3 class="text-sm font-semibold text-red-600 dark:text-red-400">Delete account</h3>
                    <p class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-400">Once deleted, all your data will be
                        permanently removed.</p>
                    <form method="POST" action="{{ route('user.destroy') }}" class="mt-4 space-y-4"
                        onsubmit="confirmSubmit(event, 'Are you sure? This cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <div>
                            <label for="password"
                                class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Password</label>
                            <input type="password" name="password" id="password" required class="input-field"
                                placeholder="Confirm your password">
                            @error('password')<span class="mt-1 block text-xs text-red-500">{{ $message }}</span>@enderror
                        </div>
                        <button type="submit" class="btn-danger">Delete account</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection