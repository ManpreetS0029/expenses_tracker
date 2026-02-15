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
                    <td class="px-4 py-3 text-right space-x-1.5">
                        <a href="{{ route('expenses') }}?edit={{ $expense->id }}"
                            class="inline-flex items-center gap-1 rounded-md bg-cyan-500/10 px-2 py-1 text-xs font-medium text-cyan-400 hover:bg-cyan-500/20 transition-colors"><svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487z"/></svg>Edit</a>
                        <form method="POST" action="{{ route('expenses.destroy', $expense) }}" class="inline"
                            onsubmit="confirmSubmit(event, 'Delete this expense?');">@csrf @method('DELETE')<button type="submit"
                                class="inline-flex items-center gap-1 rounded-md bg-rose-500/10 px-2 py-1 text-xs font-medium text-rose-400 hover:bg-rose-500/20 transition-colors"><svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>Delete</button></form>
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