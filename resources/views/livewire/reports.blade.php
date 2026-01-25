<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Financial Reports</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">visualize your income and expenses.</p>
        </div>

        <div class="flex flex-col md:flex-row gap-4 items-center w-full md:w-auto">
            <!-- Export Button -->
            <button wire:click="exportToCsv"
                class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150 w-full md:w-auto">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Export CSV
            </button>

            <!-- Filters -->
            <div class="flex gap-2 w-full md:w-auto">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search..."
                class="w-full md:w-48 px-4 py-3 text-base rounded-lg border-2 border-gray-300 dark:border-zinc-600 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 dark:bg-zinc-900/90 dark:text-white placeholder-gray-400 dark:placeholder-gray-500">

            <select wire:model.live="yearFilter"
                class="px-4 py-3 text-base rounded-lg border-2 border-gray-300 dark:border-zinc-600 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 dark:bg-zinc-900/90 dark:text-white">
                <option value="">All Years</option>
                @foreach($years as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endforeach
            </select>

            <select wire:model.live="monthFilter"
                class="px-4 py-3 text-base rounded-lg border-2 border-gray-300 dark:border-zinc-600 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 dark:bg-zinc-900/90 dark:text-white">
                <option value="">All Months</option>
                @foreach(range(1, 12) as $m)
                    <option value="{{ $m }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                @endforeach
            </select>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white dark:bg-zinc-800/90 overflow-hidden shadow-sm dark:shadow-lg dark:shadow-black/10 sm:rounded-lg p-6 border border-transparent dark:border-zinc-600">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Total Credit</h3>
            <p class="text-3xl font-bold text-green-600 dark:text-green-400">₹{{ number_format($totalCredit, 2) }}</p>
        </div>
        <div class="bg-white dark:bg-zinc-800/90 overflow-hidden shadow-sm dark:shadow-lg dark:shadow-black/10 sm:rounded-lg p-6 border border-transparent dark:border-zinc-600">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Total Debit</h3>
            <p class="text-3xl font-bold text-red-600 dark:text-red-400">₹{{ number_format($totalDebit, 2) }}</p>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Expense Breakdown Chart (Pie) -->
        <div class="bg-white dark:bg-zinc-800/90 overflow-hidden shadow-sm dark:shadow-lg dark:shadow-black/10 sm:rounded-lg p-6 border border-transparent dark:border-zinc-600">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Debit Breakdown</h3>
            <div class="relative h-64 w-full" wire:ignore>
                <canvas id="expenseBreakdownChart"></canvas>
            </div>
        </div>

        <!-- Credit vs Debit Chart (Bar) -->
        <div class="bg-white dark:bg-zinc-800/90 overflow-hidden shadow-sm dark:shadow-lg dark:shadow-black/10 sm:rounded-lg p-6 border border-transparent dark:border-zinc-600">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Credit vs Debit</h3>
            <div class="relative h-64 w-full" wire:ignore>
                <canvas id="incomeVsExpenseChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Listing -->
    <div class="bg-white dark:bg-zinc-800/90 overflow-hidden shadow-sm dark:shadow-lg dark:shadow-black/10 sm:rounded-lg border border-transparent dark:border-zinc-600">
        <div class="p-4 border-b border-gray-200 dark:border-zinc-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Transactions</h3>
        </div>
        <div class="overflow-x-auto">
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
        <div class="px-6 py-4 border-t border-gray-200 dark:border-zinc-700">
            {{ $transactions->links() }}
        </div>
    </div>
    
    @script
        <script>
            Alpine.data('reportsCharts', () => ({
                charts: {},
                
                init() {
                    // Wait for Chart.js to be fully loaded
                    if (typeof Chart === 'undefined') {
                        const checkChart = setInterval(() => {
                            if (typeof Chart !== 'undefined') {
                                clearInterval(checkChart);
                                this.renderCharts();
                            }
                        }, 100);
                    } else {
                        this.renderCharts();
                    }
                    
                    // Handle window resize for mobile
                    let resizeTimer;
                    window.addEventListener('resize', () => {
                        clearTimeout(resizeTimer);
                        resizeTimer = setTimeout(() => {
                            this.destroyCharts();
                            this.renderCharts();
                        }, 250);
                    });
                    
                    // Re-render charts on Livewire updates (pagination, filters, etc.)
                    Livewire.hook('commit', () => {
                        this.$nextTick(() => {
                            this.destroyCharts();
                            this.renderCharts();
                        });
                    });
                },
                
                destroyCharts() {
                    Object.values(this.charts).forEach(chart => chart?.destroy());
                    this.charts = {};
                },
                
                renderCharts() {
                    if (typeof Chart === 'undefined') {
                        setTimeout(() => this.renderCharts(), 200);
                        return;
                    }
                    
                    const ctxPie = document.getElementById('expenseBreakdownChart');
                    const ctxBar = document.getElementById('incomeVsExpenseChart');

                    if (!ctxPie || !ctxBar) return;
                    
                    // Ensure canvas containers have proper dimensions
                    const pieContainer = ctxPie.parentElement;
                    const barContainer = ctxBar.parentElement;
                    if (pieContainer) {
                        pieContainer.style.width = '100%';
                        pieContainer.style.height = window.innerWidth < 768 ? '300px' : '400px';
                    }
                    if (barContainer) {
                        barContainer.style.width = '100%';
                        barContainer.style.height = window.innerWidth < 768 ? '300px' : '400px';
                    }

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
                    this.charts.pie = new Chart(ctxPie, {
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
                            layout: {
                                padding: window.innerWidth < 768 ? 5 : 10
                            },
                            plugins: {
                                legend: { 
                                    position: window.innerWidth < 768 ? 'bottom' : 'right', 
                                    labels: { 
                                        color: textColor,
                                        padding: window.innerWidth < 768 ? 8 : 12,
                                        font: { size: window.innerWidth < 768 ? 10 : 12 }
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

                    // Bar Chart
                    this.charts.bar = new Chart(ctxBar, {
                        type: 'bar',
                        data: {
                            labels: ['Credit', 'Debit'],
                            datasets: [{
                                label: 'Amount',
                                data: [totalCredit, totalDebit],
                                backgroundColor: [
                                    '#10b981', // Green
                                    '#ef4444'  // Red
                                ],
                                borderRadius: 8,
                                borderWidth: 2,
                                borderColor: isDark ? '#18181b' : '#ffffff'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            layout: {
                                padding: window.innerWidth < 768 ? 5 : 10
                            },
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
                                        font: { size: window.innerWidth < 768 ? 10 : 12 },
                                        callback: (value) => '₹' + value.toLocaleString()
                                    }
                                },
                                x: {
                                    grid: { display: false },
                                    ticks: { 
                                        color: textColor,
                                        font: { size: window.innerWidth < 768 ? 10 : 12 }
                                    }
                                }
                            }
                        }
                    });
                }
            }));
        </script>
    @endscript
    
    <div x-data="reportsCharts"></div>
</div>