<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Monthly Targets</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Manage your financial goals for each month.</p>
        </div>
        <button wire:click="create()"
            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
            Add Target
        </button>
    </div>

    <div class="bg-white dark:bg-zinc-800/90 overflow-hidden shadow-sm dark:shadow-lg dark:shadow-black/10 sm:rounded-lg border border-transparent dark:border-zinc-600">
        <div
            class="p-4 border-b border-gray-200 dark:border-zinc-700 flex flex-col md:flex-row gap-4 justify-between items-center">
            <div class="w-full max-w-md flex gap-4">
                <div class="flex-1">
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search..."
                        class="w-full px-4 py-3 text-base rounded-lg border-2 border-gray-300 dark:border-zinc-600 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 dark:bg-zinc-900/90 dark:text-white placeholder-gray-400 dark:placeholder-gray-500">
                </div>
                <div class="w-40">
                    <select wire:model.live="yearFilter"
                        class="w-full px-4 py-3 text-base rounded-lg border-2 border-gray-300 dark:border-zinc-600 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 dark:bg-zinc-900/90 dark:text-white">
                        <option value="">All Years</option>
                        @foreach($availableYears as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                <thead class="bg-gray-50 dark:bg-zinc-700/50 text-xs uppercase text-gray-700 dark:text-gray-300">
                    <tr>
                        <th scope="col" class="px-6 py-3">Month</th>
                        <th scope="col" class="px-6 py-3">Total Income</th>
                        <th scope="col" class="px-6 py-3">Needs</th>
                        <th scope="col" class="px-6 py-3">Wants</th>
                        <th scope="col" class="px-6 py-3">Savings</th>
                        <th scope="col" class="px-6 py-3">Investments</th>
                        <th scope="col" class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                    @forelse($targets as $target)
                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition">
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                {{ $target->month->format('F Y') }}
                            </td>
                            <td class="px-6 py-4">₹{{ number_format($target->total_income, 2) }}</td>
                            <td class="px-6 py-4">₹{{ number_format($target->needs, 2) }}</td>
                            <td class="px-6 py-4">₹{{ number_format($target->wants, 2) }}</td>
                            <td class="px-6 py-4">₹{{ number_format($target->savings, 2) }}</td>
                            <td class="px-6 py-4">₹{{ number_format($target->investments, 2) }}</td>
                            <td class="px-6 py-4 text-right flex justify-end gap-2">
                                <button wire:click="edit({{ $target->id }})"
                                    class="p-1 text-gray-600 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 transition"
                                    title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                    </svg>
                                </button>
                                <button onclick="confirmDelete('Are you sure you want to delete this target?', () => @this.call('delete', {{ $target->id }}))"
                                    class="p-1 text-gray-600 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400 transition"
                                    title="Delete">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                                No monthly targets found. Click "Add Target" to create one.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-200 dark:border-zinc-700">
            {{ $targets->links() }}
        </div>
    </div>

    @if($isOpen)
        <div class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-gray-500/75 dark:bg-gray-900/75 transition-opacity" aria-hidden="true" wire:click="closeModal"></div>
            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div
                        class="relative transform overflow-hidden rounded-lg bg-white dark:bg-zinc-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl">
                        <div class="bg-white dark:bg-zinc-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4" id="modal-title">
                                {{ $targetId ? 'Edit Monthly Target' : 'Add Monthly Target' }}
                            </h3>

                            <form wire:submit.prevent="save" class="space-y-6">
                                <!-- Month Selection -->
                                <div>
                                    <label for="month_year"
                                        class="block text-base font-semibold text-gray-900 dark:text-white mb-2">Month &
                                        Year</label>
                                    <input type="month" wire:model="month_year" id="month_year"
                                        class="mt-1 block w-full px-4 py-3 text-base rounded-lg border-2 border-gray-300 dark:border-zinc-600 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 dark:bg-zinc-900/90 dark:text-white">
                                    @error('month_year') <span class="text-red-500 text-sm font-medium mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Total Income -->
                                    <div class="col-span-1 md:col-span-2">
                                        <label for="total_income"
                                            class="block text-base font-semibold text-gray-900 dark:text-white mb-2">Total
                                            Expected Income</label>
                                        <div class="relative mt-1 rounded-lg shadow-sm">
                                            <div
                                                class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                                <span class="text-gray-500 text-base font-medium">₹</span>
                                            </div>
                                            <input type="number" wire:model.live.debounce.500ms="total_income"
                                                id="total_income" step="0.01"
                                                class="block w-full px-4 py-3 pl-10 text-base rounded-lg border-2 border-gray-300 dark:border-zinc-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 dark:bg-zinc-900/90 dark:text-white placeholder-gray-400 dark:placeholder-gray-500"
                                                placeholder="0.00">
                                        </div>
                                        @error('total_income') <span class="text-red-500 text-sm font-medium mt-1 block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Needs -->
                                    <div>
                                        <div class="flex justify-between items-center mb-1">
                                            <label for="needs"
                                                class="block text-base font-semibold text-gray-900 dark:text-white">Needs</label>
                                            <div class="flex items-center gap-1">
                                                <input type="number" wire:model.live="needs_percent"
                                                    class="w-16 text-sm rounded-md border-2 border-gray-300 dark:border-zinc-600 dark:bg-zinc-900/90 dark:text-white p-2 text-right"
                                                    min="0" max="100">
                                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">%</span>
                                            </div>
                                        </div>
                                        <div class="relative rounded-lg shadow-sm">
                                            <div
                                                class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                                <span class="text-gray-500 text-base font-medium">₹</span>
                                            </div>
                                            <input type="number" wire:model="needs" id="needs" step="0.01"
                                                class="block w-full px-4 py-3 pl-10 text-base rounded-lg border-2 border-gray-300 dark:border-zinc-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 dark:bg-zinc-900/90 dark:text-white placeholder-gray-400 dark:placeholder-gray-500"
                                                placeholder="0.00">
                                        </div>
                                        @error('needs') <span class="text-red-500 text-sm font-medium mt-1 block">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Wants -->
                                    <div>
                                        <div class="flex justify-between items-center mb-1">
                                            <label for="wants"
                                                class="block text-base font-semibold text-gray-900 dark:text-white">Wants</label>
                                            <div class="flex items-center gap-1">
                                                <input type="number" wire:model.live="wants_percent"
                                                    class="w-16 text-sm rounded-md border-2 border-gray-300 dark:border-zinc-600 dark:bg-zinc-900/90 dark:text-white p-2 text-right"
                                                    min="0" max="100">
                                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">%</span>
                                            </div>
                                        </div>
                                        <div class="relative rounded-lg shadow-sm">
                                            <div
                                                class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                                <span class="text-gray-500 text-base font-medium">₹</span>
                                            </div>
                                            <input type="number" wire:model="wants" id="wants" step="0.01"
                                                class="block w-full px-4 py-3 pl-10 text-base rounded-lg border-2 border-gray-300 dark:border-zinc-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 dark:bg-zinc-900/90 dark:text-white placeholder-gray-400 dark:placeholder-gray-500"
                                                placeholder="0.00">
                                        </div>
                                        @error('wants') <span class="text-red-500 text-sm font-medium mt-1 block">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Savings -->
                                    <div>
                                        <div class="flex justify-between items-center mb-1">
                                            <label for="savings"
                                                class="block text-base font-semibold text-gray-900 dark:text-white">Savings</label>
                                            <div class="flex items-center gap-1">
                                                <input type="number" wire:model.live="savings_percent"
                                                    class="w-16 text-sm rounded-md border-2 border-gray-300 dark:border-zinc-600 dark:bg-zinc-900/90 dark:text-white p-2 text-right"
                                                    min="0" max="100">
                                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">%</span>
                                            </div>
                                        </div>
                                        <div class="relative rounded-lg shadow-sm">
                                            <div
                                                class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                                <span class="text-gray-500 text-base font-medium">₹</span>
                                            </div>
                                            <input type="number" wire:model="savings" id="savings" step="0.01"
                                                class="block w-full px-4 py-3 pl-10 text-base rounded-lg border-2 border-gray-300 dark:border-zinc-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 dark:bg-zinc-900/90 dark:text-white placeholder-gray-400 dark:placeholder-gray-500"
                                                placeholder="0.00">
                                        </div>
                                        @error('savings') <span class="text-red-500 text-sm font-medium mt-1 block">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Investments -->
                                    <div>
                                        <div class="flex justify-between items-center mb-1">
                                            <label for="investments"
                                                class="block text-base font-semibold text-gray-900 dark:text-white">Investments</label>
                                            <div class="flex items-center gap-1">
                                                <input type="number" wire:model.live="investments_percent"
                                                    class="w-16 text-sm rounded-md border-2 border-gray-300 dark:border-zinc-600 dark:bg-zinc-900/90 dark:text-white p-2 text-right"
                                                    min="0" max="100">
                                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">%</span>
                                            </div>
                                        </div>
                                        <div class="relative rounded-lg shadow-sm">
                                            <div
                                                class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                                <span class="text-gray-500 text-base font-medium">₹</span>
                                            </div>
                                            <input type="number" wire:model="investments" id="investments" step="0.01"
                                                class="block w-full px-4 py-3 pl-10 text-base rounded-lg border-2 border-gray-300 dark:border-zinc-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 dark:bg-zinc-900/90 dark:text-white placeholder-gray-400 dark:placeholder-gray-500"
                                                placeholder="0.00">
                                        </div>
                                        @error('investments') <span class="text-red-500 text-sm font-medium mt-1 block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </form>
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