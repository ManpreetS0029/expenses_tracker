@extends('layouts.app')

@section('title', 'Two-Factor Auth')

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
                    <h2 class="text-sm font-semibold text-zinc-900 dark:text-zinc-50">Two-Factor Authentication</h2>
                    <p class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-400">Add an extra layer of security to your
                        account.</p>

                    <div class="mt-5 space-y-3">
                        <p class="text-sm text-zinc-600 dark:text-zinc-400">Two-factor authentication is available when
                            enabled in the application. Use your authentication app or recovery codes to sign in.</p>
                        <div class="flex items-center gap-2">
                            <span
                                class="flex size-2 rounded-full {{ auth()->user()->two_factor_secret ? 'bg-emerald-500' : 'bg-zinc-400' }}"></span>
                            <span
                                class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ auth()->user()->two_factor_secret ? 'Enabled' : 'Disabled' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection