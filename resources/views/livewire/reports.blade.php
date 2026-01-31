<div class="max-w-7xl mx-auto py-4 sm:py-6 px-2 sm:px-6 lg:px-8">
    <div class="flex flex-col gap-4 mb-4 sm:mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Financial Reports</h1>
                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">{{ $periodLabel ?: 'All time' }} overview</p>
            </div>

            <!-- Export Button -->
            <button wire:click="exportToCsv"
                class="inline-flex items-center px-3 sm:px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-1.5 sm:mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Export
            </button>
        </div>

        <!-- Filters -->
        <div class="grid grid-cols-3 gap-2 sm:flex sm:gap-3">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search..."
                class="col-span-3 sm:col-span-1 sm:flex-1 sm:max-w-xs px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base rounded-lg border-2 border-gray-300 dark:border-zinc-600 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 dark:bg-zinc-900/90 dark:text-white placeholder-gray-400 dark:placeholder-gray-500">

            <select wire:model.live="yearFilter"
                class="px-2 sm:px-4 py-2 sm:py-3 text-sm sm:text-base rounded-lg border-2 border-gray-300 dark:border-zinc-600 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 dark:bg-zinc-900/90 dark:text-white">
                @foreach($years as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endforeach
            </select>

            <select wire:model.live="monthFilter"
                class="px-2 sm:px-4 py-2 sm:py-3 text-sm sm:text-base rounded-lg border-2 border-gray-300 dark:border-zinc-600 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 dark:bg-zinc-900/90 dark:text-white">
                @foreach(range(1, 12) as $m)
                    <option value="{{ $m }}">{{ date('M', mktime(0, 0, 0, $m, 1)) }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Money Left Card - Highlighted -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-4 sm:p-6 rounded-xl sm:rounded-2xl shadow-lg text-white mb-4 sm:mb-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
            <div>
                <p class="text-indigo-100 text-xs sm:text-sm font-medium uppercase tracking-wide">Money Left</p>
                <h2 class="text-2xl sm:text-4xl font-bold mt-1">
                    ₹{{ number_format($moneyLeft, 0) }}
                </h2>
                <p class="text-indigo-200 text-xs sm:text-sm mt-1">
                    {{ $moneyLeft >= 0 ? 'Available balance' : 'Over budget' }} for {{ $periodLabel ?: 'selected period' }}
                </p>
            </div>
            <div class="flex items-center gap-2 bg-white/20 rounded-xl px-3 sm:px-4 py-2">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-xs sm:text-sm font-medium">{{ number_format(abs($moneyLeftPercent), 1) }}% {{ $moneyLeft >= 0 ? 'remaining' : 'over' }}</span>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 gap-3 sm:gap-6 mb-4 sm:mb-6">
        <div class="bg-white dark:bg-zinc-800/90 overflow-hidden shadow-sm dark:shadow-lg dark:shadow-black/10 rounded-xl sm:rounded-lg p-4 sm:p-6 border border-transparent dark:border-zinc-600">
            <h3 class="text-sm sm:text-lg font-medium text-gray-900 dark:text-white mb-1 sm:mb-2">Total Income</h3>
            <p class="text-lg sm:text-3xl font-bold text-green-600 dark:text-green-400">₹{{ number_format($totalIncome, 0) }}</p>
            <p class="text-[10px] sm:text-xs text-gray-500 dark:text-gray-400 mt-1">Credits: ₹{{ number_format($totalCredit, 0) }}</p>
        </div>
        <div class="bg-white dark:bg-zinc-800/90 overflow-hidden shadow-sm dark:shadow-lg dark:shadow-black/10 rounded-xl sm:rounded-lg p-4 sm:p-6 border border-transparent dark:border-zinc-600">
            <h3 class="text-sm sm:text-lg font-medium text-gray-900 dark:text-white mb-1 sm:mb-2">Total Expenses</h3>
            <p class="text-lg sm:text-3xl font-bold text-red-600 dark:text-red-400">₹{{ number_format($totalDebit, 0) }}</p>
            <p class="text-[10px] sm:text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $totalIncome > 0 ? number_format(($totalDebit / $totalIncome) * 100, 1) : 0 }}% of income</p>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mb-4 sm:mb-8">
        <!-- Expense Breakdown Chart (Pie) -->
        <div class="bg-white dark:bg-zinc-800/90 overflow-hidden shadow-sm dark:shadow-lg dark:shadow-black/10 rounded-xl sm:rounded-lg p-4 sm:p-6 border border-transparent dark:border-zinc-600">
            <h3 class="text-sm sm:text-lg font-medium text-gray-900 dark:text-white mb-3 sm:mb-4">Expense Breakdown</h3>
            <div class="relative h-48 sm:h-64 w-full" wire:ignore>
                <canvas id="expenseBreakdownChart"></canvas>
            </div>
        </div>

        <!-- Credit vs Debit Chart (Bar) -->
        <div class="bg-white dark:bg-zinc-800/90 overflow-hidden shadow-sm dark:shadow-lg dark:shadow-black/10 rounded-xl sm:rounded-lg p-4 sm:p-6 border border-transparent dark:border-zinc-600">
            <h3 class="text-sm sm:text-lg font-medium text-gray-900 dark:text-white mb-3 sm:mb-4">Income vs Expenses</h3>
            <div class="relative h-48 sm:h-64 w-full" wire:ignore>
                <canvas id="incomeVsExpenseChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Listing -->
    <div class="bg-white dark:bg-zinc-800/90 overflow-hidden shadow-sm dark:shadow-lg dark:shadow-black/10 rounded-xl sm:rounded-lg border border-transparent dark:border-zinc-600">
        <div class="p-3 sm:p-4 border-b border-gray-200 dark:border-zinc-700">
            <h3 class="text-sm sm:text-lg font-medium text-gray-900 dark:text-white">Transactions</h3>
        </div>
        
        <!-- Mobile Card View -->
        <div class="sm:hidden divide-y divide-gray-200 dark:divide-zinc-700">
            @forelse($transactions as $tx)
                <div class="p-3">
                    <div class="flex justify-between items-start mb-1.5">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $tx->description }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $tx->category_name }}
                                @if($tx->type === 'debit' && $tx->classification)
                                    <span class="mx-1">&bull;</span> {{ $tx->classification }}
                                @endif
                            </p>
                        </div>
                        <span class="text-sm font-bold {{ $tx->type === 'credit' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                            {{ $tx->type === 'credit' ? '+' : '-' }}{{ $tx->currency_symbol }}{{ number_format($tx->amount, 0) }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $tx->date->format('M d, Y') }}</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium
                            {{ $tx->type === 'credit' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' }}">
                            {{ ucfirst($tx->type) }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-gray-500 dark:text-gray-400 text-sm">
                    No records found for the selected period.
                </div>
            @endforelse
        </div>
        
        <!-- Desktop Table View -->
        <div class="hidden sm:block overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                <thead class="bg-gray-50 dark:bg-zinc-700/50 text-xs uppercase text-gray-700 dark:text-gray-300">
                    <tr>
                        <th scope="col" class="px-6 py-3">Date</th>
                        <th scope="col" class="px-6 py-3">Description</th>
                        <th scope="col" class="px-6 py-3">Category</th>
                        <th scope="col" class="px-6 py-3">Type</th>
                        <th scope="col" class="px-6 py-3 text-right">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                    @forelse($transactions as $tx)
                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">{{ $tx->date->format('M d, Y') }}</td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900 dark:text-white">{{ $tx->description }}</div>
                                @if($tx->type === 'debit' && $tx->classification)
                                    <span class="text-xs text-gray-500">{{ $tx->classification }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">{{ $tx->category_name }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium {{ $tx->type === 'credit' ? 'bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-900/20 dark:text-green-400 dark:ring-green-500/20' : 'bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/20 dark:bg-red-900/20 dark:text-red-400 dark:ring-red-500/20' }}">
                                    {{ ucfirst($tx->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right font-medium {{ $tx->type === 'credit' ? 'text-green-600 dark:text-green-400' : 'text-gray-900 dark:text-white' }}">
                                {{ $tx->type === 'credit' ? '+' : '-' }}{{ $tx->currency_symbol }}{{ number_format($tx->amount, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                                No records found for the selected period.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-gray-200 dark:border-zinc-700">
            {{ $transactions->links() }}
        </div>
    </div>
    
    @script
        <script>
            Alpine.data('reportsCharts', () => ({
                charts: {},
                
                init() {
                    // Wait for Chart.js to be fully loaded
                    this.waitForChartJs();
                    
                    // Handle window resize for mobile with debounce
                    let resizeTimer;
                    window.addEventListener('resize', () => {
                        clearTimeout(resizeTimer);
                        resizeTimer = setTimeout(() => {
                            this.recreateCharts();
                        }, 300);
                    });
                    
                    // Re-render charts on Livewire updates (pagination, filters, etc.)
                    Livewire.hook('commit', () => {
                        setTimeout(() => {
                            this.recreateCharts();
                        }, 150);
                    });
                },
                
                waitForChartJs() {
                    if (typeof Chart !== 'undefined') {
                        this.$nextTick(() => {
                            setTimeout(() => this.renderCharts(), 100);
                        });
                    } else {
                        setTimeout(() => this.waitForChartJs(), 150);
                    }
                },
                
                getExistingChart(canvasId) {
                    const canvas = document.getElementById(canvasId);
                    if (canvas && typeof Chart !== 'undefined') {
                        return Chart.getChart(canvas);
                    }
                    return null;
                },
                
                destroyChart(canvasId) {
                    const existingChart = this.getExistingChart(canvasId);
                    if (existingChart) {
                        existingChart.destroy();
                    }
                    if (this.charts[canvasId]) {
                        delete this.charts[canvasId];
                    }
                },
                
                destroyAllCharts() {
                    ['expenseBreakdownChart', 'incomeVsExpenseChart'].forEach(id => {
                        this.destroyChart(id);
                    });
                    this.charts = {};
                },
                
                recreateCharts() {
                    this.destroyAllCharts();
                    setTimeout(() => this.renderCharts(), 50);
                },
                
                renderCharts() {
                    if (typeof Chart === 'undefined') {
                        setTimeout(() => this.renderCharts(), 200);
                        return;
                    }
                    
                    // Destroy existing charts first
                    this.destroyAllCharts();
                    
                    const ctxPie = document.getElementById('expenseBreakdownChart');
                    const ctxBar = document.getElementById('incomeVsExpenseChart');

                    if (!ctxPie || !ctxBar) return;
                    
                    const isMobile = window.innerWidth < 640;

                    // Data from Livewire
                    const needs = {{ $needs }};
                    const wants = {{ $wants }};
                    const savings = {{ $savings }};
                    const investments = {{ $investments }};
                    const unclassified = {{ $unclassified }};

                    const totalCredit = {{ $totalCredit }};
                    const totalDebit = {{ $totalDebit }};

                    // Detect dark mode
                    const isDark = document.documentElement.classList.contains('dark');
                    const textColor = isDark ? '#d1d5db' : '#6b7280';
                    const gridColor = isDark ? '#374151' : '#e5e7eb';

                    // Pie Chart
                    if (!this.getExistingChart('expenseBreakdownChart')) {
                        try {
                            this.charts.expenseBreakdownChart = new Chart(ctxPie, {
                                type: 'doughnut',
                                data: {
                                    labels: ['Needs', 'Wants', 'Savings', 'Investments', 'Other'],
                                    datasets: [{
                                        data: [needs, wants, savings, investments, unclassified],
                                        backgroundColor: [
                                            '#6366f1', // Indigo (Needs)
                                            '#f43f5e', // Rose (Wants)
                                            '#10b981', // Emerald (Savings)
                                            '#f59e0b', // Amber (Investments)
                                            '#9ca3af'  // Gray (Other)
                                        ],
                                        borderWidth: 2,
                                        borderColor: isDark ? '#18181b' : '#ffffff',
                                        hoverOffset: 10
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: { 
                                            position: isMobile ? 'bottom' : 'right', 
                                            labels: { 
                                                color: textColor,
                                                padding: isMobile ? 8 : 12,
                                                font: { size: isMobile ? 10 : 12 }
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
                        } catch (e) { console.warn('Pie chart error:', e); }
                    }

                    // Bar Chart
                    if (!this.getExistingChart('incomeVsExpenseChart')) {
                        try {
                            this.charts.incomeVsExpenseChart = new Chart(ctxBar, {
                                type: 'bar',
                                data: {
                                    labels: ['Income', 'Expenses'],
                                    datasets: [{
                                        label: 'Amount',
                                        data: [totalCredit, totalDebit],
                                        backgroundColor: ['#10b981', '#ef4444'],
                                        borderRadius: 6,
                                        borderWidth: 0
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
                                        y: {
                                            beginAtZero: true,
                                            grid: { color: gridColor },
                                            ticks: { 
                                                color: textColor,
                                                font: { size: isMobile ? 10 : 12 },
                                                callback: (value) => '₹' + value.toLocaleString()
                                            }
                                        },
                                        x: {
                                            grid: { display: false },
                                            ticks: { 
                                                color: textColor,
                                                font: { size: isMobile ? 10 : 12 }
                                            }
                                        }
                                    }
                                }
                            });
                        } catch (e) { console.warn('Bar chart error:', e); }
                    }
                }
            }));
        </script>
    @endscript
    
    <div x-data="reportsCharts"></div>
</div>