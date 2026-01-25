<div class="max-w-3xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white dark:bg-zinc-800/90 shadow-sm dark:shadow-lg dark:shadow-black/10 sm:rounded-lg border border-transparent dark:border-zinc-600">
        <div class="p-6 border-b border-gray-200 dark:border-zinc-700">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Currency Settings</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Set your preferred currency for displaying amounts throughout the application.
            </p>
        </div>

        <div class="p-6 space-y-6">
            <div>
                <label for="currency" class="block text-base font-semibold text-gray-900 dark:text-white mb-3">
                    Select Currency
                </label>
                <select wire:model.live="currency" id="currency"
                    class="w-full px-4 py-3 text-base rounded-lg border-2 border-gray-300 dark:border-zinc-600 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 dark:bg-zinc-900/90 dark:text-white">
                    @foreach($availableCurrencies as $code => $details)
                        <option value="{{ $code }}">
                            {{ $details['symbol'] }} - {{ $details['name'] }} ({{ $code }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="bg-gray-50 dark:bg-zinc-900 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Selected Currency</p>
                        <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">
                            {{ $currency_symbol }} {{ $availableCurrencies[$currency]['name'] ?? 'Unknown' }}
                        </p>
                    </div>
                    <div class="text-4xl">{{ $currency_symbol }}</div>
                </div>
            </div>

            <div class="flex justify-end">
                <button wire:click="save" type="button"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Save Settings
                </button>
            </div>
        </div>
    </div>

    <div class="mt-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">Note</h3>
                <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                    <p>Changing the currency will affect how amounts are displayed in reports and dashboards. Existing expense amounts will not be converted.</p>
                </div>
            </div>
        </div>
    </div>
</div>
