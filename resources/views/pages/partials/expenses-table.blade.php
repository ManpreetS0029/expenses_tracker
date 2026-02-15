<div class="hidden sm:block overflow-x-auto">
    <table class="w-full text-left text-sm">
        <thead
            class="bg-zinc-50 dark:bg-zinc-800/30 text-[11px] uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
            <tr>
                <th class="px-4 py-2.5">Date</th>
                <th class="px-4 py-2.5">Description</th>
                <th class="px-4 py-2.5">Category</th>
                <th class="px-4 py-2.5">Classification</th>
                <th class="px-4 py-2.5 text-right">Amount</th>
                <th class="px-4 py-2.5 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
            @forelse($expenses as $expense)
                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/30 transition-colors">
                    <td class="px-4 py-3 text-zinc-500 dark:text-zinc-400 tabular-nums">
                        {{ $expense->date->format('M d, Y') }}</td>
                    <td class="px-4 py-3 font-medium text-zinc-900 dark:text-zinc-100">{{ $expense->description ?: '—' }}
                    </td>
                    <td class="px-4 py-3 text-zinc-500 dark:text-zinc-400">{{ $expense->category->name }}</td>
                    <td class="px-4 py-3 text-zinc-500 dark:text-zinc-400">{{ $expense->classification ?: '—' }}</td>
                    <td class="px-4 py-3 text-right font-semibold text-red-500 dark:text-red-400 tabular-nums">
                        -{{ $expense->currency_symbol ?? '₹' }}{{ number_format($expense->amount, 2) }}</td>
                    <td class="px-4 py-3 text-right space-x-1">
                        <a href="{{ route('expenses') }}?edit={{ $expense->id }}"
                            class="text-sm text-zinc-500 hover:text-zinc-900 dark:hover:text-zinc-100 transition-colors">Edit</a>
                        <form method="POST" action="{{ route('expenses.destroy', $expense) }}" class="inline"
                            onsubmit="return confirm('Delete this expense?');">@csrf @method('DELETE')<button type="submit"
                                class="text-sm text-zinc-500 hover:text-red-500 transition-colors">Delete</button></form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-sm text-zinc-500">No expenses found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
{{-- Mobile cards --}}
<div class="sm:hidden divide-y divide-zinc-100 dark:divide-zinc-800">
    @forelse($expenses as $expense)
        <div class="flex items-center justify-between p-3">
            <div class="min-w-0 flex-1">
                <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100 truncate">{{ $expense->description ?: '—' }}
                </p>
                <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ $expense->category->name }} ·
                    {{ $expense->date->format('M d') }}</p>
            </div>
            <span
                class="ml-2 text-sm font-semibold text-red-500 dark:text-red-400 tabular-nums">-{{ $expense->currency_symbol ?? '₹' }}{{ number_format($expense->amount, 0) }}</span>
        </div>
    @empty
        <div class="p-8 text-center text-sm text-zinc-500 dark:text-zinc-400">No expenses found</div>
    @endforelse
</div>
<div class="pagination px-4 py-3 border-t border-zinc-200 dark:border-zinc-800">{{ $expenses->links() }}</div>