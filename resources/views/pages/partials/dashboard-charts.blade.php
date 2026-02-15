<div class="grid min-w-0 grid-cols-1 gap-3 lg:grid-cols-2">
    <div class="card min-w-0 p-4 sm:p-5">
        <h3 class="text-sm font-semibold text-zinc-50">Income vs Expenses</h3>
        <p class="text-xs text-zinc-500">Last 6 months</p>
        <div class="mt-4 h-48 min-h-0 sm:h-64"><canvas id="trendChart"></canvas></div>
    </div>
    <div class="card min-w-0 p-4 sm:p-5">
        <h3 class="text-sm font-semibold text-zinc-50">Expense Breakdown</h3>
        <p class="text-xs text-zinc-500">By classification</p>
        <div class="mt-4 flex h-48 min-h-0 items-center justify-center sm:h-64"><canvas
                id="classificationChart"></canvas></div>
    </div>
</div>

<div class="grid min-w-0 grid-cols-1 gap-3 lg:grid-cols-3">
    <div class="card min-w-0 p-4 sm:p-5 lg:col-span-2">
        <h3 class="text-sm font-semibold text-zinc-50">
            {{ $isSingleMonth ? 'Daily Spending' : 'Monthly Spending' }}
        </h3>
        <p class="text-xs text-zinc-500">{{ $isSingleMonth ? 'This month' : 'By period' }}</p>
        <div class="mt-4 h-40 min-h-0 sm:h-56"><canvas id="dailyChart"></canvas></div>
    </div>
    <div class="card min-w-0 p-4 sm:p-5">
        <h3 class="text-sm font-semibold text-zinc-50">By Weekday</h3>
        <p class="text-xs text-zinc-500">Spending pattern</p>
        <div class="mt-4 h-40 min-h-0 sm:h-56"><canvas id="weekdayChart"></canvas></div>
    </div>
</div>

<div class="card p-4 sm:p-5">
    <h3 class="text-sm font-semibold text-zinc-50">Top Categories</h3>
    <p class="text-xs text-zinc-500">Top 5 by spending</p>
    @if(!empty($topCategories))
        @php
            $catColors = ['from-violet-500 to-indigo-500', 'from-rose-500 to-pink-500', 'from-amber-500 to-orange-500', 'from-cyan-500 to-teal-500', 'from-blue-500 to-indigo-500'];
        @endphp
        <div class="mt-4 space-y-3">
            @foreach($topCategories as $index => $category)
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <span
                                class="flex h-6 w-6 shrink-0 items-center justify-center rounded-md text-xs font-bold text-white bg-gradient-to-br {{ $catColors[$index] ?? $catColors[0] }}">{{ $index + 1 }}</span>
                            <span class="truncate text-sm font-medium text-zinc-100">{{ $category['name'] }}</span>
                        </div>
                        <div class="ml-2 flex shrink-0 items-center gap-2 text-right">
                            <span
                                class="text-sm font-bold text-zinc-100 tabular-nums">₹{{ number_format($category['total'], 0) }}</span>
                            <span
                                class="text-xs text-zinc-500 tabular-nums">{{ number_format($category['percentage'], 1) }}%</span>
                        </div>
                    </div>
                    <div class="h-1.5 w-full overflow-hidden rounded-full bg-zinc-800">
                        <div class="h-full rounded-full bg-gradient-to-r {{ $catColors[$index] ?? $catColors[0] }} transition-all"
                            style="width: {{ min($category['percentage'], 100) }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="mt-6 py-8 text-center text-sm text-zinc-500">No spending data this period</p>
    @endif
</div>