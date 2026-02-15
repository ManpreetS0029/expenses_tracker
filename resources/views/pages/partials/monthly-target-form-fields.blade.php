@php
    $income = $target ? (float) $target->total_income : 0;
    $needs = $target ? (float) $target->needs : 0;
    $wants = $target ? (float) $target->wants : 0;
    $savings = $target ? (float) $target->savings : 0;
    $investments = $target ? (float) $target->investments : 0;
    $needsP = $income > 0 ? round(($needs / $income) * 100) : 50;
    $wantsP = $income > 0 ? round(($wants / $income) * 100) : 20;
    $savingsP = $income > 0 ? round(($savings / $income) * 100) : 20;
    $investmentsP = $income > 0 ? round(($investments / $income) * 100) : 10;
@endphp
<div class="space-y-4">
    <div>
        <label for="month_year" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Month &
            Year</label>
        <input type="month" name="month_year" id="month_year"
            value="{{ $target ? $target->month->format('Y-m') : date('Y-m') }}" required class="input-field">
        @error('month_year')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
    </div>
    <div>
        <label for="total_income" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Total Expected
            Income</label>
        <input type="number" name="total_income" id="total_income" step="0.01"
            value="{{ old('total_income', $income) }}" placeholder="0" class="input-field">
        @error('total_income')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label for="needs" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Needs</label>
            <input type="number" name="needs" id="needs" step="0.01" value="{{ old('needs', $needs) }}"
                class="input-field">
        </div>
        <div>
            <label for="wants" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Wants</label>
            <input type="number" name="wants" id="wants" step="0.01" value="{{ old('wants', $wants) }}"
                class="input-field">
        </div>
        <div>
            <label for="savings" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Savings</label>
            <input type="number" name="savings" id="savings" step="0.01" value="{{ old('savings', $savings) }}"
                class="input-field">
        </div>
        <div>
            <label for="investments"
                class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Investments</label>
            <input type="number" name="investments" id="investments" step="0.01"
                value="{{ old('investments', $investments) }}" class="input-field">
        </div>
    </div>
    <button type="submit" class="btn-primary w-full sm:w-auto">Save</button>
</div>