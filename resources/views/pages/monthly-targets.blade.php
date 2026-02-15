@extends('layouts.app')

@section('title', 'Monthly Targets')

@section('content')
    <div class="space-y-5">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-lg font-semibold text-zinc-900 dark:text-zinc-50">Monthly Targets</h1>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Set and track financial goals</p>
            </div>
            <a href="{{ route('monthly-targets') }}?add=1" class="btn-primary inline-flex items-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span class="hidden sm:inline">Add Target</span>
            </a>
        </div>

        <div class="card overflow-hidden" id="data-container">
            <form id="filter-form" class="p-3 sm:p-4 border-b border-zinc-200 dark:border-zinc-800">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-w-lg">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search..." class="input-field"
                        data-auto-filter>
                    <select name="year" class="input-field" data-auto-filter>
                        <option value="">All Years</option>
                        @foreach($availableYears as $y)<option value="{{ $y }}" {{ $yearFilter === $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>@endforeach
                    </select>
                </div>
            </form>

            <div id="table-content">
                @include('pages.partials.targets-table')
            </div>
        </div>
    </div>

    @if(request('add') || (request('edit') && $editingTarget))
        <div id="target-modal" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="fixed inset-0 bg-black/40 backdrop-blur-sm"></div>
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="card relative p-5 shadow-xl sm:max-w-lg w-full">
                    <h3 class="text-sm font-semibold text-zinc-900 dark:text-zinc-50 mb-4">
                        {{ request('edit') ? 'Edit Target' : 'Add Target' }}
                    </h3>
                    @if(request('edit') && $editingTarget)
                        <form method="POST" action="{{ route('monthly-targets.update', $editingTarget) }}">
                            @csrf @method('PUT')
                            @include('pages.partials.monthly-target-form-fields', ['target' => $editingTarget])
                        </form>
                    @else
                        <form method="POST" action="{{ route('monthly-targets.store') }}">
                            @csrf
                            @include('pages.partials.monthly-target-form-fields', ['target' => null])
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
                var debounceTimer;
                var baseUrl = '{{ route("monthly-targets") }}';

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

                form.addEventListener('submit', function (e) { e.preventDefault(); fetchData(); });
                bindPaginationLinks();
            })();
            (function() {
                var modalForm = document.querySelector('#target-modal form');
                if (modalForm) {
                    modalForm.addEventListener('submit', function(e) {
                        var valid = true;
                        modalForm.querySelectorAll('.js-error').forEach(function(el) { el.remove(); });
                        
                        var month = modalForm.querySelector('[name="month_year"]');
                        if (month && !month.value.trim()) {
                            showError(month, 'Month is required');
                            valid = false;
                        }

                        ['total_income', 'needs', 'wants', 'savings', 'investments'].forEach(function(name) {
                            var input = modalForm.querySelector('[name="' + name + '"]');
                            if (input) {
                                if (!input.value.trim()) {
                                    showError(input, 'Required');
                                    valid = false;
                                } else if (parseFloat(input.value) < 0) {
                                    showError(input, 'Must be positive');
                                    valid = false;
                                }
                            }
                        });

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