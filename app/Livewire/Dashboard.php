<?php

namespace App\Livewire;

use App\Models\Expense;
use App\Models\MonthlyTarget;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;

use Livewire\Attributes\Lazy;

#[Lazy]
#[Layout('layouts.app')]
class Dashboard extends Component
{
    public function placeholder()
    {
        return view('livewire.placeholders.dashboard-skeleton');
    }

    public function render()
    {
        $userId = Auth::id();
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        // 1. Current Month Stats
        $monthlyTarget = MonthlyTarget::where('user_id', $userId)
            ->where('month', '>=', $startOfMonth->toDateString())
            ->where('month', '<=', $endOfMonth->toDateString())
            ->first();

        $totalIncome = $monthlyTarget ? $monthlyTarget->total_income : 0;

        $monthlyExpenses = Expense::where('user_id', $userId)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->where('type', 'debit')
            ->sum('amount');

        // Total Lifetime Balance
        $allIncome = MonthlyTarget::where('user_id', $userId)->sum('total_income');
        $allCredits = Expense::where('user_id', $userId)->where('type', 'credit')->sum('amount');
        $allDebits = Expense::where('user_id', $userId)->where('type', 'debit')->sum('amount');
        $lifetimeBalance = ($allIncome + $allCredits) - $allDebits;

        $classificationBreakdown = Expense::where('user_id', $userId)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->where('type', 'debit')
            ->selectRaw('classification, sum(amount) as total')
            ->groupBy('classification')
            ->pluck('total', 'classification')
            ->toArray();

        // Ensure all classifications exist even if 0
        $classifications = ['Needs', 'Wants', 'Savings', 'Investments'];
        $chartData = [];
        foreach ($classifications as $class) {
            $chartData[$class] = $classificationBreakdown[$class] ?? 0;
        }

        // 2. Trend Data (Last 6 Months)
        $sixMonthsAgo = $now->copy()->subMonths(5)->startOfMonth();
        $trendsData = Expense::where('user_id', $userId)
            ->where('date', '>=', $sixMonthsAgo)
            ->get();

        $trendLabels = [];
        $incomeTrend = [];
        $expenseTrend = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            $monthKey = $month->format('Y-m');
            $trendLabels[] = $month->format('M Y');

            $monthStats = $trendsData->filter(function ($expense) use ($monthKey) {
                $date = \Carbon\Carbon::parse($expense->date);
                return $date->format('Y-m') === $monthKey;
            });

            $incomeTrend[] = (float) $monthStats->where('type', 'credit')->sum('amount');
            $expenseTrend[] = (float) $monthStats->where('type', 'debit')->sum('amount');
        }

        // 3. Category Breakdown (Top 5)
        $categoryBreakdown = Expense::where('user_id', $userId)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->where('type', 'debit')
            ->with('category')
            ->selectRaw('category_id, sum(amount) as total')
            ->groupBy('category_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->category->name ?? 'Uncategorized',
                    'total' => $item->total
                ];
            });

        // 4. Recent Expenses
        $recentExpenses = Expense::where('user_id', $userId)
            ->with('category')
            ->orderBy('date', 'desc')
            ->limit(5)
            ->get();


        $this->dispatch('charts-updated');
        return view('livewire.dashboard', [
            'totalIncome' => $totalIncome,
            'monthlyExpenses' => $monthlyExpenses,
            'monthlySavings' => $totalIncome - $monthlyExpenses,
            'lifetimeBalance' => $lifetimeBalance,
            'chartData' => $chartData,
            'trendLabels' => $trendLabels,
            'incomeTrend' => $incomeTrend,
            'expenseTrend' => $expenseTrend,
            'categoryBreakdown' => $categoryBreakdown,
            'recentExpenses' => $recentExpenses,
            'monthlyTarget' => $monthlyTarget,
        ]);
    }
}
