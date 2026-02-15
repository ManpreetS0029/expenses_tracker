@extends('layouts.app')

@section('title', 'Appearance')

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
                    <h2 class="text-sm font-semibold text-zinc-900 dark:text-zinc-50">Appearance</h2>
                    <p class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-400">Choose your preferred theme.</p>

                    <form method="POST" action="{{ route('appearance.update') }}" class="mt-5 space-y-4">
                        @csrf
                        <div>
                            <label for="theme"
                                class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Theme</label>
                            <select name="theme" id="theme" onchange="this.form.submit()"
                                class="input-field w-full max-w-xs">
                                <option value="light" {{ request()->cookie('theme') === 'light' ? 'selected' : '' }}>Light
                                </option>
                                <option value="dark" {{ request()->cookie('theme', 'dark') === 'dark' ? 'selected' : '' }}>
                                    Dark</option>
                            </select>
                        </div>
                    </form>
                    <p class="mt-3 text-xs text-zinc-500 dark:text-zinc-400">Your preference is saved automatically.</p>
                </div>
            </div>
        </div>
    </div>
@endsection