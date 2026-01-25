<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Financial Reports</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">visualize your income and expenses.</p>
        </div>

        <!-- Filters -->
        <div class="flex gap-2">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search..."
                class="w-full md:w-48 rounded-md border-gray-300 dark:border-zinc-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-zinc-900 dark:text-white">

            <select wire:model.live="yearFilter"
                class="rounded-md border-gray-300 dark:border-zinc-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-zinc-900 dark:text-white">
                <option value="">All Years</option>
                @foreach($years as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endforeach
            </select>

            <select wire:model.live="monthFilter"
                class="rounded-md border-gray-300 dark:border-zinc-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-zinc-900 dark:text-white">
                <option value="">All Months</option>
                @foreach(range(1, 12) as $m)
                    <option value="{{ $m }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white dark:bg-zinc-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Total Credit</h3>
            <p class="text-3xl font-bold text-green-600 dark:text-green-400">₹{{ number_format($totalCredit, 2) }}</p>
        </div>
        <div class="bg-white dark:bg-zinc-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Total Debit</h3>
            <p class="text-3xl font-bold text-red-600 dark:text-red-400">₹{{ number_format($totalDebit, 2) }}</p>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Expense Breakdown Chart (Pie) -->
        <div class="bg-white dark:bg-zinc-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Debit Breakdown</h3>
            <div class="relative h-64 w-full">
                <canvas id="expenseBreakdownChart"></canvas>
            </div>
        </div>

        <!-- Credit vs Debit Chart (Bar) -->
        <div class="bg-white dark:bg-zinc-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Credit vs Debit</h3>
            <div class="relative h-64 w-full">
                <canvas id="incomeVsExpenseChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Listing -->
    <div class="bg-white dark:bg-zinc-800 overflow-hidden shadow-sm sm:rounded-lg">
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
                    @forelse($expenses as $expense)
                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">{{ $expense->date->format('M d, Y') }}</td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900 dark:text-white">{{ $expense->description ?: 'No description' }}</div>
                                @if($expense->type === 'debit' && $expense->classification)
                                    <span class="text-xs text-gray-500">{{ $expense->classification }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">{{ $expense->category->name }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium {{ $expense->type === 'credit' ? 'bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-900/20 dark:text-green-400 dark:ring-green-500/20' : 'bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/20 dark:bg-red-900/20 dark:text-red-400 dark:ring-red-500/20' }}">
                                    {{ ucfirst($expense->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right font-medium {{ $expense->type === 'credit' ? 'text-green-600 dark:text-green-400' : 'text-gray-900 dark:text-white' }}">
                                {{ $expense->type === 'credit' ? '+' : '-' }}₹{{ number_format($expense->amount, 2) }}
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
            {{ $expenses->links() }}
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('livewire:navigated', () => {
            renderCharts();
        });

        // Initial render
        renderCharts();

        function renderCharts() {
            const ctxPie = document.getElementById('expenseBreakdownChart');
            const ctxBar = document.getElementById('incomeVsExpenseChart');

            if (!ctxPie || !ctxBar) return;

            // Destroy existing charts if any (to prevent canvas reuse issues)
            if (window.myPieChart) window.myPieChart.destroy();
            if (window.myBarChart) window.myBarChart.destroy();

            // Data from Livewire
            const needs = {{ $needs }};
            const wants = {{ $wants }};
            const savings = {{ $savings }};
            const investments = {{ $investments }};
            const unclassified = {{ $unclassified }};

            const totalCredit = {{ $totalCredit }};
            const totalDebit = {{ $totalDebit }};

            // Pie Chart
            window.myPieChart = new Chart(ctxPie, {
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
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'right', labels: { color: '#9ca3af' } }
                    }
                }
            });

            // Bar Chart
            window.myBarChart = new Chart(ctxBar, {
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
                        borderRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return '₹' + context.raw;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#374151' },
                            ticks: { color: '#9ca3af' }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: '#9ca3af' }
                        }
                    }
                }
            });
        }
    </script>
</div>