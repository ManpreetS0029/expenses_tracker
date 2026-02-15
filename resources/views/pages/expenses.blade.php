@extends('layouts.app')

@section('title', 'Expenses')

@section('content')
    <div class="space-y-5" id="page-root">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-lg font-semibold text-zinc-50">Expenses</h1>
                <p class="text-sm text-zinc-400">Track your daily expenses</p>
            </div>
            <a href="{{ route('expenses') }}?add=1" class="btn-primary inline-flex items-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span class="hidden sm:inline">Add Expense</span>
            </a>
        </div>

        {{-- Summary cards --}}
        <div class="grid grid-cols-3 gap-3" id="summary-cards">
            <div class="stat-card border-l-2 border-l-rose-500">
                <p class="stat-label">Total</p>
                <p class="stat-value text-rose-400 !text-lg tabular-nums">₹{{ number_format($totalAmount, 0) }}</p>
            </div>
            <div class="stat-card border-l-2 border-l-blue-500">
                <p class="stat-label">Needs</p>
                <p class="stat-value text-blue-400 !text-lg tabular-nums">₹{{ number_format($totalNeeds, 0) }}</p>
            </div>
            <div class="stat-card border-l-2 border-l-pink-500">
                <p class="stat-label">Wants</p>
                <p class="stat-value text-pink-400 !text-lg tabular-nums">₹{{ number_format($totalWants, 0) }}</p>
            </div>
        </div>

        <div class="card overflow-hidden" id="data-container">
            <form id="filter-form" class="p-3 sm:p-4 border-b border-zinc-200 dark:border-zinc-800">
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-2">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search..."
                        class="input-field col-span-2 sm:col-span-1" data-auto-filter>
                    <select name="category" class="input-field" data-auto-filter>
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)<option value="{{ $cat->id }}" {{ $categoryFilter == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>@endforeach
                    </select>
                    <select name="classification" class="input-field" data-auto-filter>
                        <option value="">All Types</option>
                        <option value="Needs" {{ $classificationFilter === 'Needs' ? 'selected' : '' }}>Needs</option>
                        <option value="Wants" {{ $classificationFilter === 'Wants' ? 'selected' : '' }}>Wants</option>
                    </select>
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
                @include('pages.partials.expenses-table')
            </div>
        </div>
    </div>

    @if(request('add') || (request('edit') && $editingExpense))
        <div id="expense-modal" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="fixed inset-0 bg-black/40 backdrop-blur-sm"></div>
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="card relative p-5 shadow-xl sm:max-w-lg w-full">
                    <h3 class="text-sm font-semibold text-zinc-50 mb-4">{{ request('edit') ? 'Edit Expense' : 'Add Expense' }}
                    </h3>
                    @if(request('edit') && $editingExpense)
                        <form method="POST" action="{{ route('expenses.update', $editingExpense) }}">
                            @csrf @method('PUT')
                            @include('pages.partials.expense-form-fields', ['expense' => $editingExpense])
                        </form>
                    @else
                        <form method="POST" action="{{ route('expenses.store') }}">
                            @csrf
                            @include('pages.partials.expense-form-fields', ['expense' => null])
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @push('scripts')
        <script>
            (function () {
                var form = document.getElementById('filter-form');
                var tableContent = document.getElementById('table-content');
                var summaryCards = document.getElementById('summary-cards');
                var periodFilter = document.getElementById('period-filter');
                var customDates = document.getElementById('custom-dates');
                var debounceTimer;
                var baseUrl = '{{ route("expenses") }}';

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
                        })
                        .catch(function () { tableContent.style.opacity = '1'; });
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
                                })
                                .catch(function () { tableContent.style.opacity = '1'; });
                        });
                    });
                }

                form.querySelectorAll('[data-auto-filter]').forEach(function (el) {
                    var event = el.tagName === 'INPUT' && el.type === 'text' ? 'input' : 'change';
                    el.addEventListener(event, function () {
                        if (event === 'input') {
                            clearTimeout(debounceTimer);
                            debounceTimer = setTimeout(fetchData, 400);
                        } else {
                            fetchData();
                        }
                    });
                });

                if (periodFilter) {
                    periodFilter.addEventListener('change', function () {
                        if (this.value === 'custom') customDates.classList.remove('hidden');
                        else customDates.classList.add('hidden');
                    });
                }

                form.addEventListener('submit', function (e) { e.preventDefault(); fetchData(); });
                bindPaginationLinks();
            })();
            (function() {
                var modalForm = document.querySelector('#expense-modal form');
                if (modalForm) {
                    modalForm.addEventListener('submit', function(e) {
                        var valid = true;
                        modalForm.querySelectorAll('.js-error').forEach(function(el) { el.remove(); });
                        
                        var date = modalForm.querySelector('[name="date"]');
                        if (date && !date.value.trim()) {
                            showError(date, 'Date is required');
                            valid = false;
                        }

                        var amount = modalForm.querySelector('[name="amount"]');
                        if (amount) {
                            if (!amount.value.trim()) {
                                showError(amount, 'Amount is required');
                                valid = false;
                            } else if (parseFloat(amount.value) <= 0) {
                                showError(amount, 'Amount must be greater than 0');
                                valid = false;
                            }
                        }

                        var category = modalForm.querySelector('[name="category_id"]');
                        if (category && !category.value) {
                            showError(category, 'Category is required');
                            valid = false;
                        }

                        if (!valid) e.preventDefault();
                    });
                }

                function showError(input, message) {
                    var error = document.createElement('span');
                    error.className = 'text-red-500 text-xs mt-1 block js-error';
                    error.innerText = message;
                    input.parentNode.appendChild(error);
                }
            })();
        </script>
    @endpush
@endsection