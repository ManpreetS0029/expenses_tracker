<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Expenses</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Track your daily expenses and income.</p>
        </div>
        <button wire:click="openModal()"
            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
            Add Expense
        </button>
    </div>

    <!-- Filters -->
    <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search expenses..."
                class="w-full px-4 py-3 text-base rounded-lg border-2 border-gray-300 dark:border-zinc-600 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 dark:bg-zinc-900/90 dark:text-white placeholder-gray-400 dark:placeholder-gray-500">
        </div>
        <div>
            <select wire:model.live="typeFilter"
                class="w-full px-4 py-3 text-base rounded-lg border-2 border-gray-300 dark:border-zinc-600 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 dark:bg-zinc-900/90 dark:text-white">
                <option value="">All Types</option>
                <option value="credit">Credit</option>
                <option value="debit">Debit</option>
            </select>
        </div>
        <div>
            <select wire:model.live="categoryFilter"
                class="w-full px-4 py-3 text-base rounded-lg border-2 border-gray-300 dark:border-zinc-600 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 dark:bg-zinc-900/90 dark:text-white">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <select wire:model.live="monthFilter"
                class="w-full px-4 py-3 text-base rounded-lg border-2 border-gray-300 dark:border-zinc-600 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 dark:bg-zinc-900/90 dark:text-white">
                <option value="">All Months</option>
                @foreach(range(1, 12) as $m)
                    <option value="{{ $m }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="flex flex-col gap-4">
        @foreach ($expenses as $expense)
            <div
                class="bg-white dark:bg-zinc-800/90 overflow-hidden shadow-sm dark:shadow-lg dark:shadow-black/10 sm:rounded-lg p-4 border border-transparent dark:border-zinc-600 transition duration-150 ease-in-out hover:shadow-md dark:hover:shadow-xl">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-4">
                        <div
                            class="p-2 rounded-full {{ $expense->type === 'credit' ? 'bg-green-100 text-green-600 dark:bg-green-900/20 dark:text-green-400' : 'bg-red-100 text-red-600 dark:bg-red-900/20 dark:text-red-400' }}">
                            @if($expense->type === 'credit')
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 18 9 11.25l4.306 4.307a11.95 11.95 0 0 1 5.814-5.519l2.74-1.22m0 0-5.94-2.28m5.94 2.28-2.28 5.941" />
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 6 9 12.75l4.286-4.286a11.948 11.948 0 0 1 4.306 6.43l.776 2.898m0 0 3.182-5.511m-3.182 5.51-5.511-3.181" />
                                </svg>
                            @endif
                        </div>
                        <div>
                            <div class="font-medium text-gray-900 dark:text-white">
                                {{ $expense->description ?: 'No description' }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $expense->category->name }}
                                <span class="mx-1">&bull;</span>
                                @if($expense->type === 'debit' && $expense->classification)
                                    <span
                                        class="inline-flex items-center rounded-md bg-gray-50 dark:bg-zinc-700 px-2 py-1 text-xs font-medium text-gray-600 dark:text-gray-300 ring-1 ring-inset ring-gray-500/10">{{ $expense->classification }}</span>
                                    <span class="mx-1">&bull;</span>
                                @endif
                                {{ $expense->date->format('M d, Y') }}
                            </div>
                        </div>
                    </div>
                    <div class="text-right flex items-center gap-4">
                        <div
                            class="font-bold {{ $expense->type === 'credit' ? 'text-green-600 dark:text-green-400' : 'text-gray-900 dark:text-white' }}">
                            {{ $expense->type === 'credit' ? '+' : '-' }}₹{{ number_format($expense->amount, 2) }}
                        </div>
                        <div class="flex gap-2">
                            <button wire:click="openModal({{ $expense->id }})"
                                class="p-1 text-gray-600 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                </svg>
                            </button>
                            <button onclick="confirmDelete('Are you sure you want to delete this expense?', () => @this.call('delete', {{ $expense->id }}))"
                                class="p-1 text-gray-600 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        @if ($expenses->isEmpty())
            <div class="text-center py-10">
                <div
                    class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-zinc-800 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6 text-gray-400">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">No expenses found</h3>
                <p class="mt-1 text-gray-500 dark:text-gray-400">Start tracking your finances by adding an expense.</p>
                <div class="mt-6">
                    <button wire:click="openModal()"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Add Expense
                    </button>
                </div>
            </div>
        @endif

        <div class="mt-4">
            {{ $expenses->links() }}
        </div>
    </div>

    @if($isOpen)
        <div class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-gray-500/75 dark:bg-gray-900/75 transition-opacity" aria-hidden="true" wire:click="closeModal"></div>

            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div
                        class="relative transform overflow-hidden rounded-lg bg-white dark:bg-zinc-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                        <div class="bg-white dark:bg-zinc-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4" id="modal-title">
                                {{ $expenseId ? 'Edit Expense' : 'Add Expense' }}
                            </h3>

                            <div class="space-y-4">
                                <!-- Type -->
                                <div>
                                    <label
                                        class="block text-base font-semibold text-gray-900 dark:text-white mb-2">Type</label>
                                    <div class="grid grid-cols-2 gap-4">
                                        <label
                                            class="cursor-pointer border-2 rounded-lg p-4 flex items-center justify-center gap-2 text-base font-medium transition {{ $type === 'debit' ? 'bg-red-50 border-red-300 text-red-700 dark:bg-red-900/30 dark:border-red-700 dark:text-red-400 ring-2 ring-red-500' : 'border-gray-300 dark:border-zinc-600 hover:bg-gray-50 dark:hover:bg-zinc-700 text-gray-700 dark:text-gray-300' }}">
                                            <input type="radio" value="debit" wire:model.live="type" class="sr-only">
                                            <span>Debit</span>
                                        </label>
                                        <label
                                            class="cursor-pointer border-2 rounded-lg p-4 flex items-center justify-center gap-2 text-base font-medium transition {{ $type === 'credit' ? 'bg-green-50 border-green-300 text-green-700 dark:bg-green-900/30 dark:border-green-700 dark:text-green-400 ring-2 ring-green-500' : 'border-gray-300 dark:border-zinc-600 hover:bg-gray-50 dark:hover:bg-zinc-700 text-gray-700 dark:text-gray-300' }}">
                                            <input type="radio" value="credit" wire:model.live="type" class="sr-only">
                                            <span>Credit</span>
                                        </label>
                                    </div>
                                    @error('type') <span class="text-red-500 text-sm font-medium mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <!-- Date & Amount -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="date"
                                            class="block text-base font-semibold text-gray-900 dark:text-white mb-2">Date</label>
                                        <input type="date" wire:model="date" id="date"
                                            class="mt-1 block w-full px-4 py-3 text-base rounded-lg border-2 border-gray-300 dark:border-zinc-600 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 dark:bg-zinc-900/90 dark:text-white">
                                        @error('date') <span class="text-red-500 text-sm font-medium mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label for="amount"
                                            class="block text-base font-semibold text-gray-900 dark:text-white mb-2">Amount</label>
                                        <div class="relative mt-1 rounded-lg shadow-sm">
                                            <div
                                                class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                                <span class="text-gray-500 text-base font-medium">₹</span>
                                            </div>
                                            <input type="number" wire:model="amount" id="amount" step="0.01"
                                                class="block w-full px-4 py-3 pl-10 text-base rounded-lg border-2 border-gray-300 dark:border-zinc-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 dark:bg-zinc-900/90 dark:text-white placeholder-gray-400 dark:placeholder-gray-500"
                                                placeholder="0.00">
                                        </div>
                                        @error('amount') <span class="text-red-500 text-sm font-medium mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Description -->
                                <div>
                                    <label for="description"
                                        class="block text-base font-semibold text-gray-900 dark:text-white mb-2">Description</label>
                                    <input type="text" wire:model="description" id="description"
                                        class="mt-1 block w-full px-4 py-3 text-base rounded-lg border-2 border-gray-300 dark:border-zinc-600 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 dark:bg-zinc-900/90 dark:text-white placeholder-gray-400 dark:placeholder-gray-500"
                                        placeholder="e.g. Lunch with friends">
                                    @error('description') <span class="text-red-500 text-sm font-medium mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <!-- Category and Currency -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="category_id"
                                            class="block text-base font-semibold text-gray-900 dark:text-white mb-2">Category</label>
                                        <select wire:model.live="category_id" id="category_id"
                                            class="mt-1 block w-full px-4 py-3 text-base rounded-lg border-2 border-gray-300 dark:border-zinc-600 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 dark:bg-zinc-900/90 dark:text-white">
                                            <option value="">Select Category</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('category_id') <span class="text-red-500 text-sm font-medium mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label for="currency"
                                            class="block text-base font-semibold text-gray-900 dark:text-white mb-2">Currency</label>
                                        <select wire:model.live="currency" id="currency"
                                            class="mt-1 block w-full px-4 py-3 text-base rounded-lg border-2 border-gray-300 dark:border-zinc-600 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 dark:bg-zinc-900/90 dark:text-white">
                                            @foreach($availableCurrencies as $code => $details)
                                                <option value="{{ $code }}">{{ $details['symbol'] }} {{ $code }}</option>
                                            @endforeach
                                        </select>
                                        @error('currency') <span class="text-red-500 text-sm font-medium mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Classification (Needs, Wants, etc) -->
                                @if($type === 'debit')
                                    <div>
                                        <label for="classification"
                                            class="block text-base font-semibold text-gray-900 dark:text-white mb-2">Classification</label>
                                        <div class="mt-1 flex gap-2">
                                            @foreach(['Needs', 'Wants', 'Savings', 'Investments'] as $classOption)
                                                <label
                                                    class="cursor-pointer border-2 rounded-lg px-4 py-3 text-base font-medium flex-1 text-center transition {{ $classification === $classOption ? 'bg-indigo-50 border-indigo-300 text-indigo-700 dark:bg-indigo-900/30 dark:border-indigo-700 dark:text-indigo-400 ring-2 ring-indigo-500' : 'border-gray-300 dark:border-zinc-600 hover:bg-gray-50 dark:hover:bg-zinc-700 text-gray-700 dark:text-gray-300' }}">
                                                    <input type="radio" value="{{ $classOption }}" wire:model.live="classification"
                                                        class="sr-only">
                                                    <span>{{ $classOption }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                        @error('classification') <span class="text-red-500 text-sm font-medium mt-1 block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                @endif

                                <!-- Sub Category -->

                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-zinc-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="button" wire:click="save"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Save
                            </button>
                            <button type="button" wire:click="closeModal"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm dark:bg-zinc-800 dark:text-gray-300 dark:border-zinc-600 dark:hover:bg-zinc-700">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>