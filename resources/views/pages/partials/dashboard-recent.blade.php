<div class="card p-4 sm:p-5">
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h3 class="text-sm font-medium text-zinc-900 dark:text-zinc-50">Recent Transactions</h3>
            <p class="text-xs text-zinc-500 dark:text-zinc-400">Latest activity</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('expenses') }}"
                class="text-xs font-medium text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-200 transition-colors">View
                expenses →</a>
            <a href="{{ route('credits') }}"
                class="text-xs font-medium text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-200 transition-colors">View
                credits →</a>
        </div>
    </div>

    {{-- Mobile cards --}}
    <div class="mt-4 space-y-2 sm:hidden">
        @forelse($recentTransactions as $tx)
            <div class="rounded-lg border border-zinc-100 bg-zinc-50/50 p-3 dark:border-zinc-800 dark:bg-zinc-800/30">
                <div class="flex justify-between items-start gap-2">
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100 truncate">{{ $tx->description }}</p>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ $tx->category_name }}</p>
                    </div>
                    <span
                        class="shrink-0 text-sm font-semibold tabular-nums {{ $tx->type === 'credit' ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-500 dark:text-red-400' }}">{{ $tx->type === 'credit' ? '+' : '−' }}{{ $tx->currency_symbol }}{{ number_format($tx->amount, 0) }}</span>
                </div>
                <div class="mt-2 flex items-center justify-between text-xs text-zinc-500 dark:text-zinc-400">
                    <span>{{ $tx->date->format('M d, Y') }}</span>
                    <span
                        class="rounded-full px-2 py-0.5 font-medium text-[11px] {{ $tx->type === 'credit' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-400' : 'bg-red-50 text-red-700 dark:bg-red-900/20 dark:text-red-400' }}">{{ ucfirst($tx->type) }}</span>
                </div>
            </div>
        @empty
            <p class="py-8 text-center text-sm text-zinc-500 dark:text-zinc-400">No transactions yet</p>
        @endforelse
    </div>

    {{-- Desktop table --}}
    <div class="mt-4 hidden overflow-x-auto sm:block">
        <table class="w-full min-w-[500px]">
            <thead>
                <tr
                    class="border-b border-zinc-200 text-left text-[11px] font-medium uppercase tracking-wider text-zinc-500 dark:border-zinc-800 dark:text-zinc-400">
                    <th class="pb-2.5">Date</th>
                    <th class="pb-2.5">Description</th>
                    <th class="pb-2.5">Category</th>
                    <th class="pb-2.5">Type</th>
                    <th class="pb-2.5 text-right">Amount</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                @forelse($recentTransactions as $tx)
                    <tr class="transition-colors hover:bg-zinc-50 dark:hover:bg-zinc-800/30">
                        <td class="py-2.5 text-sm text-zinc-500 dark:text-zinc-400 tabular-nums">
                            {{ $tx->date->format('M d') }}</td>
                        <td class="py-2.5 text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $tx->description }}</td>
                        <td class="py-2.5 text-sm text-zinc-500 dark:text-zinc-400">{{ $tx->category_name }}</td>
                        <td class="py-2.5">
                            <span
                                class="inline-flex rounded-full px-2 py-0.5 text-[11px] font-medium {{ $tx->type === 'credit' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-400' : 'bg-red-50 text-red-700 dark:bg-red-900/20 dark:text-red-400' }}">{{ ucfirst($tx->type) }}</span>
                        </td>
                        <td
                            class="py-2.5 text-right text-sm font-semibold tabular-nums {{ $tx->type === 'credit' ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-500 dark:text-red-400' }}">
                            {{ $tx->type === 'credit' ? '+' : '−' }}{{ $tx->currency_symbol }}{{ number_format($tx->amount, 2) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-10 text-center text-sm text-zinc-500 dark:text-zinc-400">No transactions
                            yet</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>