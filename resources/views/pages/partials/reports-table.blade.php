<div class="hidden sm:block overflow-x-auto">
    <table class="w-full text-left text-sm">
        <thead
            class="bg-zinc-50 dark:bg-zinc-800/30 text-[11px] uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
            <tr>
                <th class="px-4 py-2.5">Date</th>
                <th class="px-4 py-2.5">Description</th>
                <th class="px-4 py-2.5">Category</th>
                <th class="px-4 py-2.5">Type</th>
                <th class="px-4 py-2.5 text-right">Amount</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
            @forelse($transactions as $tx)
                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/30 transition-colors">
                    <td class="px-4 py-3 text-zinc-500 dark:text-zinc-400 tabular-nums">
                        {{ \Carbon\Carbon::parse($tx->date)->format('M d, Y') }}</td>
                    <td class="px-4 py-3 font-medium text-zinc-900 dark:text-zinc-100">{{ $tx->description ?: '—' }}</td>
                    <td class="px-4 py-3 text-zinc-500 dark:text-zinc-400">{{ $tx->category_name }}</td>
                    <td class="px-4 py-3">
                        <span
                            class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $tx->type === 'credit' ? 'bg-emerald-500/10 text-emerald-500' : 'bg-red-500/10 text-red-500' }}">{{ ucfirst($tx->type) }}</span>
                    </td>
                    <td
                        class="px-4 py-3 text-right font-semibold tabular-nums {{ $tx->type === 'credit' ? 'text-emerald-500' : 'text-red-500' }}">
                        {{ $tx->type === 'credit' ? '+' : '-' }}{{ $tx->currency_symbol ?? '₹' }}{{ number_format($tx->amount, 2) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-sm text-zinc-500">No transactions found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="sm:hidden divide-y divide-zinc-100 dark:divide-zinc-800">
    @forelse($transactions as $tx)
        <div class="flex items-center justify-between p-3">
            <div class="min-w-0 flex-1">
                <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100 truncate">{{ $tx->description ?: '—' }}</p>
                <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ $tx->category_name }} ·
                    {{ \Carbon\Carbon::parse($tx->date)->format('M d') }}</p>
            </div>
            <span
                class="ml-2 text-sm font-semibold tabular-nums {{ $tx->type === 'credit' ? 'text-emerald-500' : 'text-red-500' }}">{{ $tx->type === 'credit' ? '+' : '-' }}{{ $tx->currency_symbol ?? '₹' }}{{ number_format($tx->amount, 0) }}</span>
        </div>
    @empty
        <div class="p-8 text-center text-sm text-zinc-500 dark:text-zinc-400">No transactions found</div>
    @endforelse
</div>
<div class="pagination px-4 py-3 border-t border-zinc-200 dark:border-zinc-800">{{ $transactions->links() }}</div>