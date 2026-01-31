<div class="max-w-7xl mx-auto py-4 sm:py-6 px-2 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-4 sm:mb-6">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Expenses</h1>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Track your daily expenses.</p>
        </div>
        <button wire:click="openModal()"
            class="inline-flex items-center px-3 sm:px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-zinc-800 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 sm:mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span class="hidden sm:inline">Add Expense</span>
        </button>
    </div>

    <div class="bg-white dark:bg-zinc-800/90 overflow-hidden shadow-sm dark:shadow-lg rounded-xl border border-gray-200 dark:border-zinc-700">
        {{-- Filters --}}
        <div class="p-3 sm:p-4 border-b border-gray-200 dark:border-zinc-700">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-2 sm:gap-4">
                <div>
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search description..."
                        class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-zinc-600 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:bg-zinc-900 dark:text-white placeholder-gray-400">
                </div>
                <div>
                    <select wire:model.live="categoryFilter"
                        class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-zinc-600 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:bg-zinc-900 dark:text-white">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <select wire:model.live="classificationFilter"
                        class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-zinc-600 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:bg-zinc-900 dark:text-white">
                        <option value="">All (Needs / Wants)</option>
                        <option value="Needs">Needs</option>
                        <option value="Wants">Wants</option>
                    </select>
                </div>
                <div>
                    <select wire:model.live="periodFilter"
                        class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-zinc-600 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:bg-zinc-900 dark:text-white">
                        @foreach($periodOptions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                @if($periodFilter === 'custom')
                    <div>
                        <input wire:model.live="dateFrom" type="date" title="Date from"
                            class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-zinc-600 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:bg-zinc-900 dark:text-white">
                    </div>
                    <div>
                        <input wire:model.live="dateTo" type="date" title="Date to"
                            class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-zinc-600 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:bg-zinc-900 dark:text-white">
                    </div>
                @endif
            </div>
        </div>

        {{-- Mobile Card View --}}
        <div class="sm:hidden divide-y divide-gray-200 dark:divide-zinc-700">
            @forelse ($expenses as $expense)
                <div class="p-3">
                    <div class="flex justify-between items-start">
                        <div class="flex items-start gap-3 flex-1 min-w-0">
                            <div class="p-2 rounded-full bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6 9 12.75l4.286-4.286a11.948 11.948 0 0 1 4.306 6.43l.776 2.898m0 0 3.182-5.511m-3.182 5.51-5.511-3.181" />
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="font-medium text-sm text-gray-900 dark:text-white truncate">{{ $expense->description ?: 'No description' }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $expense->category->name }} &bull; {{ $expense->date->format('M d') }}</p>
                                @if($expense->classification)
                                    <span class="inline-flex mt-1 px-1.5 py-0.5 rounded text-[10px] font-medium bg-gray-100 text-gray-600 dark:bg-zinc-700 dark:text-gray-300">{{ $expense->classification }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="text-right flex flex-col items-end gap-1">
                            <span class="font-bold text-sm text-red-600 dark:text-red-400">-{{ $expense->currency_symbol ?? '₹' }}{{ number_format($expense->amount, 0) }}</span>
                            <div class="flex gap-1">
                                <button wire:click="openModal({{ $expense->id }})" class="p-1 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                    </svg>
                                </button>
                                <button onclick="confirmDelete('Are you sure you want to delete this expense?', () => @this.call('delete', {{ $expense->id }}))" class="p-1 text-gray-400 hover:text-red-600 dark:hover:text-red-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 dark:bg-zinc-800 mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-400">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">No expenses found</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Start tracking by adding an expense</p>
                </div>
            @endforelse
        </div>

        {{-- Desktop Table View --}}
        <div class="hidden sm:block overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 dark:bg-zinc-700/50 text-xs uppercase text-gray-600 dark:text-gray-300">
                    <tr>
                        <th scope="col" class="px-4 py-3">Date</th>
                        <th scope="col" class="px-4 py-3">Description</th>
                        <th scope="col" class="px-4 py-3">Category</th>
                        <th scope="col" class="px-4 py-3">Classification</th>
                        <th scope="col" class="px-4 py-3 text-right">Amount</th>
                        <th scope="col" class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                    @forelse($expenses as $expense)
                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition">
                            <td class="px-4 py-3 whitespace-nowrap text-gray-600 dark:text-gray-400">{{ $expense->date->format('M d, Y') }}</td>
                            <td class="px-4 py-3">
                                <span class="font-medium text-gray-900 dark:text-white">{{ $expense->description ?: 'No description' }}</span>
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $expense->category->name }}</td>
                            <td class="px-4 py-3">
                                @if($expense->classification)
                                    <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700 dark:bg-zinc-700 dark:text-gray-300">{{ $expense->classification }}</span>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right font-semibold text-red-600 dark:text-red-400">
                                -{{ $expense->currency_symbol ?? '₹' }}{{ number_format($expense->amount, 2) }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end gap-1">
                                    <button wire:click="openModal({{ $expense->id }})" class="p-1.5 text-gray-500 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 rounded hover:bg-gray-100 dark:hover:bg-zinc-700 transition" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                        </svg>
                                    </button>
                                    <button onclick="confirmDelete('Are you sure you want to delete this expense?', () => @this.call('delete', {{ $expense->id }}))" class="p-1.5 text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400 rounded hover:bg-gray-100 dark:hover:bg-zinc-700 transition" title="Delete">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 dark:bg-zinc-800 mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-400">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">No expenses found</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Start tracking by adding an expense</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-4 py-3 border-t border-gray-200 dark:border-zinc-700">
            {{ $expenses->links() }}
        </div>
    </div>

    {{-- Modal --}}
    @if($isOpen)
        <div class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-gray-500/75 dark:bg-gray-900/75 transition-opacity" aria-hidden="true" wire:click="closeModal"></div>
            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div class="relative transform overflow-hidden rounded-xl bg-white dark:bg-zinc-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                        <div class="bg-white dark:bg-zinc-800 px-4 pt-5 pb-4 sm:p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                                {{ $expenseId ? 'Edit Expense' : 'Add Expense' }}
                            </h3>
                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date</label>
                                        <input type="date" wire:model="date" id="date" class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-zinc-600 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:bg-zinc-900 dark:text-white">
                                        @error('date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Amount</label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">{{ $currency_symbol }}</span>
                                            <input type="number" wire:model="amount" id="amount" step="0.01" inputmode="decimal" placeholder="0.00" class="w-full pl-8 pr-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-zinc-600 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:bg-zinc-900 dark:text-white">
                                        </div>
                                        @error('amount') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                                    <input type="text" wire:model="description" id="description" placeholder="e.g. Lunch with friends" class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-zinc-600 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:bg-zinc-900 dark:text-white">
                                    @error('description') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Category</label>
                                        <select wire:model.live="category_id" id="category_id" class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-zinc-600 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:bg-zinc-900 dark:text-white">
                                            <option value="">Select</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('category_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label for="currency" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Currency</label>
                                        <select wire:model.live="currency" id="currency" class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-zinc-600 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:bg-zinc-900 dark:text-white">
                                            @foreach($availableCurrencies as $code => $details)
                                                <option value="{{ $code }}">{{ $details['symbol'] }} {{ $code }}</option>
                                            @endforeach
                                        </select>
                                        @error('currency') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Classification</label>
                                    <div class="grid grid-cols-2 gap-2">
                                        @foreach(['Needs', 'Wants'] as $classOption)
                                            <label class="cursor-pointer">
                                                <input type="radio" value="{{ $classOption }}" wire:model.live="classification" class="sr-only peer">
                                                <div class="px-2 sm:px-3 py-2 text-xs font-medium text-center rounded-lg border transition
                                                    peer-checked:bg-indigo-50 peer-checked:border-indigo-500 peer-checked:text-indigo-700
                                                    dark:peer-checked:bg-indigo-900/30 dark:peer-checked:border-indigo-600 dark:peer-checked:text-indigo-400
                                                    border-gray-300 dark:border-zinc-600 hover:bg-gray-50 dark:hover:bg-zinc-700 text-gray-700 dark:text-gray-300">
                                                    {{ $classOption }}
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                    @error('classification') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
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
