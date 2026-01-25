<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4 animate-pulse">
        <div>
            <div class="h-8 w-48 bg-neutral-200 dark:bg-neutral-700 rounded mb-2"></div>
            <div class="h-4 w-64 bg-neutral-200 dark:bg-neutral-700 rounded"></div>
        </div>

        <!-- Filters Skeleton -->
        <div class="flex gap-2">
            <div class="w-48 h-10 bg-neutral-200 dark:bg-neutral-700 rounded"></div>
            <div class="w-32 h-10 bg-neutral-200 dark:bg-neutral-700 rounded"></div>
            <div class="w-32 h-10 bg-neutral-200 dark:bg-neutral-700 rounded"></div>
        </div>
    </div>

    <!-- Stats Cards Skeleton -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        @for ($i = 0; $i < 2; $i++)
            <div class="bg-white dark:bg-zinc-800 overflow-hidden shadow-sm sm:rounded-lg p-6 animate-pulse">
                <div class="h-5 w-32 bg-neutral-200 dark:bg-neutral-700 rounded mb-4"></div>
                <div class="h-10 w-40 bg-neutral-200 dark:bg-neutral-700 rounded"></div>
            </div>
        @endfor
    </div>

    <!-- Charts Skeleton -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        @for ($i = 0; $i < 2; $i++)
            <div class="bg-white dark:bg-zinc-800 overflow-hidden shadow-sm sm:rounded-lg p-6 animate-pulse">
                <div class="h-5 w-40 bg-neutral-200 dark:bg-neutral-700 rounded mb-4"></div>
                <div class="relative h-64 w-full bg-neutral-100 dark:bg-neutral-700/30 rounded"></div>
            </div>
        @endfor
    </div>

    <!-- Transactions Table Skeleton -->
    <div class="bg-white dark:bg-zinc-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-4 border-b border-gray-200 dark:border-zinc-700 animate-pulse">
            <div class="h-5 w-32 bg-neutral-200 dark:bg-neutral-700 rounded"></div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                <thead class="bg-gray-50 dark:bg-zinc-700/50 text-xs uppercase text-gray-700 dark:text-gray-300">
                    <tr>
                        <th scope="col" class="px-6 py-3">Date</th>
                        <th scope="col" class="px-6 py-3">Description</th>
                        <th scope="col" class="px-6 py-3">Category</th>
                        <th scope="col" class="px-6 py-3">Type</th>
                        <th scope="col" class="px-6 py-3 text-right">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                    @for ($i = 0; $i < 8; $i++)
                        <tr class="animate-pulse">
                            <td class="px-6 py-4">
                                <div class="h-4 w-24 bg-neutral-200 dark:bg-neutral-700 rounded"></div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="h-4 w-32 bg-neutral-200 dark:bg-neutral-700 rounded mb-1"></div>
                                <div class="h-3 w-16 bg-neutral-200 dark:bg-neutral-700 rounded"></div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="h-4 w-20 bg-neutral-200 dark:bg-neutral-700 rounded"></div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="h-6 w-16 bg-neutral-200 dark:bg-neutral-700 rounded-md"></div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="h-4 w-24 bg-neutral-200 dark:bg-neutral-700 rounded ml-auto"></div>
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
