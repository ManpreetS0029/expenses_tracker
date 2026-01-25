<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6 animate-pulse">
        <div>
            <div class="h-8 w-48 bg-neutral-200 dark:bg-neutral-700 rounded mb-2"></div>
            <div class="h-4 w-64 bg-neutral-200 dark:bg-neutral-700 rounded"></div>
        </div>
        <div class="h-10 w-32 bg-neutral-200 dark:bg-neutral-700 rounded"></div>
    </div>

    <div class="bg-white dark:bg-zinc-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div
            class="p-4 border-b border-gray-200 dark:border-zinc-700 flex flex-col md:flex-row gap-4 justify-between items-center animate-pulse">
            <div class="w-full max-w-md flex gap-4">
                <div class="flex-1 h-10 bg-neutral-200 dark:bg-neutral-700 rounded"></div>
                <div class="w-32 h-10 bg-neutral-200 dark:bg-neutral-700 rounded"></div>
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
                    @for ($i = 0; $i < 5; $i++)
                        <tr class="animate-pulse">
                            <td class="px-6 py-4">
                                <div class="h-4 w-24 bg-neutral-200 dark:bg-neutral-700 rounded"></div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="h-4 w-20 bg-neutral-200 dark:bg-neutral-700 rounded"></div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="h-4 w-20 bg-neutral-200 dark:bg-neutral-700 rounded"></div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="h-4 w-20 bg-neutral-200 dark:bg-neutral-700 rounded"></div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="h-4 w-20 bg-neutral-200 dark:bg-neutral-700 rounded"></div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="h-4 w-20 bg-neutral-200 dark:bg-neutral-700 rounded"></div>
                            </td>
                            <td class="px-6 py-4 text-right flex justify-end gap-2">
                                <div class="h-5 w-5 bg-neutral-200 dark:bg-neutral-700 rounded"></div>
                                <div class="h-5 w-5 bg-neutral-200 dark:bg-neutral-700 rounded"></div>
                            </td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-200 dark:border-zinc-700 animate-pulse">
            <div class="h-8 w-64 bg-neutral-200 dark:bg-neutral-700 rounded"></div>
        </div>
    </div>
</div>
