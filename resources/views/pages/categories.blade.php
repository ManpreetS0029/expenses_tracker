@extends('layouts.app')

@section('title', 'Categories')

@section('content')
    <div class="space-y-5">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-lg font-semibold text-zinc-900 dark:text-zinc-50">Categories</h1>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Manage expense and income categories</p>
            </div>
            <button type="button"
                onclick="document.getElementById('category-modal').classList.remove('hidden'); document.getElementById('category-form').reset(); document.querySelectorAll('.js-error').forEach(e => e.remove()); document.getElementById('method-field').innerHTML=''; document.getElementById('category-form').action='{{ route('categories.store') }}'; document.getElementById('modal-title').textContent='Add Category';"
                class="btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 sm:mr-1.5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span class="hidden sm:inline">Add Category</span>
            </button>
        </div>

        <div class="card overflow-hidden" id="data-container">
            <form id="filter-form" class="p-3 sm:p-4 border-b border-zinc-200 dark:border-zinc-800">
                <input type="text" name="search" value="{{ old('search', $search) }}" placeholder="Search categories..."
                    class="input-field max-w-sm" data-auto-filter>
            </form>

            <div id="table-content">
                @include('pages.partials.categories-table')
            </div>
        </div>

        {{-- Modal --}}
        <div id="category-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-modal="true">
            <div class="fixed inset-0 bg-black/40 backdrop-blur-sm"
                onclick="document.getElementById('category-modal').classList.add('hidden')"></div>
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="card relative p-5 shadow-xl sm:max-w-md w-full" onclick="event.stopPropagation()">
                    <h3 id="modal-title" class="text-sm font-semibold text-zinc-900 dark:text-zinc-50 mb-4">Add Category
                    </h3>
                    <form id="category-form" method="POST" action="{{ route('categories.store') }}">
                        @csrf
                        <div id="method-field"></div>
                        <div class="mb-4">
                            <label for="category-name"
                                class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Name</label>
                            <input type="text" name="name" id="category-name" placeholder="e.g. Groceries"
                                value="{{ old('name') }}" class="input-field">
                            @error('name')<span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
                        </div>
                        <div class="flex gap-2 justify-end">
                            <button type="button"
                                onclick="document.getElementById('category-modal').classList.add('hidden')"
                                class="btn-secondary">Cancel</button>
                            <button type="submit" class="btn-success">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function openEditCategory(id, name) {
                document.getElementById('category-modal').classList.remove('hidden');
                document.querySelectorAll('.js-error').forEach(e => e.remove());
                document.getElementById('modal-title').textContent = 'Edit Category';
                document.getElementById('method-field').innerHTML = '<input type="hidden" name="_method" value="PUT">';
                document.getElementById('category-form').action = '{{ url('categories') }}/' + id;
                document.getElementById('category-name').value = name;
            }

            (function () {
                var form = document.getElementById('filter-form');
                var tableContent = document.getElementById('table-content');
                var debounceTimer;
                var baseUrl = '{{ route("categories") }}';

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
                    el.addEventListener('input', function () {
                        clearTimeout(debounceTimer);
                        debounceTimer = setTimeout(fetchData, 400);
                    });
                });

                form.addEventListener('submit', function (e) { e.preventDefault(); fetchData(); });
                bindPaginationLinks();
            })();
            (function () {
                var modalForm = document.querySelector('#category-form');
                if (modalForm) {
                    modalForm.addEventListener('submit', function (e) {
                        var valid = true;
                        modalForm.querySelectorAll('.js-error').forEach(function (el) { el.remove(); });

                        var name = modalForm.querySelector('[name="name"]');
                        if (name && !name.value.trim()) {
                            showError(name, 'Category name is required');
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