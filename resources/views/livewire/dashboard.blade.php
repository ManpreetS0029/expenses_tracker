<div class="space-y-6" x-data="dashboardCharts">
    <!-- Header -->
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-neutral-900 dark:text-white">Dashboard</h1>
            <p class="text-sm text-neutral-500 mt-1">{{ $currentMonth }} Overview</p>
        </div>
        <div class="flex gap-2">
            <select wire:model.live="yearFilter"
                class="px-4 py-2 text-sm rounded-lg border-2 border-neutral-300 dark:border-neutral-600 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 dark:bg-neutral-900/90 dark:text-white">
                <option value="">All Years</option>
                @foreach($availableYears as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endforeach
            </select>
            <select wire:model.live="monthFilter"
                class="px-4 py-2 text-sm rounded-lg border-2 border-neutral-300 dark:border-neutral-600 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 dark:bg-neutral-900/90 dark:text-white">
                <option value="">All Months</option>
                @foreach(range(1, 12) as $m)
                    <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Key Metrics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Monthly Income -->
        <div
            class="bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 p-6 rounded-2xl border border-green-200 dark:border-green-800 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-3">
                <div class="p-3 bg-green-100 dark:bg-green-800/30 rounded-xl">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <span class="text-xs font-semibold text-green-600 dark:text-green-400 uppercase">Income</span>
            </div>
            <h2 class="text-3xl font-bold text-green-900 dark:text-white mb-1">
                ₹{{ number_format($monthlyIncome, 0) }}
            </h2>
            <p class="text-xs text-green-700 dark:text-green-300">This month's earnings</p>
        </div>

        <!-- Monthly Expenses -->
        <div
            class="bg-gradient-to-br from-red-50 to-orange-50 dark:from-red-900/20 dark:to-orange-900/20 p-6 rounded-2xl border border-red-200 dark:border-red-800 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-3">
                <div class="p-3 bg-red-100 dark:bg-red-800/30 rounded-xl">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                </div>
                <span class="text-xs font-semibold text-red-600 dark:text-red-400 uppercase">Expenses</span>
            </div>
            <h2 class="text-3xl font-bold text-red-900 dark:text-white mb-1">
                ₹{{ number_format($monthlyExpenses, 0) }}
            </h2>
            <p class="text-xs text-red-700 dark:text-red-300">
                {{ $monthlyIncome > 0 ? number_format(($monthlyExpenses / $monthlyIncome) * 100, 1) : 0 }}% of income
            </p>
        </div>

        <!-- Monthly Savings -->
        <div
            class="bg-gradient-to-br from-blue-50 to-cyan-50 dark:from-blue-900/20 dark:to-cyan-900/20 p-6 rounded-2xl border border-blue-200 dark:border-blue-800 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-3">
                <div class="p-3 bg-blue-100 dark:bg-blue-800/30 rounded-xl">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 8h6m-5 0a3 3 0 110 6H9l3 3m-3-6h6m6 1a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-xs font-semibold text-blue-600 dark:text-blue-400 uppercase">Savings</span>
            </div>
            <h2 class="text-3xl font-bold text-blue-900 dark:text-white mb-1">
                ₹{{ number_format($monthlySavings, 0) }}
            </h2>
            <p class="text-xs text-blue-700 dark:text-blue-300">
                {{ number_format($savingsRate, 1) }}% savings rate
            </p>
        </div>

        <!-- Net Balance -->
        <div
            class="bg-gradient-to-br from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 p-6 rounded-2xl border border-purple-200 dark:border-purple-800 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-3">
                <div class="p-3 bg-purple-100 dark:bg-purple-800/30 rounded-xl">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-xs font-semibold text-purple-600 dark:text-purple-400 uppercase">Net Worth</span>
            </div>
            <h2 class="text-3xl font-bold text-purple-900 dark:text-white mb-1">
                ₹{{ number_format($lifetimeBalance, 0) }}
            </h2>
            <p class="text-xs text-purple-700 dark:text-purple-300">Total balance</p>
        </div>
    </div>

    <!-- Quick Insights -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Daily Average -->
        <div
            class="bg-white dark:bg-neutral-800/90 p-5 rounded-xl border border-neutral-200 dark:border-neutral-600 shadow-sm dark:shadow-lg dark:shadow-black/10">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-neutral-500 dark:text-neutral-400">Daily Average</p>
                    <p class="text-2xl font-bold text-neutral-900 dark:text-white mt-1">
                        ₹{{ number_format($avgDailySpending, 0) }}
                    </p>
                </div>
                <div class="p-3 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg">
                    <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Projected Expenses -->
        <div
            class="bg-white dark:bg-neutral-800/90 p-5 rounded-xl border border-neutral-200 dark:border-neutral-600 shadow-sm dark:shadow-lg dark:shadow-black/10">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-neutral-500 dark:text-neutral-400">Projected Expenses</p>
                    <p class="text-2xl font-bold text-neutral-900 dark:text-white mt-1">
                        ₹{{ number_format($projectedExpenses, 0) }}
                    </p>
                </div>
                <div class="p-3 bg-amber-50 dark:bg-amber-900/30 rounded-lg">
                    <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Budget Status -->
        <div
            class="bg-white dark:bg-neutral-800/90 p-5 rounded-xl border border-neutral-200 dark:border-neutral-600 shadow-sm dark:shadow-lg dark:shadow-black/10">
            <div class="flex items-center justify-between mb-2">
                <p class="text-sm text-neutral-500 dark:text-neutral-400">Budget Used</p>
                <p class="text-sm font-bold text-neutral-900 dark:text-white">
                    {{ number_format($budgetUsedPercent, 1) }}%
                </p>
            </div>
            <div class="w-full bg-neutral-200 dark:bg-neutral-700 rounded-full h-2.5">
                <div class="h-2.5 rounded-full transition-all duration-500 
                    {{ $budgetUsedPercent > 100 ? 'bg-red-600' : ($budgetUsedPercent > 80 ? 'bg-amber-500' : 'bg-green-600') }}"
                    style="width: {{ min($budgetUsedPercent, 100) }}%">
                </div>
            </div>
            <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-2">
                ₹{{ number_format($monthlyExpenses, 0) }} of ₹{{ number_format($totalBudget, 0) }}
            </p>
        </div>
    </div>

    <!-- Classification Budget Breakdown -->
    @if(!empty($classificationBudgets))
        <div
            class="bg-white dark:bg-neutral-800/90 p-6 rounded-2xl border border-neutral-200 dark:border-neutral-600 shadow-sm dark:shadow-lg dark:shadow-black/10">
            <h3 class="text-lg font-semibold text-neutral-900 dark:text-white mb-5">Budget by Classification</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                @foreach($classificationBudgets as $classification)
                    @php
                        $isOver = $classification['percent'] > 100;
                        $isWarning = $classification['percent'] > 80 && $classification['percent'] <= 100;
                        $isGood = $classification['percent'] <= 80;
                    @endphp
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                {{ $classification['name'] }}
                            </span>
                            <span
                                class="text-xs font-bold px-2 py-1 rounded 
                                {{ $isOver ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' : ($isWarning ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' : 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400') }}">
                                {{ number_format($classification['percent'], 0) }}%
                            </span>
                        </div>
                        <div class="w-full bg-neutral-200 dark:bg-neutral-700 rounded-full h-2 overflow-hidden">
                            <div class="h-2 rounded-full transition-all duration-500 
                                {{ $isOver ? 'bg-red-600' : ($isWarning ? 'bg-amber-500' : 'bg-green-600') }}"
                                style="width: {{ min($classification['percent'], 100) }}%">
                            </div>
                        </div>
                        <div class="flex justify-between text-xs text-neutral-500 dark:text-neutral-400">
                            <span>₹{{ number_format($classification['spent'], 0) }}</span>
                            <span>₹{{ number_format($classification['budget'], 0) }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Charts Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Income vs Expenses Trend -->
        <div
            class="bg-white dark:bg-neutral-800/90 p-6 rounded-2xl border border-neutral-200 dark:border-neutral-600 shadow-sm dark:shadow-lg dark:shadow-black/10">
            <h3 class="text-lg font-semibold text-neutral-900 dark:text-white mb-5">Income vs Expenses (6 Months)</h3>
            <div class="h-80" wire:ignore>
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        <!-- Expense Distribution -->
        <div
            class="bg-white dark:bg-neutral-800/90 p-6 rounded-2xl border border-neutral-200 dark:border-neutral-600 shadow-sm dark:shadow-lg dark:shadow-black/10">
            <h3 class="text-lg font-semibold text-neutral-900 dark:text-white mb-5">Expense by Classification</h3>
            <div class="h-80 flex items-center justify-center" wire:ignore>
                <canvas id="classificationChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Daily & Weekly Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Daily Spending -->
        <div
            class="lg:col-span-2 bg-white dark:bg-neutral-800 p-6 rounded-2xl border border-neutral-200 dark:border-neutral-700 shadow-sm">
            <h3 class="text-lg font-semibold text-neutral-900 dark:text-white mb-5">Daily Spending This Month</h3>
            <div class="h-64" wire:ignore>
                <canvas id="dailyChart"></canvas>
            </div>
        </div>

        <!-- Weekly Pattern -->
        <div
            class="bg-white dark:bg-neutral-800/90 p-6 rounded-2xl border border-neutral-200 dark:border-neutral-600 shadow-sm dark:shadow-lg dark:shadow-black/10">
            <h3 class="text-lg font-semibold text-neutral-900 dark:text-white mb-5">Spending by Weekday</h3>
            <div class="h-64" wire:ignore>
                <canvas id="weekdayChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Top 5 Spending Categories -->
    <div
        class="bg-white dark:bg-neutral-800/90 p-6 rounded-2xl border border-neutral-200 dark:border-neutral-600 shadow-sm dark:shadow-lg dark:shadow-black/10">
        <h3 class="text-lg font-semibold text-neutral-900 dark:text-white mb-5">Top 5 Spending Categories</h3>
        @if(!empty($topCategories))
            <div class="space-y-4">
                @foreach($topCategories as $index => $category)
                    @php
                        $colors = [
                            ['bg' => 'bg-blue-500', 'text' => 'text-blue-600 dark:text-blue-400'],
                            ['bg' => 'bg-amber-500', 'text' => 'text-amber-600 dark:text-amber-400'],
                            ['bg' => 'bg-green-500', 'text' => 'text-green-600 dark:text-green-400'],
                            ['bg' => 'bg-red-500', 'text' => 'text-red-600 dark:text-red-400'],
                            ['bg' => 'bg-purple-500', 'text' => 'text-purple-600 dark:text-purple-400'],
                        ];
                        $color = $colors[$index % count($colors)];
                    @endphp
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center justify-center w-8 h-8 rounded-lg {{ $color['bg'] }} text-white text-sm font-bold">
                                    {{ $index + 1 }}
                                </div>
                                <span class="text-sm font-medium text-neutral-900 dark:text-white">
                                    {{ $category['name'] }}
                                </span>
                            </div>
                            <div class="text-right">
                                <span class="text-sm font-bold {{ $color['text'] }}">
                                    ₹{{ number_format($category['total'], 0) }}
                                </span>
                                <span class="text-xs text-neutral-500 dark:text-neutral-400 ml-2">
                                    {{ number_format($category['percentage'], 1) }}%
                                </span>
                            </div>
                        </div>
                        <div class="w-full bg-neutral-200 dark:bg-neutral-700 rounded-full h-2 overflow-hidden">
                            <div class="h-2 rounded-full transition-all duration-500 {{ $color['bg'] }}"
                                style="width: {{ min($category['percentage'], 100) }}%">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="flex items-center justify-center py-12 text-neutral-500 dark:text-neutral-400">
                <div class="text-center">
                    <svg class="w-16 h-16 mx-auto mb-4 text-neutral-300 dark:text-neutral-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <p class="text-sm">No spending data available this month</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Recent Transactions -->
    <div
        class="bg-white dark:bg-neutral-800/90 p-6 rounded-2xl border border-neutral-200 dark:border-neutral-600 shadow-sm dark:shadow-lg dark:shadow-black/10">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">Recent Transactions</h3>
            <a href="{{ route('expenses') }}"
                class="text-sm font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition-colors">
                View All →
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-xs font-semibold text-neutral-500 dark:text-neutral-400 uppercase border-b border-neutral-200 dark:border-neutral-700">
                        <th class="pb-3 text-left">Date</th>
                        <th class="pb-3 text-left">Description</th>
                        <th class="pb-3 text-left">Category</th>
                        <th class="pb-3 text-left">Type</th>
                        <th class="pb-3 text-right">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100 dark:divide-neutral-700">
                    @forelse($recentExpenses as $expense)
                        <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700/50 transition-colors">
                            <td class="py-3 text-sm text-neutral-600 dark:text-neutral-400">
                                {{ Carbon\Carbon::parse($expense->date)->format('M d, Y') }}
                            </td>
                            <td class="py-3 text-sm text-neutral-900 dark:text-white font-medium">
                                {{ $expense->description ?? 'No description' }}
                            </td>
                            <td class="py-3 text-sm text-neutral-600 dark:text-neutral-400">
                                {{ $expense->category->name ?? 'Unknown' }}
                            </td>
                            <td class="py-3">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $expense->type === 'credit' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' }}">
                                    {{ ucfirst($expense->type) }}
                                </span>
                            </td>
                            <td class="py-3 text-sm font-bold text-right 
                                {{ $expense->type === 'credit' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ $expense->type === 'credit' ? '+' : '-' }}₹{{ number_format($expense->amount, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-10 text-center text-neutral-500 dark:text-neutral-400">
                                No transactions found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @script
        <script>
            Alpine.data('dashboardCharts', () => ({
                charts: {},
                
                init() {
                    this.initCharts();
                    
                    Livewire.on('init-charts', () => {
                        this.destroyCharts();
                        this.initCharts();
                    });
                },
                
                destroyCharts() {
                    Object.keys(this.charts).forEach(key => {
                        if (this.charts[key] && typeof this.charts[key].destroy === 'function') {
                            try {
                                this.charts[key].destroy();
                            } catch (e) {
                                console.warn('Error destroying chart:', key, e);
                            }
                        }
                    });
                    this.charts = {};
                },
                
                initCharts() {
                    if (typeof Chart === 'undefined') {
                        setTimeout(() => this.initCharts(), 200);
                        return;
                    }
                    
                    const isDark = document.documentElement.classList.contains('dark');
                    const textColor = isDark ? '#d1d5db' : '#737373';
                    const gridColor = isDark ? '#3f3f46' : '#e5e5e5';
                    const chartBackground = isDark ? 'rgba(63, 63, 70, 0.3)' : 'rgba(249, 250, 251, 0.8)';
                    
                    Chart.defaults.color = textColor;
                    Chart.defaults.font.family = 'inherit';
                    
                    // Trend Chart
                    const trendCtx = document.getElementById('trendChart');
                    if (trendCtx) {
                        this.charts.trend = new Chart(trendCtx, {
                            type: 'line',
                            data: {
                                labels: @json($trendLabels),
                                datasets: [{
                                    label: 'Income',
                                    data: @json($trendIncome),
                                    borderColor: '#10b981',
                                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                    fill: true,
                                    tension: 0.4,
                                    pointRadius: 4,
                                    pointHoverRadius: 6
                                }, {
                                    label: 'Expenses',
                                    data: @json($trendExpenses),
                                    borderColor: '#ef4444',
                                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                                    fill: true,
                                    tension: 0.4,
                                    pointRadius: 4,
                                    pointHoverRadius: 6
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                interaction: { intersect: false, mode: 'index' },
                                plugins: {
                                    legend: { 
                                        position: 'top', 
                                        align: 'end',
                                        labels: {
                                            color: textColor,
                                            padding: 12,
                                            font: { size: 12 }
                                        }
                                    },
                                    tooltip: {
                                        backgroundColor: isDark ? '#18181b' : '#ffffff',
                                        titleColor: isDark ? '#f3f4f6' : '#111827',
                                        bodyColor: isDark ? '#d1d5db' : '#374151',
                                        borderColor: isDark ? '#3f3f46' : '#e5e7eb',
                                        borderWidth: 1,
                                        callbacks: {
                                            label: (context) => context.dataset.label + ': ₹' + context.parsed.y.toLocaleString()
                                        }
                                    }
                                },
                                scales: {
                                    x: { grid: { display: false } },
                                    y: {
                                        grid: { color: gridColor },
                                        ticks: {
                                            callback: (value) => '₹' + value.toLocaleString()
                                        }
                                    }
                                }
                            }
                        });
                    }
                    
                    // Classification Doughnut Chart
                    const classCtx = document.getElementById('classificationChart');
                    if (classCtx) {
                        this.charts.classification = new Chart(classCtx, {
                            type: 'doughnut',
                            data: {
                                labels: ['Needs', 'Wants', 'Savings', 'Investments'],
                                datasets: [{
                                    data: @json(array_values($classifications)),
                                    backgroundColor: ['#3b82f6', '#f59e0b', '#10b981', '#8b5cf6'],
                                    borderWidth: 0,
                                    hoverOffset: 15
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                cutout: '70%',
                                plugins: {
                                    legend: { 
                                        position: 'bottom', 
                                        labels: { 
                                            padding: 20, 
                                            usePointStyle: true,
                                            color: textColor
                                        } 
                                    },
                                    tooltip: {
                                        backgroundColor: isDark ? '#18181b' : '#ffffff',
                                        titleColor: isDark ? '#f3f4f6' : '#111827',
                                        bodyColor: isDark ? '#d1d5db' : '#374151',
                                        borderColor: isDark ? '#3f3f46' : '#e5e7eb',
                                        borderWidth: 1,
                                        callbacks: {
                                            label: (context) => context.label + ': ₹' + context.parsed.toLocaleString()
                                        }
                                    }
                                }
                            }
                        });
                    }
                    
                    // Daily Chart
                    const dailyCtx = document.getElementById('dailyChart');
                    if (dailyCtx) {
                        this.charts.daily = new Chart(dailyCtx, {
                            type: 'bar',
                            data: {
                                labels: @json($dailyLabels),
                                datasets: [{
                                    label: 'Daily Spending',
                                    data: @json($dailyData),
                                    backgroundColor: '#8b5cf6',
                                    borderRadius: 6
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { display: false },
                                    tooltip: {
                                        backgroundColor: isDark ? '#18181b' : '#ffffff',
                                        titleColor: isDark ? '#f3f4f6' : '#111827',
                                        bodyColor: isDark ? '#d1d5db' : '#374151',
                                        borderColor: isDark ? '#3f3f46' : '#e5e7eb',
                                        borderWidth: 1,
                                        callbacks: {
                                            label: (context) => 'Spent: ₹' + context.parsed.y.toLocaleString()
                                        }
                                    }
                                },
                                scales: {
                                    x: { 
                                        grid: { display: false },
                                        ticks: { color: textColor }
                                    },
                                    y: {
                                        grid: { color: gridColor },
                                        ticks: {
                                            color: textColor,
                                            callback: (value) => '₹' + value.toLocaleString()
                                        }
                                    }
                                }
                            }
                        });
                    }
                    
                    // Weekday Chart
                    const weekdayCtx = document.getElementById('weekdayChart');
                    if (weekdayCtx) {
                        this.charts.weekday = new Chart(weekdayCtx, {
                            type: 'bar',
                            data: {
                                labels: @json($weekdayLabels),
                                datasets: [{
                                    label: 'Spending',
                                    data: @json($weekdayData),
                                    backgroundColor: '#06b6d4',
                                    borderRadius: 6
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { display: false },
                                    tooltip: {
                                        backgroundColor: isDark ? '#18181b' : '#ffffff',
                                        titleColor: isDark ? '#f3f4f6' : '#111827',
                                        bodyColor: isDark ? '#d1d5db' : '#374151',
                                        borderColor: isDark ? '#3f3f46' : '#e5e7eb',
                                        borderWidth: 1,
                                        callbacks: {
                                            label: (context) => '₹' + context.parsed.y.toLocaleString()
                                        }
                                    }
                                },
                                scales: {
                                    x: { 
                                        grid: { display: false },
                                        ticks: { color: textColor }
                                    },
                                    y: {
                                        grid: { color: gridColor },
                                        ticks: {
                                            color: textColor,
                                            callback: (value) => '₹' + value.toLocaleString()
                                        }
                                    }
                                }
                            }
                        });
                    }
                }
            }));
        </script>
    @endscript
</div>
