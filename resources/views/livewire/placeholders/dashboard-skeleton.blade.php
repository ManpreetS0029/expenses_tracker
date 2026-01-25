<div class="space-y-6">
    <!-- Header/Stats Section Skeleton -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @for ($i = 0; $i < 4; $i++)
            <div
                class="bg-gradient-to-br from-neutral-50 to-neutral-100 dark:from-neutral-900/50 dark:to-neutral-800/50 p-6 rounded-2xl border border-neutral-200 dark:border-neutral-700 shadow-sm animate-pulse">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-neutral-200 dark:bg-neutral-700 rounded-xl w-12 h-12"></div>
                    <div class="h-3 w-16 bg-neutral-200 dark:bg-neutral-700 rounded"></div>
                </div>
                <div class="h-8 w-32 bg-neutral-200 dark:bg-neutral-700 rounded mb-2"></div>
                <div class="h-3 w-24 bg-neutral-200 dark:bg-neutral-700 rounded"></div>
            </div>
        @endfor
    </div>

    <!-- Quick Insights Skeleton -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @for ($i = 0; $i < 3; $i++)
            <div
                class="bg-white dark:bg-neutral-800 p-5 rounded-xl border border-neutral-200 dark:border-neutral-700 shadow-sm animate-pulse">
                <div class="flex items-center justify-between">
                    <div class="space-y-2">
                        <div class="h-3 w-24 bg-neutral-200 dark:bg-neutral-700 rounded"></div>
                        <div class="h-6 w-32 bg-neutral-200 dark:bg-neutral-700 rounded"></div>
                    </div>
                    <div class="p-3 bg-neutral-100 dark:bg-neutral-700 rounded-lg w-12 h-12"></div>
                </div>
            </div>
        @endfor
    </div>

    <!-- Classification Budget Breakdown Skeleton -->
    <div
        class="bg-white dark:bg-neutral-800 p-6 rounded-2xl border border-neutral-200 dark:border-neutral-700 shadow-sm animate-pulse">
        <div class="h-5 w-48 bg-neutral-200 dark:bg-neutral-700 rounded mb-5"></div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
            @for ($i = 0; $i < 4; $i++)
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <div class="h-4 w-16 bg-neutral-200 dark:bg-neutral-700 rounded"></div>
                        <div class="h-5 w-12 bg-neutral-200 dark:bg-neutral-700 rounded"></div>
                    </div>
                    <div class="w-full bg-neutral-200 dark:bg-neutral-700 rounded-full h-2"></div>
                    <div class="flex justify-between">
                        <div class="h-3 w-12 bg-neutral-200 dark:bg-neutral-700 rounded"></div>
                        <div class="h-3 w-12 bg-neutral-200 dark:bg-neutral-700 rounded"></div>
                    </div>
                </div>
            @endfor
        </div>
    </div>

    <!-- Charts Section Skeleton -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @for ($i = 0; $i < 2; $i++)
            <div
                class="bg-white dark:bg-neutral-800 p-6 rounded-2xl border border-neutral-200 dark:border-neutral-700 shadow-sm animate-pulse">
                <div class="h-5 w-48 bg-neutral-200 dark:bg-neutral-700 rounded mb-5"></div>
                <div class="h-80 bg-neutral-100 dark:bg-neutral-700/30 rounded-xl"></div>
            </div>
        @endfor
    </div>

    <!-- Daily & Weekly Charts Skeleton -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div
            class="lg:col-span-2 bg-white dark:bg-neutral-800 p-6 rounded-2xl border border-neutral-200 dark:border-neutral-700 shadow-sm animate-pulse">
            <div class="h-5 w-48 bg-neutral-200 dark:bg-neutral-700 rounded mb-5"></div>
            <div class="h-64 bg-neutral-100 dark:bg-neutral-700/30 rounded-xl"></div>
        </div>
        <div
            class="bg-white dark:bg-neutral-800 p-6 rounded-2xl border border-neutral-200 dark:border-neutral-700 shadow-sm animate-pulse">
            <div class="h-5 w-48 bg-neutral-200 dark:bg-neutral-700 rounded mb-5"></div>
            <div class="h-64 bg-neutral-100 dark:bg-neutral-700/30 rounded-xl"></div>
        </div>
    </div>

    <!-- Top Categories Chart Skeleton -->
    <div
        class="bg-white dark:bg-neutral-800 p-6 rounded-2xl border border-neutral-200 dark:border-neutral-700 shadow-sm animate-pulse">
        <div class="h-5 w-48 bg-neutral-200 dark:bg-neutral-700 rounded mb-5"></div>
        <div class="h-80 bg-neutral-100 dark:bg-neutral-700/30 rounded-xl"></div>
    </div>

    <!-- Recent Transactions Skeleton -->
    <div
        class="bg-white dark:bg-neutral-800 p-6 rounded-2xl border border-neutral-200 dark:border-neutral-700 shadow-sm animate-pulse">
        <div class="flex items-center justify-between mb-5">
            <div class="h-5 w-40 bg-neutral-200 dark:bg-neutral-700 rounded"></div>
            <div class="h-4 w-20 bg-neutral-200 dark:bg-neutral-700 rounded"></div>
        </div>
        <div class="space-y-4">
            @for ($i = 0; $i < 5; $i++)
                <div class="flex items-center justify-between py-3">
                    <div class="flex items-center gap-4 flex-1">
                        <div class="h-3 w-20 bg-neutral-200 dark:bg-neutral-700 rounded"></div>
                        <div class="h-3 w-32 bg-neutral-200 dark:bg-neutral-700 rounded"></div>
                        <div class="h-3 w-24 bg-neutral-200 dark:bg-neutral-700 rounded"></div>
                        <div class="h-5 w-16 bg-neutral-200 dark:bg-neutral-700 rounded"></div>
                    </div>
                    <div class="h-4 w-24 bg-neutral-200 dark:bg-neutral-700 rounded"></div>
                </div>
            @endfor
        </div>
    </div>
</div>