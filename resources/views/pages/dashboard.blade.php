@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="min-w-0 space-y-5" id="dashboard-charts-root">
        <script type="application/json" id="dashboard-chart-data">@json($dashboardChartData)</script>

        <div class="flex min-w-0 flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="min-w-0">
                <h1 class="text-lg font-semibold text-zinc-900 dark:text-zinc-50 sm:text-xl">Dashboard</h1>
                <p class="mt-0.5 text-sm text-zinc-500 dark:text-zinc-400">{{ $currentMonth }}</p>
            </div>
            <form method="GET" action="{{ route('dashboard') }}" class="w-full sm:w-auto">
                <select name="period" onchange="this.form.submit()" class="input-field w-full sm:w-auto">
                    @foreach($periodOptions as $value => $label)
                        <option value="{{ $value }}" {{ $periodFilter === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        @include('pages.partials.dashboard-cards')
        @include('pages.partials.dashboard-charts')
        @include('pages.partials.dashboard-recent')
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var el = document.getElementById('dashboard-chart-data');
                if (!el || !el.textContent) return;
                var data;
                try { data = JSON.parse(el.textContent.trim()); } catch (e) { return; }
                if (typeof Chart === 'undefined') return;

                var isDark = document.documentElement.classList.contains('dark');
                var textColor = isDark ? '#a1a1aa' : '#71717a';
                var gridColor = isDark ? '#27272a' : '#f4f4f5';
                var isMobile = window.innerWidth < 640;
                Chart.defaults.color = textColor;
                Chart.defaults.font.family = "'Inter', sans-serif";
                Chart.defaults.font.size = isMobile ? 10 : 11;
                Chart.defaults.responsive = true;
                Chart.defaults.maintainAspectRatio = false;

                var trendCtx = document.getElementById('trendChart');
                if (trendCtx) {
                    new Chart(trendCtx, {
                        type: 'line',
                        data: {
                            labels: data.trendLabels || [],
                            datasets: [
                                { label: 'Income', data: data.trendIncome || [], borderColor: '#10b981', backgroundColor: 'rgba(16, 185, 129, 0.08)', fill: true, tension: 0.4, borderWidth: 2, pointRadius: 3, pointBackgroundColor: '#10b981' },
                                { label: 'Expenses', data: data.trendExpenses || [], borderColor: '#f43f5e', backgroundColor: 'rgba(244, 63, 94, 0.08)', fill: true, tension: 0.4, borderWidth: 2, pointRadius: 3, pointBackgroundColor: '#f43f5e' }
                            ]
                        },
                        options: {
                            responsive: true, maintainAspectRatio: false,
                            plugins: { legend: { position: 'top', labels: { usePointStyle: true, pointStyle: 'circle', padding: 16, font: { size: 11 } } }, tooltip: { backgroundColor: isDark ? '#27272a' : '#18181b', titleColor: '#fafafa', bodyColor: '#d4d4d8', borderColor: isDark ? '#3f3f46' : 'transparent', borderWidth: 1, padding: 10, cornerRadius: 8, callbacks: { label: function (c) { return c.dataset.label + ': ₹' + c.parsed.y.toLocaleString(); } } } },
                            scales: { x: { grid: { display: false }, border: { display: false } }, y: { beginAtZero: true, grid: { color: gridColor }, border: { display: false }, ticks: { callback: function (v) { return '₹' + v.toLocaleString(); } } } }
                        }
                    });
                }

                var classCtx = document.getElementById('classificationChart');
                if (classCtx) {
                    new Chart(classCtx, {
                        type: 'doughnut',
                        data: {
                            labels: ['Needs', 'Wants', 'Savings', 'Investments'],
                            datasets: [{ data: data.classifications || [0, 0, 0, 0], backgroundColor: ['#6366f1', '#ec4899', '#10b981', '#f59e0b'], hoverBackgroundColor: ['#818cf8', '#f472b6', '#34d399', '#fbbf24'], borderWidth: 0, borderRadius: 4 }]
                        },
                        options: { responsive: true, maintainAspectRatio: false, cutout: isMobile ? '65%' : '72%', plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, pointStyle: 'circle', padding: 12, font: { size: 11 } } }, tooltip: { backgroundColor: isDark ? '#27272a' : '#18181b', padding: 10, cornerRadius: 8, callbacks: { label: function (c) { return c.label + ': ₹' + c.parsed.toLocaleString(); } } } } }
                    });
                }

                var dailyCtx = document.getElementById('dailyChart');
                if (dailyCtx) {
                    new Chart(dailyCtx, {
                        type: 'bar',
                        data: { labels: data.dailyLabels || [], datasets: [{ label: 'Daily Spending', data: data.dailyData || [], backgroundColor: 'rgba(139, 92, 246, 0.6)', hoverBackgroundColor: '#8b5cf6', borderRadius: 4, barPercentage: 0.6 }] },
                        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false }, tooltip: { backgroundColor: isDark ? '#27272a' : '#18181b', padding: 10, cornerRadius: 8, callbacks: { label: function (c) { return 'Spent: ₹' + c.parsed.y.toLocaleString(); } } } }, scales: { x: { grid: { display: false }, border: { display: false } }, y: { beginAtZero: true, grid: { color: gridColor }, border: { display: false }, ticks: { callback: function (v) { return '₹' + v.toLocaleString(); } } } } }
                    });
                }

                var weekdayCtx = document.getElementById('weekdayChart');
                if (weekdayCtx) {
                    new Chart(weekdayCtx, {
                        type: 'bar',
                        data: { labels: data.weekdayLabels || ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'], datasets: [{ label: 'Spending', data: data.weekdayData || [0, 0, 0, 0, 0, 0, 0], backgroundColor: ['#f43f5e', '#f59e0b', '#10b981', '#06b6d4', '#6366f1', '#ec4899', '#8b5cf6'], borderRadius: 4, barPercentage: 0.5 }] },
                        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false }, tooltip: { backgroundColor: isDark ? '#27272a' : '#18181b', padding: 10, cornerRadius: 8, callbacks: { label: function (c) { return '₹' + c.parsed.y.toLocaleString(); } } } }, scales: { x: { grid: { display: false }, border: { display: false } }, y: { beginAtZero: true, grid: { color: gridColor }, border: { display: false }, ticks: { callback: function (v) { return '₹' + v.toLocaleString(); } } } } }
                    });
                }
            });
        </script>
    @endpush
@endsection