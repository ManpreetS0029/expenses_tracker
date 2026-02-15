{{-- Money left hero card --}}
<div class="card relative overflow-hidden p-4 sm:p-5">
    <div class="absolute inset-0 bg-gradient-to-br from-violet-600/10 via-transparent to-indigo-600/10"></div>
    <div class="relative flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="stat-label">Money left</p>
            <p
                class="mt-1 text-2xl font-bold sm:text-3xl tabular-nums {{ $moneyLeft >= 0 ? 'bg-gradient-to-r from-emerald-400 to-cyan-400 bg-clip-text text-transparent' : 'text-rose-400' }}">
                ₹{{ number_format($moneyLeft, 0) }}</p>
            <p class="mt-0.5 text-xs {{ $moneyLeft >= 0 ? 'text-zinc-400' : 'text-rose-400' }}">
                {{ $moneyLeft >= 0 ? 'Available to spend' : 'Over budget' }}
            </p>
        </div>
        <span
            class="inline-flex self-start items-center rounded-lg bg-violet-500/10 border border-violet-500/20 px-2.5 py-1 text-xs font-semibold text-violet-400 tabular-nums">
            {{ number_format($moneyLeftPercent, 1) }}% of income
        </span>
    </div>
</div>

{{-- Key metrics --}}
<div class="grid min-w-0 grid-cols-2 gap-3 lg:grid-cols-4">
    <div class="stat-card border-l-2 border-l-emerald-500">
        <p class="stat-label">Income</p>
        <p class="stat-value text-emerald-400 tabular-nums">
            ₹{{ number_format($monthlyIncome, 0) }}</p>
        <p class="stat-sub">This month</p>
    </div>
    <div class="stat-card border-l-2 border-l-rose-500">
        <p class="stat-label">Expenses</p>
        <p class="stat-value text-rose-400 tabular-nums">₹{{ number_format($monthlyExpenses, 0) }}</p>
        <p class="stat-sub">{{ $monthlyIncome > 0 ? number_format(($monthlyExpenses / $monthlyIncome) * 100, 1) : 0 }}%
            of income</p>
    </div>
    <div class="stat-card border-l-2 border-l-cyan-500">
        <p class="stat-label">Savings</p>
        <p class="stat-value text-cyan-400 tabular-nums">₹{{ number_format($monthlySavings, 0) }}</p>
        <p class="stat-sub">{{ number_format($savingsRate, 1) }}% rate</p>
    </div>
    <div class="stat-card border-l-2 border-l-amber-500">
        <p class="stat-label">Net worth</p>
        <p class="stat-value text-amber-400 tabular-nums">₹{{ number_format($lifetimeBalance, 0) }}</p>
        <p class="stat-sub">Total balance</p>
    </div>
</div>

{{-- Secondary metrics --}}
<div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
    <div class="stat-card">
        <p class="stat-label">Daily average</p>
        <p class="mt-1 text-lg font-semibold text-violet-400 tabular-nums">
            ₹{{ number_format($avgDailySpending, 0) }}</p>
    </div>
    <div class="stat-card">
        <p class="stat-label">Projected expenses</p>
        <p class="mt-1 text-lg font-semibold text-orange-400 tabular-nums">
            ₹{{ number_format($projectedExpenses, 0) }}</p>
    </div>
    <div class="stat-card">
        <div class="flex items-center justify-between">
            <p class="stat-label">Budget used</p>
            <span
                class="text-sm font-bold tabular-nums {{ $budgetUsedPercent > 100 ? 'text-rose-400' : ($budgetUsedPercent > 80 ? 'text-amber-400' : 'text-emerald-400') }}">{{ number_format($budgetUsedPercent, 1) }}%</span>
        </div>
        <div class="mt-2.5 h-2 w-full overflow-hidden rounded-full bg-zinc-800">
            <div class="h-full rounded-full transition-all {{ $budgetUsedPercent > 100 ? 'bg-gradient-to-r from-rose-500 to-pink-500' : ($budgetUsedPercent > 80 ? 'bg-gradient-to-r from-amber-500 to-orange-500' : 'bg-gradient-to-r from-emerald-500 to-cyan-500') }}"
                style="width: {{ min($budgetUsedPercent, 100) }}%"></div>
        </div>
        <p class="mt-1.5 text-xs text-zinc-400 tabular-nums">
            ₹{{ number_format($monthlyExpenses, 0) }} / ₹{{ number_format($totalBudget, 0) }}</p>
    </div>
</div>

@if(!empty($classificationBudgets))
    <div class="card p-4 sm:p-5">
        <h3 class="text-sm font-semibold text-zinc-50">Budget by classification</h3>
        <div class="mt-4 grid grid-cols-2 gap-4 lg:grid-cols-4">
            @foreach($classificationBudgets as $classification)
                @php
                    $isOver = $classification['percent'] > 100;
                    $isWarning = $classification['percent'] > 80 && $classification['percent'] <= 100;
                    $colors = ['Needs' => 'from-blue-500 to-indigo-500', 'Wants' => 'from-pink-500 to-rose-500', 'Savings' => 'from-emerald-500 to-cyan-500', 'Investments' => 'from-amber-500 to-orange-500'];
                    $barColor = $isOver ? 'bg-gradient-to-r from-rose-500 to-pink-500' : ($isWarning ? 'bg-gradient-to-r from-amber-500 to-orange-500' : 'bg-gradient-to-r ' . ($colors[$classification['name']] ?? 'from-violet-500 to-indigo-500'));
                @endphp
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-zinc-300">{{ $classification['name'] }}</span>
                        <span
                            class="rounded-md px-1.5 py-0.5 text-xs font-bold tabular-nums {{ $isOver ? 'bg-rose-500/15 text-rose-400' : ($isWarning ? 'bg-amber-500/15 text-amber-400' : 'bg-zinc-800 text-zinc-300') }}">{{ number_format($classification['percent'], 0) }}%</span>
                    </div>
                    <div class="h-1.5 w-full overflow-hidden rounded-full bg-zinc-800">
                        <div class="h-full rounded-full {{ $barColor }}"
                            style="width: {{ min($classification['percent'], 100) }}%"></div>
                    </div>
                    <div class="flex justify-between text-xs text-zinc-500 tabular-nums">
                        <span>₹{{ number_format($classification['spent'], 0) }}</span>
                        <span>₹{{ number_format($classification['budget'], 0) }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif