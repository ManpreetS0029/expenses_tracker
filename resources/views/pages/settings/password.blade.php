@extends('layouts.app')

@section('title', 'Password')

@section('content')
    <div class="space-y-5">
        <div>
            <h1 class="text-lg font-semibold text-zinc-900 dark:text-zinc-50">Settings</h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Manage your account</p>
        </div>

        <div class="flex flex-col gap-5 md:flex-row md:items-start">
            @include('partials.settings-nav')
            <div class="flex-1 min-w-0 max-w-lg w-full">
                <div class="card p-5">
                    <h2 class="text-sm font-semibold text-zinc-900 dark:text-zinc-50">Password</h2>
                    <p class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-400">Use a strong password to keep your account
                        secure.</p>

                    <form method="POST" action="{{ url('/user/password') }}" class="mt-5 space-y-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <label for="current_password"
                                class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Current
                                Password</label>
                            <input type="password" name="current_password" id="current_password" required
                                class="input-field" placeholder="Enter current password">
                            @error('current_password')<span
                            class="mt-1 block text-xs text-red-500">{{ $message }}</span>@enderror
                        </div>
                        <div>
                            <label for="password"
                                class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">New Password</label>
                            <input type="password" name="password" id="password" required class="input-field"
                                placeholder="Enter new password">
                            @error('password')<span class="mt-1 block text-xs text-red-500">{{ $message }}</span>@enderror
                        </div>
                        <div>
                            <label for="password_confirmation"
                                class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Confirm
                                Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                class="input-field" placeholder="Confirm new password">
                        </div>
                        <button type="submit" class="btn-primary">Update password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection