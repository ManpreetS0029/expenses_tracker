<div class="space-y-6" x-data="{
    trendChart: null,
    distChart: null,
    init() {
        this.initCharts();
        $wire.on('charts-updated', () => {
            this.initCharts();
        });
    },
    initCharts() {
        if (typeof Chart === 'undefined') {
            setTimeout(() => this.initCharts(), 200);
            return;
        }

        const trendCanvas = document.getElementById('trendChart');
        const distCanvas = document.getElementById('distributionChart');

        if (!trendCanvas || !distCanvas) return;

        const isDark = document.documentElement.classList.contains('dark');
        const textColor = isDark ? '#a3a3a3' : '#737373';
        const gridColor = isDark ? '#3f3f46' : '#e5e5e5';

        // Chart defaults
        Chart.defaults.color = textColor;
        Chart.defaults.font.family = 'inherit';

        // Trend Chart
        if (this.trendChart) this.trendChart.destroy();
        this.trendChart = new Chart(trendCanvas, {
            type: 'line',
            data: {
                labels: @json($trendLabels),
                datasets: [{
                    label: 'Income',
                    data: @json($incomeTrend),
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }, {
                    label: 'Expenses',
                    data: @json($expenseTrend),
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
                        labels: { boxWidth: 12, usePointStyle: true, pointStyle: 'circle' }
                    },
                    tooltip: {
                        padding: 12,
                        backgroundColor: isDark ? '#18181b' : '#ffffff',
                        titleColor: isDark ? '#ffffff' : '#18181b',
                        bodyColor: isDark ? '#a1a1aa' : '#71717a',
                        borderColor: isDark ? '#3f3f46' : '#e5e7eb',
                        borderWidth: 1
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { padding: 10 }
                    },
                    y: {
                        grid: { color: gridColor, drawBorder: false },
                        ticks: {
                            padding: 10,
                            callback: (value) => '₹' + value.toLocaleString()
                        }
                    }
                }
            }
        });

        // Distribution Chart
        if (this.distChart) this.distChart.destroy();
        this.distChart = new Chart(distCanvas, {
            type: 'doughnut',
            data: {
                labels: @json(array_keys($chartData)),
                datasets: [{
                    data: @json(array_values($chartData)),
                    backgroundColor: ['#3b82f6', '#f59e0b', '#10b981', '#8b5cf6'],
                    hoverOffset: 10,
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 20, boxWidth: 12, usePointStyle: true, pointStyle: 'circle' }
                    },
                    tooltip: {
                        padding: 12,
                        backgroundColor: isDark ? '#18181b' : '#ffffff',
                        titleColor: isDark ? '#ffffff' : '#18181b',
                        bodyColor: isDark ? '#a1a1aa' : '#71717a',
                        borderColor: isDark ? '#3f3f46' : '#e5e7eb',
                        borderWidth: 1
                    }
                }
            }
        });
    }
}">
    <!-- Header/Stats Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Balance -->
        <div
            class="bg-white dark:bg-neutral-800 p-6 rounded-2xl border border-neutral-200 dark:border-neutral-700 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-blue-50 dark:bg-blue-900/30 rounded-xl text-blue-600 dark:text-blue-400">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 12a2.25 2.25 0 0 0-2.25-2.25H15a3 3 0 1 1-6 0H5.25A2.25 2.25 0 0 0 3 12m18 0v6a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 9m18 0V6a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 6v3" />
                    </svg>
                </div>
                <span class="text-xs font-medium text-neutral-500 uppercase tracking-wider">Net Balance</span>
            </div>
            <h3 class="text-2xl font-bold text-neutral-900 dark:text-white">₹{{ number_format($lifetimeBalance, 2) }}
            </h3>
            <p class="text-xs text-neutral-500 mt-1">Total lifetime balance</p>
        </div>

        <!-- Monthly Savings -->
        <div
            class="bg-white dark:bg-neutral-800 p-6 rounded-2xl border border-neutral-200 dark:border-neutral-700 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-cyan-50 dark:bg-cyan-900/30 rounded-xl text-cyan-600 dark:text-cyan-400">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75m0 1.5v.75m0 1.5v.75m0 1.5V15m1.5-10.5h.75m1.5 0h.75m1.5 0h.75m1.5 0h.75m1.5 0h.75m1.5 0h.75m1.5 0h.75m1.5 0h.75m-14.25 15h.75m1.5 0h.75m1.5 0h.75m1.5 0h.75m1.5 0h.75m1.5 0h.75m1.5 0h.75m1.5 0h.75m-15-1.5V4.5A2.25 2.25 0 0 1 3.75 2.25h16.5a2.25 2.25 0 0 1 2.25 2.25v13.5a2.25 2.25 0 0 1-2.25 2.25h-16.5a2.25 2.25 0 0 1-2.25-2.25Z" />
                    </svg>
                </div>
                <span class="text-xs font-medium text-neutral-500 uppercase tracking-wider">Monthly Savings</span>
            </div>
            <h3 class="text-2xl font-bold text-neutral-900 dark:text-white">₹{{ number_format($monthlySavings, 2) }}
            </h3>
            <p class="text-xs text-neutral-500 mt-1">Savings this month</p>
        </div>

        <!-- Monthly Income -->
        <div
            class="bg-white dark:bg-neutral-800 p-6 rounded-2xl border border-neutral-200 dark:border-neutral-700 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-green-50 dark:bg-green-900/30 rounded-xl text-green-600 dark:text-green-400">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                </div>
                <span class="text-xs font-medium text-neutral-500 uppercase tracking-wider">Income</span>
            </div>
            <h3 class="text-2xl font-bold text-neutral-900 dark:text-white">₹{{ number_format($totalIncome, 2) }}</h3>
            <p class="text-xs text-neutral-500 mt-1">Total earnings this month</p>
        </div>

        <!-- Monthly Expenses -->
        <div
            class="bg-white dark:bg-neutral-800 p-6 rounded-2xl border border-neutral-200 dark:border-neutral-700 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-red-50 dark:bg-red-900/30 rounded-xl text-red-600 dark:text-red-400">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                    </svg>
                </div>
                <span class="text-xs font-medium text-neutral-500 uppercase tracking-wider">Expenses</span>
            </div>
            <h3 class="text-2xl font-bold text-neutral-900 dark:text-white">₹{{ number_format($monthlyExpenses, 2) }}
            </h3>
            <p class="text-xs text-neutral-500 mt-1">
                {{ $totalIncome > 0 ? round(($monthlyExpenses / $totalIncome) * 100, 1) . '%' : '0%' }} of income spent
            </p>
        </div>
    </div>

    <!-- Budget Status Section -->
    <div
        class="bg-white dark:bg-neutral-800 p-6 rounded-2xl border border-neutral-200 dark:border-neutral-700 shadow-sm">
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm font-medium text-neutral-500 uppercase tracking-wider">Budget Status</span>
            @php
                $targetAmount = $monthlyTarget ? ($monthlyTarget->needs + $monthlyTarget->wants) : 0;
                $percentUsed = $targetAmount > 0 ? min(round(($monthlyExpenses / $targetAmount) * 100, 1), 100) : 0;
            @endphp
            <span class="text-sm font-bold text-neutral-900 dark:text-white">{{ $percentUsed }}%</span>
        </div>
        <div class="w-full bg-neutral-200 dark:bg-neutral-700 rounded-full h-3">
            <div class="bg-purple-600 h-3 rounded-full transition-all duration-500" style="width: {{ $percentUsed }}%">
            </div>
        </div>
        <p class="text-xs text-neutral-500 mt-2">Spent ₹{{ number_format($monthlyExpenses, 2) }} out of planned
            ₹{{ number_format($targetAmount, 2) }} budget</p>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Trend Chart -->
        <div
            class="bg-white dark:bg-neutral-800 p-6 rounded-2xl border border-neutral-200 dark:border-neutral-700 shadow-sm">
            <h3 class="text-lg font-semibold text-neutral-900 dark:text-white mb-6">Income vs Expenses Trend</h3>
            <div class="relative h-80" wire:ignore>
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        <!-- Distribution Chart -->
        <div
            class="bg-white dark:bg-neutral-800 p-6 rounded-2xl border border-neutral-200 dark:border-neutral-700 shadow-sm">
            <h3 class="text-lg font-semibold text-neutral-900 dark:text-white mb-6">Expense Distribution (This Month)
            </h3>
            <div class="relative h-80" wire:ignore>
                <canvas id="distributionChart"></canvas>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Top Categories -->
        <div
            class="lg:col-span-1 bg-white dark:bg-neutral-800 p-6 rounded-2xl border border-neutral-200 dark:border-neutral-700 shadow-sm">
            <h3 class="text-lg font-semibold text-neutral-900 dark:text-white mb-6">Top Spending Categories</h3>
            <div class="space-y-4">
                @foreach($categoryBreakdown as $category)
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <span
                                class="text-sm font-medium text-neutral-700 dark:text-neutral-300">{{ $category['name'] }}</span>
                            <span
                                class="text-sm font-bold text-neutral-900 dark:text-white">₹{{ number_format($category['total'], 2) }}</span>
                        </div>
                        <div class="w-full bg-neutral-200 dark:bg-neutral-700 rounded-full h-1.5">
                            <div class="bg-blue-600 h-1.5 rounded-full"
                                style="width: {{ $monthlyExpenses > 0 ? ($category['total'] / $monthlyExpenses) * 100 : 0 }}%">
                            </div>
                        </div>
                    </div>
                @endforeach
                @if($categoryBreakdown->isEmpty())
                    <p class="text-neutral-500 text-sm italic">No expenses recorded this month.</p>
                @endif
            </div>
        </div>

        <!-- Recent Transactions -->
        <div
            class="lg:col-span-2 bg-white dark:bg-neutral-800 p-6 rounded-2xl border border-neutral-200 dark:border-neutral-700 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">Recent Transactions</h3>
                <a href="{{ route('expenses') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">View
                    All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr
                            class="text-xs uppercase text-neutral-500 border-b border-neutral-100 dark:border-neutral-700">
                            <th class="pb-3 font-semibold">Date</th>
                            <th class="pb-3 font-semibold">Description</th>
                            <th class="pb-3 font-semibold">Category</th>
                            <th class="pb-3 font-semibold text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100 dark:divide-neutral-700">
                        @foreach($recentExpenses as $expense)
                            <tr>
                                <td class="py-4 text-sm text-neutral-600 dark:text-neutral-400 font-medium">
                                    {{ \Carbon\Carbon::parse($expense->date)->format('M d, Y') }}
                                </td>
                                <td class="py-4 text-sm text-neutral-900 dark:text-white">
                                    {{ $expense->description ?? 'No description' }}
                                </td>
                                <td class="py-4">
                                    <span
                                        class="px-2 py-1 text-xs font-medium rounded-lg {{ $expense->type === 'debit' ? 'bg-red-50 text-red-600 dark:bg-red-900/20 dark:text-red-400' : 'bg-green-50 text-green-600 dark:bg-green-900/20 dark:text-green-400' }}">
                                        {{ $expense->category->name }}
                                    </span>
                                </td>
                                <td
                                    class="py-4 text-sm font-bold text-right {{ $expense->type === 'debit' ? 'text-red-600' : 'text-green-600' }}">
                                    {{ $expense->type === 'debit' ? '-' : '+' }}₹{{ number_format($expense->amount, 2) }}
                                </td>
                            </tr>
                        @endforeach
                        @if($recentExpenses->isEmpty())
                            <tr>
                                <td colspan="4" class="py-10 text-center text-neutral-500 italic">No transactions found.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>