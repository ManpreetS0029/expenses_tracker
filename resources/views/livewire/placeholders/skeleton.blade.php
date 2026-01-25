<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6 animate-pulse">
        <div>
            <div class="h-8 w-48 bg-gray-200 dark:bg-zinc-700 rounded mb-2"></div>
            <div class="h-4 w-64 bg-gray-200 dark:bg-zinc-700 rounded"></div>
        </div>
        <div class="h-10 w-32 bg-gray-200 dark:bg-zinc-700 rounded"></div>
    </div>

    <div class="grid gap-4 animate-pulse">
        @for ($i = 0; $i < 4; $i++)
            <div class="bg-white dark:bg-zinc-800 overflow-hidden shadow-sm sm:rounded-lg p-4">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-full bg-gray-200 dark:bg-zinc-700"></div>
                        <div class="h-5 w-32 bg-gray-200 dark:bg-zinc-700 rounded"></div>
                    </div>
                    <div class="flex gap-2">
                        <div class="h-5 w-5 bg-gray-200 dark:bg-zinc-700 rounded"></div>
                        <div class="h-5 w-5 bg-gray-200 dark:bg-zinc-700 rounded"></div>
                    </div>
                </div>
            </div>
        @endfor
    </div>
</div>