@extends('layouts.app')

@section('title', 'Currency')

@section('content')
    <div class="space-y-5">
        <div>
            <h1 class="text-lg font-semibold text-zinc-900 dark:text-zinc-50">Settings</h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Manage your account</p>
        </div>

        <div class="flex flex-col gap-5 md:flex-row md:items-start">
            @include('partials.settings-nav')
            <div class="flex-1 min-w-0 max-w-lg w-full space-y-4">
                <div class="card p-5">
                    <h2 class="text-sm font-semibold text-zinc-900 dark:text-zinc-50">Currency</h2>
                    <p class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-400">Set your preferred currency for displaying
                        amounts.</p>

                    <form method="POST" action="{{ route('currency.update') }}" class="mt-5 space-y-4">
                        @csrf
                        <div>
                            <label for="currency"
                                class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Select
                                Currency</label>
                            <select name="currency" id="currency" required class="input-field">
                                @foreach($availableCurrencies as $code => $details)
                                    <option value="{{ $code }}" {{ $currency === $code ? 'selected' : '' }}>
                                        {{ $details['symbol'] }} - {{ $details['name'] }} ({{ $code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="rounded-lg bg-zinc-50 p-3 dark:bg-zinc-800/50">
                            <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400">Currently selected</p>
                            <p class="mt-1 text-lg font-semibold text-zinc-900 dark:text-zinc-50">{{ $currency_symbol }}
                                {{ $availableCurrencies[$currency]['name'] ?? 'Unknown' }}</p>
                        </div>
                        <button type="submit" class="btn-primary">Save</button>
                    </form>
                </div>

                <div class="rounded-lg border border-zinc-200 bg-zinc-50 p-4 dark:border-zinc-700 dark:bg-zinc-800/30">
                    <p class="text-xs text-zinc-600 dark:text-zinc-400"><strong>Note:</strong> Changing currency only
                        affects display. Existing amounts won't be converted.</p>
                </div>
            </div>
        </div>
    </div>
@endsection