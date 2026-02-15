@extends('layouts.app')

@section('title', 'Credits')

@section('content')
    <div class="space-y-5" id="page-root">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-lg font-semibold text-zinc-900 dark:text-zinc-50">Credits</h1>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Track your income and credits</p>
            </div>
            <a href="{{ route('credits') }}?add=1" class="btn-primary inline-flex items-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span class="hidden sm:inline">Add Credit</span>
            </a>
        </div>

        <div class="card overflow-hidden" id="data-container">
            <form id="filter-form" class="p-3 sm:p-4 border-b border-zinc-200 dark:border-zinc-800">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-2">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search..." class="input-field"
                        data-auto-filter>
                    <select name="category" class="input-field" data-auto-filter>
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)<option value="{{ $cat->id }}" {{ $categoryFilter == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>@endforeach
                    </select>
                    <select name="period" class="input-field" data-auto-filter id="period-filter">
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
                @include('pages.partials.credits-table')
            </div>
        </div>
    </div>

    @if(request('add') || (request('edit') && $editingCredit))
        <div id="credit-modal" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="fixed inset-0 bg-black/40 backdrop-blur-sm"></div>
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="card relative p-5 shadow-xl sm:max-w-lg w-full">
                    <h3 class="text-sm font-semibold text-zinc-900 dark:text-zinc-50 mb-4">
                        {{ request('edit') ? 'Edit Credit' : 'Add Credit' }}</h3>
                    @if(request('edit') && $editingCredit)
                        <form method="POST" action="{{ route('credits.update', $editingCredit) }}">
                            @csrf @method('PUT')
                            @include('pages.partials.credit-form-fields', ['credit' => $editingCredit])
                        </form>
                    @else
                        <form method="POST" action="{{ route('credits.store') }}">
                            @csrf
                            @include('pages.partials.credit-form-fields', ['credit' => null])
                        </form>
                    @endif
                    <a href="{{ route('credits') }}"
                        class="mt-3 inline-block text-sm text-zinc-500 hover:text-zinc-900 dark:hover:text-zinc-100 transition-colors">Cancel</a>
                </div>
            </div>
        </div>
    @endif

    @push('scripts')
        <script>
            (function () {
                var form = document.getElementById('filter-form');
                var tableContent = document.getElementById('table-content');
                var periodFilter = document.getElementById('period-filter');
                var customDates = document.getElementById('custom-dates');
                var debounceTimer;
                var baseUrl = '{{ route("credits") }}';

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