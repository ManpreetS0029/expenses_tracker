<div class="max-w-7xl mx-auto py-4 sm:py-6 px-2 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-4 sm:mb-6">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Monthly Targets</h1>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Manage your financial goals.</p>
        </div>
        <button wire:click="create()"
            class="inline-flex items-center px-3 sm:px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-zinc-800 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 sm:mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span class="hidden sm:inline">Add Target</span>
        </button>
    </div>

    <div class="bg-white dark:bg-zinc-800/90 overflow-hidden shadow-sm dark:shadow-lg rounded-xl border border-gray-200 dark:border-zinc-700">
        {{-- Filters --}}
        <div class="p-3 sm:p-4 border-b border-gray-200 dark:border-zinc-700">
            <div class="flex gap-2 sm:gap-4">
                <div class="flex-1 max-w-xs">
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search..."
                        class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-zinc-600 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:bg-zinc-900 dark:text-white placeholder-gray-400">
                </div>
                <div class="w-28 sm:w-36">
                    <select wire:model.live="yearFilter"
                        class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-zinc-600 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:bg-zinc-900 dark:text-white">
                        <option value="">All Years</option>
                        @foreach($availableYears as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Mobile Card View --}}
        <div class="sm:hidden divide-y divide-gray-200 dark:divide-zinc-700">
            @forelse($targets as $target)
                <div class="p-3">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $target->month->format('F Y') }}</p>
                            <p class="text-lg font-bold text-indigo-600 dark:text-indigo-400">₹{{ number_format($target->total_income, 0) }}</p>
                        </div>
                        <div class="flex gap-1">
                            <button wire:click="edit({{ $target->id }})" class="p-1.5 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400" title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                </svg>
                            </button>
                            <button onclick="confirmDelete('Are you sure?', () => @this.call('delete', {{ $target->id }}))" class="p-1.5 text-gray-400 hover:text-red-600 dark:hover:text-red-400" title="Delete">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-2">
                            <span class="text-blue-600 dark:text-blue-400 font-medium">Needs</span>
                            <p class="font-semibold text-gray-900 dark:text-white">₹{{ number_format($target->needs, 0) }}</p>
                        </div>
                        <div class="bg-amber-50 dark:bg-amber-900/20 rounded-lg p-2">
                            <span class="text-amber-600 dark:text-amber-400 font-medium">Wants</span>
                            <p class="font-semibold text-gray-900 dark:text-white">₹{{ number_format($target->wants, 0) }}</p>
                        </div>
                        <div class="bg-emerald-50 dark:bg-emerald-900/20 rounded-lg p-2">
                            <span class="text-emerald-600 dark:text-emerald-400 font-medium">Savings</span>
                            <p class="font-semibold text-gray-900 dark:text-white">₹{{ number_format($target->savings, 0) }}</p>
                        </div>
                        <div class="bg-violet-50 dark:bg-violet-900/20 rounded-lg p-2">
                            <span class="text-violet-600 dark:text-violet-400 font-medium">Invest</span>
                            <p class="font-semibold text-gray-900 dark:text-white">₹{{ number_format($target->investments, 0) }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 dark:bg-zinc-800 mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-400">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">No targets found</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Create a monthly target to get started</p>
                </div>
            @endforelse
        </div>

        {{-- Desktop Table View --}}
        <div class="hidden sm:block overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 dark:bg-zinc-700/50 text-xs uppercase text-gray-600 dark:text-gray-300">
                    <tr>
                        <th scope="col" class="px-4 py-3">Month</th>
                        <th scope="col" class="px-4 py-3">Total Income</th>
                        <th scope="col" class="px-4 py-3">Needs</th>
                        <th scope="col" class="px-4 py-3">Wants</th>
                        <th scope="col" class="px-4 py-3">Savings</th>
                        <th scope="col" class="px-4 py-3">Investments</th>
                        <th scope="col" class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                    @forelse($targets as $target)
                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition">
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $target->month->format('F Y') }}</td>
                            <td class="px-4 py-3 font-semibold text-indigo-600 dark:text-indigo-400">₹{{ number_format($target->total_income, 0) }}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400">₹{{ number_format($target->needs, 0) }}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400">₹{{ number_format($target->wants, 0) }}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400">₹{{ number_format($target->savings, 0) }}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400">₹{{ number_format($target->investments, 0) }}</td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end gap-1">
                                    <button wire:click="edit({{ $target->id }})" class="p-1.5 text-gray-500 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 rounded hover:bg-gray-100 dark:hover:bg-zinc-700 transition" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                        </svg>
                                    </button>
                                    <button onclick="confirmDelete('Are you sure you want to delete this target?', () => @this.call('delete', {{ $target->id }}))" class="p-1.5 text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400 rounded hover:bg-gray-100 dark:hover:bg-zinc-700 transition" title="Delete">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 dark:bg-zinc-800 mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-400">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">No targets found</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Create a monthly target to get started</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-4 py-3 border-t border-gray-200 dark:border-zinc-700">
            {{ $targets->links() }}
        </div>
    </div>

    {{-- Modal --}}
    @if($isOpen)
        <div class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-gray-500/75 dark:bg-gray-900/75 transition-opacity" aria-hidden="true" wire:click="closeModal"></div>
            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div class="relative transform overflow-hidden rounded-xl bg-white dark:bg-zinc-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl">
                        <div class="bg-white dark:bg-zinc-800 px-4 pt-5 pb-4 sm:p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                                {{ $targetId ? 'Edit Monthly Target' : 'Add Monthly Target' }}
                            </h3>

                            <div class="space-y-4">
                                <div>
                                    <label for="month_year" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Month & Year</label>
                                    <input type="month" wire:model="month_year" id="month_year" class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-zinc-600 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:bg-zinc-900 dark:text-white">
                                    @error('month_year') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label for="total_income" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Total Expected Income</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">₹</span>
                                        <input type="number" wire:model.live.debounce.500ms="total_income" id="total_income" step="0.01" placeholder="0.00" class="w-full pl-8 pr-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-zinc-600 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:bg-zinc-900 dark:text-white">
                                    </div>
                                    @error('total_income') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <div class="flex justify-between items-center mb-1">
                                            <label for="needs" class="text-sm font-medium text-gray-700 dark:text-gray-300">Needs</label>
                                            <div class="flex items-center gap-1">
                                                <input type="number" wire:model.live="needs_percent" class="w-14 text-xs rounded border border-gray-300 dark:border-zinc-600 dark:bg-zinc-900 dark:text-white p-1 text-right" min="0" max="100">
                                                <span class="text-xs text-gray-500">%</span>
                                            </div>
                                        </div>
                                        <div class="relative">
                                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">₹</span>
                                            <input type="number" wire:model="needs" id="needs" step="0.01" placeholder="0.00" class="w-full pl-8 pr-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-zinc-600 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:bg-zinc-900 dark:text-white">
                                        </div>
                                        @error('needs') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <div class="flex justify-between items-center mb-1">
                                            <label for="wants" class="text-sm font-medium text-gray-700 dark:text-gray-300">Wants</label>
                                            <div class="flex items-center gap-1">
                                                <input type="number" wire:model.live="wants_percent" class="w-14 text-xs rounded border border-gray-300 dark:border-zinc-600 dark:bg-zinc-900 dark:text-white p-1 text-right" min="0" max="100">
                                                <span class="text-xs text-gray-500">%</span>
                                            </div>
                                        </div>
                                        <div class="relative">
                                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">₹</span>
                                            <input type="number" wire:model="wants" id="wants" step="0.01" placeholder="0.00" class="w-full pl-8 pr-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-zinc-600 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:bg-zinc-900 dark:text-white">
                                        </div>
                                        @error('wants') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <div class="flex justify-between items-center mb-1">
                                            <label for="savings" class="text-sm font-medium text-gray-700 dark:text-gray-300">Savings</label>
                                            <div class="flex items-center gap-1">
                                                <input type="number" wire:model.live="savings_percent" class="w-14 text-xs rounded border border-gray-300 dark:border-zinc-600 dark:bg-zinc-900 dark:text-white p-1 text-right" min="0" max="100">
                                                <span class="text-xs text-gray-500">%</span>
                                            </div>
                                        </div>
                                        <div class="relative">
                                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">₹</span>
                                            <input type="number" wire:model="savings" id="savings" step="0.01" placeholder="0.00" class="w-full pl-8 pr-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-zinc-600 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:bg-zinc-900 dark:text-white">
                                        </div>
                                        @error('savings') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <div class="flex justify-between items-center mb-1">
                                            <label for="investments" class="text-sm font-medium text-gray-700 dark:text-gray-300">Investments</label>
                                            <div class="flex items-center gap-1">
                                                <input type="number" wire:model.live="investments_percent" class="w-14 text-xs rounded border border-gray-300 dark:border-zinc-600 dark:bg-zinc-900 dark:text-white p-1 text-right" min="0" max="100">
                                                <span class="text-xs text-gray-500">%</span>
                                            </div>
                                        </div>
                                        <div class="relative">
                                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">₹</span>
                                            <input type="number" wire:model="investments" id="investments" step="0.01" placeholder="0.00" class="w-full pl-8 pr-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-zinc-600 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:bg-zinc-900 dark:text-white">
                                        </div>
                                        @error('investments') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-zinc-700/50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-2">
                            <button type="button" wire:click="save" class="w-full sm:w-auto inline-flex justify-center rounded-lg px-4 py-2 bg-indigo-600 text-sm font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                                Save
                            </button>
                            <button type="button" wire:click="closeModal" class="mt-3 sm:mt-0 w-full sm:w-auto inline-flex justify-center rounded-lg px-4 py-2 bg-white dark:bg-zinc-800 text-sm font-medium text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-zinc-600 hover:bg-gray-50 dark:hover:bg-zinc-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
