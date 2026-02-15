@extends('layouts.app')

@section('title', 'Reports')

@section('content')
    <div class="space-y-5">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-lg font-semibold text-zinc-900 dark:text-zinc-50">Reports</h1>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ $periodLabel }}</p>
            </div>
            <a href="{{ route('reports.export', request()->query()) }}"
                class="btn-success inline-flex items-center gap-1.5 text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                Export
            </a>
        </div>

        {{-- Summary cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-3 gap-3" id="summary-cards">
            <div class="stat-card border-l-2 border-l-violet-500">
                <p class="stat-label">Money Left</p>
                <p class="stat-value {{ $moneyLeft >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                    ₹{{ number_format(abs($moneyLeft), 0) }}</p>
                <p class="stat-sub">{{ number_format($moneyLeftPercent, 1) }}% of income</p>
            </div>
            <div class="stat-card border-l-2 border-l-emerald-500">
                <p class="stat-label">Total Income</p>
                <p class="stat-value text-emerald-400">₹{{ number_format($totalIncome, 0) }}</p>
            </div>
            <div class="stat-card border-l-2 border-l-rose-500">
                <p class="stat-label">Total Expenses</p>
                <p class="stat-value text-rose-400">₹{{ number_format($totalDebit, 0) }}</p>
            </div>
            <div class="stat-card border-l-2 border-l-cyan-500">
                <p class="stat-label">Credits</p>
                <p class="stat-value text-cyan-400">₹{{ number_format($totalCredit, 0) }}</p>
            </div>
            <div class="stat-card border-l-2 border-l-blue-500">
                <p class="stat-label">Needs</p>
                <p class="stat-value text-blue-400">₹{{ number_format($needs, 0) }}</p>
            </div>
            <div class="stat-card border-l-2 border-l-pink-500">
                <p class="stat-label">Wants</p>
                <p class="stat-value text-pink-400">₹{{ number_format($wants, 0) }}</p>
            </div>
        </div>

        <div class="card overflow-hidden" id="data-container">
            <form id="filter-form" class="p-3 sm:p-4 border-b border-zinc-200 dark:border-zinc-800">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search..." class="input-field"
                        data-auto-filter>
                    <select name="period" id="period-filter" class="input-field" data-auto-filter>
                        @foreach($periodOptions as $value => $label)<option value="{{ $value }}" {{ $periodFilter === $value ? 'selected' : '' }}>{{ $label }}</option>@endforeach
                    </select>
                    <div id="custom-dates"
                        class="{{ $periodFilter === 'custom' ? '' : 'hidden' }} col-span-2 grid grid-cols-2 gap-2">
                        <input type="date" name="date_from" value="{{ $dateFrom }}" class="input-field" data-auto-filter>
                        <input type="date" name="date_to" value="{{ $dateTo }}" class="input-field" data-auto-filter>
                    </div>
                </div>
            </form>

            <div id="table-content">
                @include('pages.partials.reports-table')
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            (function () {
                var form = document.getElementById('filter-form');
                var tableContent = document.getElementById('table-content');
                var periodFilter = document.getElementById('period-filter');
                var customDates = document.getElementById('custom-dates');
                var summaryCards = document.getElementById('summary-cards');
                var debounceTimer;
                var baseUrl = '{{ route("reports") }}';

                function fetchData() {
                    var params = new URLSearchParams(new FormData(form));
                    var cleanParams = new URLSearchParams();
                    params.forEach(function (v, k) { if (v) cleanParams.set(k, v); });
                    var url = baseUrl + (cleanParams.toString() ? '?' + cleanParams.toString() : '');
                    window.history.replaceState({}, '', url);
                    tableContent.style.opacity = '0.5';
                    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                        .then(function (r) { return r.text(); })
                        .then(function (html) {
                            var doc = new DOMParser().parseFromString(html, 'text/html');
                            var newTable = doc.getElementById('table-content');
                            if (newTable) tableContent.innerHTML = newTable.innerHTML;
                            var newSummary = doc.getElementById('summary-cards');
                            if (newSummary && summaryCards) summaryCards.innerHTML = newSummary.innerHTML;
                            tableContent.style.opacity = '1';
                            bindPaginationLinks();
                        }).catch(function () { tableContent.style.opacity = '1'; });
                }

                function bindPaginationLinks() {
                    tableContent.querySelectorAll('.pagination a').forEach(function (link) {
                        link.addEventListener('click', function (e) {
                            e.preventDefault();
                            var pageUrl = this.href;
                            tableContent.style.opacity = '0.5';
                            fetch(pageUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                                .then(function (r) { return r.text(); })
                                .then(function (html) {
                                    var doc = new DOMParser().parseFromString(html, 'text/html');
                                    var newTable = doc.getElementById('table-content');
                                    if (newTable) tableContent.innerHTML = newTable.innerHTML;
                                    tableContent.style.opacity = '1';
                                    window.history.replaceState({}, '', pageUrl);
                                    bindPaginationLinks();
                                }).catch(function () { tableContent.style.opacity = '1'; });
                        });
                    });
                }

                form.querySelectorAll('[data-auto-filter]').forEach(function (el) {
                    var event = el.tagName === 'INPUT' && el.type === 'text' ? 'input' : 'change';
                    el.addEventListener(event, function () {
                        if (event === 'input') { clearTimeout(debounceTimer); debounceTimer = setTimeout(fetchData, 400); }
                        else fetchData();
                    });
                });

                if (periodFilter) periodFilter.addEventListener('change', function () {
                    customDates.classList.toggle('hidden', this.value !== 'custom');
                });

                form.addEventListener('submit', function (e) { e.preventDefault(); fetchData(); });
                bindPaginationLinks();
            })();
        </script>
    @endpush
@endsection