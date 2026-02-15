@php
    $currencies = $availableCurrencies ?? config('currencies.available', []);
    $defaultCurrency = $defaultCurrency ?? 'INR';
    $defaultSymbol = $defaultCurrencySymbol ?? '₹';
@endphp
<div class="space-y-4">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label for="date" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Date</label>
            <input type="date" name="date" value="{{ $credit ? $credit->date->format('Y-m-d') : date('Y-m-d') }}"
                required class="input-field">
            @error('date')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
        </div>
        <div>
            <label for="amount" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Amount</label>
            <input type="number" name="amount" step="0.01" value="{{ old('amount', $credit?->amount) }}" required
                class="input-field">
            @error('amount')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
        </div>
    </div>
    <div>
        <label for="description"
            class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Description</label>
        <input type="text" name="description" value="{{ old('description', $credit?->description) }}"
            class="input-field">
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label for="category_id"
                class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Category</label>
            <select name="category_id" required class="input-field">
                <option value="">Select</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id', $credit?->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            @error('category_id')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
        </div>
        <div>
            <label for="currency"
                class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Currency</label>
            <select name="currency" class="input-field">
                @foreach($currencies as $code => $details)
                    <option value="{{ $code }}" {{ old('currency', $credit?->currency ?? $defaultCurrency) === $code ? 'selected' : '' }}>{{ $details['symbol'] }} {{ $code }}</option>
                @endforeach
            </select>
            <input type="hidden" name="currency_symbol" id="credit_currency_symbol"
                value="{{ old('currency_symbol', $credit?->currency_symbol ?? $defaultSymbol) }}">
        </div>
    </div>
    <button type="submit" class="btn-primary w-full sm:w-auto">Save</button>
</div>