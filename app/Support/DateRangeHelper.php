<?php

namespace App\Support;

use Carbon\Carbon;

class DateRangeHelper
{
    /**
     * Return [startDate, endDate] for the given period key (relative to today).
     *
     * @return array{0: Carbon, 1: Carbon}
     */
    public static function rangeForPeriod(string $period): array
    {
        $now = Carbon::now();

        return match ($period) {
            'this_month' => [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()],
            'last_month' => [
                $now->copy()->subMonth()->startOfMonth(),
                $now->copy()->subMonth()->endOfMonth(),
            ],
            'last_3_months' => [
                $now->copy()->subMonths(2)->startOfMonth(),
                $now->copy()->endOfMonth(),
            ],
            'last_6_months' => [
                $now->copy()->subMonths(5)->startOfMonth(),
                $now->copy()->endOfMonth(),
            ],
            'last_year' => [
                $now->copy()->subMonths(11)->startOfMonth(),
                $now->copy()->endOfMonth(),
            ],
            default => [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()],
        };
    }

    /**
     * Period options for select dropdowns (value => label).
     *
     * @return array<string, string>
     */
    public static function periodLabels(): array
    {
        return [
            'this_month' => 'This month',
            'last_month' => 'Last month',
            'last_3_months' => 'Last 3 months',
            'last_6_months' => 'Last 6 months',
            'last_year' => 'Last year',
        ];
    }
}
