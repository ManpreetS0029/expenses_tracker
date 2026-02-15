<div class="hidden sm:block overflow-x-auto">
    <table class="w-full text-left text-sm">
        <thead
            class="bg-zinc-50 dark:bg-zinc-800/30 text-[11px] uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
            <tr>
                <th class="px-4 py-2.5">Month</th>
                <th class="px-4 py-2.5 text-right">Income</th>
                <th class="px-4 py-2.5 text-right">Needs</th>
                <th class="px-4 py-2.5 text-right">Wants</th>
                <th class="px-4 py-2.5 text-right">Savings</th>
                <th class="px-4 py-2.5 text-right">Investments</th>
                <th class="px-4 py-2.5 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
            @forelse($targets as $target)
                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/30 transition-colors">
                    <td class="px-4 py-3 font-medium text-zinc-900 dark:text-zinc-100 tabular-nums">
                        {{ \Carbon\Carbon::parse($target->month)->format('M Y') }}</td>
                    <td class="px-4 py-3 text-right tabular-nums text-zinc-700 dark:text-zinc-300">
                        {{ number_format($target->total_income, 0) }}</td>
                    <td class="px-4 py-3 text-right tabular-nums text-zinc-500 dark:text-zinc-400">
                        {{ number_format($target->needs, 0) }}</td>
                    <td class="px-4 py-3 text-right tabular-nums text-zinc-500 dark:text-zinc-400">
                        {{ number_format($target->wants, 0) }}</td>
                    <td class="px-4 py-3 text-right tabular-nums text-zinc-500 dark:text-zinc-400">
                        {{ number_format($target->savings, 0) }}</td>
                    <td class="px-4 py-3 text-right tabular-nums text-zinc-500 dark:text-zinc-400">
                        {{ number_format($target->investments, 0) }}</td>
                    <td class="px-4 py-3 text-right space-x-1">
                        <a href="{{ route('monthly-targets') }}?edit={{ $target->id }}"
                            class="text-sm text-zinc-500 hover:text-zinc-900 dark:hover:text-zinc-100 transition-colors">Edit</a>
                        <form method="POST" action="{{ route('monthly-targets.destroy', $target) }}" class="inline"
                            onsubmit="return confirm('Delete this target?');">@csrf @method('DELETE')<button type="submit"
                                class="text-sm text-zinc-500 hover:text-red-500 transition-colors">Delete</button></form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-sm text-zinc-500">No targets found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="sm:hidden divide-y divide-zinc-100 dark:divide-zinc-800">
    @forelse($targets as $target)
        <div class="p-3">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100 tabular-nums">
                    {{ \Carbon\Carbon::parse($target->month)->format('M Y') }}</p>
                <p class="text-sm font-semibold text-zinc-900 dark:text-zinc-100 tabular-nums">
                    {{ number_format($target->total_income, 0) }}</p>
            </div>
            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5 tabular-nums">N:
                {{ number_format($target->needs, 0) }} · W: {{ number_format($target->wants, 0) }} · S:
                {{ number_format($target->savings, 0) }} · I: {{ number_format($target->investments, 0) }}</p>
        </div>
    @empty
        <div class="p-8 text-center text-sm text-zinc-500 dark:text-zinc-400">No targets found</div>
    @endforelse
</div>
<div class="pagination px-4 py-3 border-t border-zinc-200 dark:border-zinc-800">{{ $targets->links() }}</div>