@php
    $currencies = $availableCurrencies ?? config('currencies.available', []);
    $defaultCurrency = $defaultCurrency ?? 'INR';
    $defaultSymbol = $defaultCurrencySymbol ?? '₹';
@endphp
<div class="space-y-4">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label for="date" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Date</label>
            <input type="date" name="date" id="date"
                value="{{ $expense ? $expense->date->format('Y-m-d') : date('Y-m-d') }}" class="input-field">
            @error('date')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
        </div>
        <div>
            <label for="amount" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Amount</label>
            <input type="number" name="amount" step="0.01" value="{{ old('amount', $expense?->amount) }}"
                placeholder="0.00" class="input-field">
            @error('amount')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
        </div>
    </div>
    <div>
        <label for="description"
            class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Description</label>
        <input type="text" name="description" id="description" value="{{ old('description', $expense?->description) }}"
            placeholder="e.g. Lunch" class="input-field">
        @error('description')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label for="category_id"
                class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Category</label>
            <select name="category_id" id="category_id" class="input-field">
                <option value="">Select</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id', $expense?->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            @error('category_id')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
        </div>
        <div>
            <label for="currency"
                class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Currency</label>
            <select name="currency" id="currency" class="input-field">
                @foreach($currencies as $code => $details)
                    <option value="{{ $code }}" data-symbol="{{ $details['symbol'] }}" {{ old('currency', $expense?->currency ?? $defaultCurrency) === $code ? 'selected' : '' }}>{{ $details['symbol'] }}
                        {{ $code }}
                    </option>
                @endforeach
            </select>
            <input type="hidden" name="currency_symbol" id="currency_symbol"
                value="{{ old('currency_symbol', $expense?->currency_symbol ?? $defaultSymbol) }}">
        </div>
    </div>
    <div>
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Classification</label>
        <div class="flex gap-2">
            <label class="flex-1 cursor-pointer">
                <input type="radio" name="classification" value="Needs" {{ old('classification', $expense?->classification ?? 'Needs') === 'Needs' ? 'checked' : '' }} class="peer sr-only">
                <span
                    class="block text-center rounded-lg border border-zinc-700 bg-zinc-800/50 px-3 py-2 text-sm font-medium text-zinc-400 peer-checked:border-blue-500 peer-checked:bg-blue-500/10 peer-checked:text-blue-400 transition-all cursor-pointer hover:border-zinc-600">Needs</span>
            </label>
            <label class="flex-1 cursor-pointer">
                <input type="radio" name="classification" value="Wants" {{ old('classification', $expense?->classification) === 'Wants' ? 'checked' : '' }} class="peer sr-only">
                <span
                    class="block text-center rounded-lg border border-zinc-700 bg-zinc-800/50 px-3 py-2 text-sm font-medium text-zinc-400 peer-checked:border-pink-500 peer-checked:bg-pink-500/10 peer-checked:text-pink-400 transition-all cursor-pointer hover:border-zinc-600">Wants</span>
            </label>
        </div>
    </div>
    <div class="flex items-center gap-3 pt-1">
        <button type="submit" class="btn-success">Save</button>
        <a href="{{ $cancelUrl ?? route('expenses') }}" class="btn-secondary">Cancel</a>
    </div>
</div>
