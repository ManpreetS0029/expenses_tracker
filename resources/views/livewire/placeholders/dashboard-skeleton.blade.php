<div class="space-y-6 animate-pulse">
    <!-- Header/Stats Section Skeleton -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @for ($i = 0; $i < 4; $i++)
            <div
                class="bg-white dark:bg-neutral-800 p-6 rounded-2xl border border-neutral-200 dark:border-neutral-700 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-neutral-100 dark:bg-neutral-700 rounded-xl w-12 h-12"></div>
                    <div class="h-3 w-16 bg-neutral-100 dark:bg-neutral-700 rounded"></div>
                </div>
                <div class="h-8 w-32 bg-neutral-100 dark:bg-neutral-700 rounded mb-2"></div>
                <div class="h-3 w-24 bg-neutral-100 dark:bg-neutral-700 rounded"></div>
            </div>
        @endfor
    </div>

    <!-- Budget Status Section Skeleton -->
    <div
        class="bg-white dark:bg-neutral-800 p-6 rounded-2xl border border-neutral-200 dark:border-neutral-700 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div class="h-4 w-32 bg-neutral-100 dark:bg-neutral-700 rounded"></div>
            <div class="h-4 w-12 bg-neutral-100 dark:bg-neutral-700 rounded"></div>
        </div>
        <div class="w-full bg-neutral-100 dark:bg-neutral-700 rounded-full h-3 mb-2"></div>
        <div class="h-3 w-48 bg-neutral-100 dark:bg-neutral-700 rounded"></div>
    </div>

    <!-- Charts Section Skeleton -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div
            class="bg-white dark:bg-neutral-800 p-6 rounded-2xl border border-neutral-200 dark:border-neutral-700 shadow-sm h-96">
            <div class="h-5 w-48 bg-neutral-100 dark:bg-neutral-700 rounded mb-8"></div>
            <div class="flex items-end justify-between h-64 gap-2">
                @for ($i = 0; $i < 12; $i++)
                    <div class="bg-neutral-50 dark:bg-neutral-700/50 rounded-t w-full" style="height: {{ rand(20, 100) }}%">
                    </div>
                @endfor
            </div>
        </div>
        <div
            class="bg-white dark:bg-neutral-800 p-6 rounded-2xl border border-neutral-200 dark:border-neutral-700 shadow-sm h-96 flex flex-col items-center">
            <div class="h-5 w-48 bg-neutral-100 dark:bg-neutral-700 rounded mb-12 self-start"></div>
            <div class="rounded-full bg-neutral-100 dark:bg-neutral-700 h-56 w-56 flex items-center justify-center">
                <div class="rounded-full bg-white dark:bg-neutral-800 h-32 w-32"></div>
            </div>
            <div class="mt-8 flex gap-4">
                @for ($i = 0; $i < 4; $i++)
                    <div class="flex items-center gap-2">
                        <div class="h-3 w-3 rounded-full bg-neutral-100 dark:bg-neutral-700"></div>
                        <div class="h-3 w-12 bg-neutral-100 dark:bg-neutral-700 rounded"></div>
                    </div>
                @endfor
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Top Categories Skeleton -->
        <div
            class="lg:col-span-1 bg-white dark:bg-neutral-800 p-6 rounded-2xl border border-neutral-200 dark:border-neutral-700 shadow-sm">
            <div class="h-5 w-40 bg-neutral-100 dark:bg-neutral-700 rounded mb-8"></div>
            <div class="space-y-6">
                @for ($i = 0; $i < 4; $i++)
                    <div>
                        <div class="flex justify-between mb-2">
                            <div class="h-4 w-24 bg-neutral-100 dark:bg-neutral-700 rounded"></div>
                            <div class="h-4 w-16 bg-neutral-100 dark:bg-neutral-700 rounded"></div>
                        </div>
                        <div class="w-full bg-neutral-100 dark:bg-neutral-700 rounded-full h-1.5"></div>
                    </div>
                @endfor
            </div>
        </div>

        <!-- Recent Transactions Skeleton -->
        <div
            class="lg:col-span-2 bg-white dark:bg-neutral-800 p-6 rounded-2xl border border-neutral-200 dark:border-neutral-700 shadow-sm">
            <div class="flex items-center justify-between mb-8">
                <div class="h-5 w-40 bg-neutral-100 dark:bg-neutral-700 rounded"></div>
                <div class="h-4 w-16 bg-neutral-100 dark:bg-neutral-700 rounded"></div>
            </div>
            <div class="space-y-6">
                @for ($i = 0; $i < 5; $i++)
                    <div class="flex items-center justify-between pb-4 border-b border-neutral-50 dark:border-neutral-700">
                        <div class="flex items-center gap-4">
                            <div class="h-10 w-10 rounded-xl bg-neutral-100 dark:bg-neutral-700"></div>
                            <div>
                                <div class="h-4 w-32 bg-neutral-100 dark:bg-neutral-700 rounded mb-2"></div>
                                <div class="h-3 w-20 bg-neutral-100 dark:bg-neutral-700 rounded"></div>
                            </div>
                        </div>
                        <div class="h-5 w-20 bg-neutral-100 dark:bg-neutral-700 rounded"></div>
                    </div>
                @endfor
            </div>
        </div>
    </div>
</div>