<?php

namespace App\Livewire;

use App\Models\Credit;
use App\Models\Expense;
use App\Models\MonthlyTarget;
use App\Support\DateRangeHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
#[Layout('layouts.app')]
class Dashboard extends Component
{
    public $periodFilter = 'this_month';

    public function mount()
    {
        $this->periodFilter = 'this_month';
    }

    public function placeholder()
    {
        return view('livewire.placeholders.dashboard-skeleton');
    }

    public function render()
    {
        $userId = Auth::id();

        [$startOfPeriod, $endOfPeriod] = DateRangeHelper::rangeForPeriod($this->periodFilter);
        $startOfMonth = $startOfPeriod;
        $endOfMonth = $endOfPeriod;
        $now = Carbon::now();

        // Monthly targets in period (sum for range)
        $monthlyTargetsInPeriod = MonthlyTarget::where('user_id', $userId)
            ->where('month', '>=', $startOfPeriod->toDateString())
            ->where('month', '<=', $endOfPeriod->toDateString())
            ->get();
        $monthlyTargetIncome = (float) $monthlyTargetsInPeriod->sum('total_income');
        $budgetNeeds = (float) $monthlyTargetsInPeriod->sum('needs');
        $budgetWants = (float) $monthlyTargetsInPeriod->sum('wants');
        $budgetSavings = (float) $monthlyTargetsInPeriod->sum('savings');
        $budgetInvestments = (float) $monthlyTargetsInPeriod->sum('investments');

        // Income (targets + credits) and expenses for period
        $monthlyCredits = (float) Credit::where('user_id', $userId)
            ->whereBetween('date', [$startOfPeriod, $endOfPeriod])
            ->sum('amount');
        $monthlyIncome = $monthlyTargetIncome + $monthlyCredits;

        $monthlyExpenses = Expense::where('user_id', $userId)
            ->whereBetween('date', [$startOfPeriod, $endOfPeriod])
            ->where('type', 'debit')
            ->sum('amount');

        $monthlySavings = $monthlyIncome - $monthlyExpenses;

        // Lifetime Balance
        $totalIncome = (float) MonthlyTarget::where('user_id', $userId)->sum('total_income');
        $totalCredits = (float) Credit::where('user_id', $userId)->sum('amount');
        $totalDebits = (float) Expense::where('user_id', $userId)->where('type', 'debit')->sum('amount');
        $lifetimeBalance = ($totalIncome + $totalCredits) - $totalDebits;

        // Average daily spending: use days in period
        $daysInPeriod = $startOfPeriod->diffInDays($endOfPeriod) + 1;
        $avgDailySpending = $daysInPeriod > 0 ? $monthlyExpenses / $daysInPeriod : 0;
        $isCurrentMonth = $now->isCurrentMonth() && $this->periodFilter === 'this_month';
        $daysInMonth = $now->daysInMonth;
        $daysElapsed = $isCurrentMonth ? Carbon::now()->day : min($now->day, $endOfPeriod->day);
        $daysRemaining = $daysInMonth - $daysElapsed;
        $projectedExpenses = $isCurrentMonth ? ($monthlyExpenses + ($avgDailySpending * max(0, $daysRemaining))) : $monthlyExpenses;

        // Budget Progress (needs + wants for period)
        $totalBudget = $budgetNeeds + $budgetWants;
        $budgetUsedPercent = $totalBudget > 0 ? ($monthlyExpenses / $totalBudget) * 100 : 0;

        // Savings Rate
        $savingsRate = $monthlyIncome > 0 ? ($monthlySavings / $monthlyIncome) * 100 : 0;

        // Money Left (Income - Expenses)
        $moneyLeft = $monthlyIncome - $monthlyExpenses;
        $moneyLeftPercent = $monthlyIncome > 0 ? ($moneyLeft / $monthlyIncome) * 100 : 0;

        // Expense by Classification (Doughnut Chart)
        $classificationData = Expense::where('user_id', $userId)
            ->whereBetween('date', [$startOfPeriod, $endOfPeriod])
            ->where('type', 'debit')
            ->selectRaw('classification, SUM(amount) as total')
            ->groupBy('classification')
            ->get()
            ->pluck('total', 'classification')
            ->toArray();

        $classifications = ['Needs' => 0, 'Wants' => 0, 'Savings' => 0, 'Investments' => 0];
        foreach ($classificationData as $key => $value) {
            if (isset($classifications[$key])) {
                $classifications[$key] = (float) $value;
            }
        }

        // Income vs Expenses Trend (Last 6 months ending at period end)
        $trendData = ['income' => [], 'expenses' => []];
        $trendLabels = [];
        $sixMonthsAgo = $endOfPeriod->copy()->subMonths(5)->startOfMonth();
        $currentMonthEnd = $endOfPeriod->copy()->endOfMonth();

        for ($i = 5; $i >= 0; $i--) {
            $trendLabels[] = $endOfPeriod->copy()->subMonths($i)->format('M Y');
        }

        // Fetch all monthly target income in one query grouped by month
        $monthlyTargetIncomes = MonthlyTarget::where('user_id', $userId)
            ->whereBetween('month', [$sixMonthsAgo, $currentMonthEnd])
            ->selectRaw("strftime('%Y-%m', month) as month_key, SUM(total_income) as total")
            ->groupBy('month_key')
            ->pluck('total', 'month_key')
            ->toArray();

        // Fetch all credits in one query grouped by month
        $creditsByMonth = Credit::where('user_id', $userId)
            ->whereBetween('date', [$sixMonthsAgo, $currentMonthEnd])
            ->selectRaw("strftime('%Y-%m', date) as month_key, SUM(amount) as total")
            ->groupBy('month_key')
            ->pluck('total', 'month_key')
            ->toArray();

        // Fetch all expenses in one query grouped by month
        $expensesByMonth = Expense::where('user_id', $userId)
            ->whereBetween('date', [$sixMonthsAgo, $currentMonthEnd])
            ->where('type', 'debit')
            ->selectRaw("strftime('%Y-%m', date) as month_key, SUM(amount) as total")
            ->groupBy('month_key')
            ->pluck('total', 'month_key')
            ->toArray();

        // Build trend data arrays
        for ($i = 5; $i >= 0; $i--) {
            $monthKey = $endOfPeriod->copy()->subMonths($i)->format('Y-m');
            $targetIncome = (float) ($monthlyTargetIncomes[$monthKey] ?? 0);
            $credits = (float) ($creditsByMonth[$monthKey] ?? 0);
            $expenses = (float) ($expensesByMonth[$monthKey] ?? 0);

            $trendData['income'][] = $targetIncome + $credits;
            $trendData['expenses'][] = $expenses;
        }

        // Daily/Monthly Spending: single month = by day, multi-month = by month
        $dailyData = [];
        $dailyLabels = [];
        $isSingleMonth = in_array($this->periodFilter, ['this_month', 'last_month'], true);

        if ($isSingleMonth) {
            $daysToShow = $startOfPeriod->isCurrentMonth() ? Carbon::now()->day : $startOfPeriod->daysInMonth;
            $dailyExpenses = Expense::where('user_id', $userId)
                ->whereBetween('date', [$startOfPeriod, $endOfPeriod])
                ->where('type', 'debit')
                ->selectRaw("CAST(strftime('%d', date) AS INTEGER) as day, SUM(amount) as total")
                ->groupBy('day')
                ->pluck('total', 'day')
                ->toArray();
            for ($day = 1; $day <= $daysToShow; $day++) {
                $dailyLabels[] = $day;
                $dailyData[] = (float) ($dailyExpenses[$day] ?? 0);
            }
        } else {
            $monthsInRange = $startOfPeriod->diffInMonths($endOfPeriod) + 1;
            $cursor = $startOfPeriod->copy();
            for ($i = 0; $i < $monthsInRange; $i++) {
                $monthKey = $cursor->format('Y-m');
                $dailyLabels[] = $cursor->format('M Y');
                $monthSum = Expense::where('user_id', $userId)
                    ->whereBetween('date', [$cursor->copy()->startOfMonth(), $cursor->copy()->endOfMonth()])
                    ->where('type', 'debit')
                    ->sum('amount');
                $dailyData[] = (float) $monthSum;
                $cursor->addMonth();
            }
        }

        // Top Categories
        $topCategories = Expense::where('expenses.user_id', $userId)
            ->whereBetween('expenses.date', [$startOfPeriod, $endOfPeriod])
            ->where('expenses.type', 'debit')
            ->join('categories', 'expenses.category_id', '=', 'categories.id')
            ->selectRaw('categories.name, SUM(expenses.amount) as total')
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->map(fn ($item) => [
                'name' => $item->name,
                'total' => (float) $item->total,
            ])
            ->toArray();

        // Calculate percentage for each category
        $totalCategorySpending = array_sum(array_column($topCategories, 'total'));
        $topCategories = array_map(function ($category) use ($totalCategorySpending) {
            $category['percentage'] = $totalCategorySpending > 0 ? ($category['total'] / $totalCategorySpending) * 100 : 0;

            return $category;
        }, $topCategories);

        // Spending by Day of Week - Optimized with database aggregation
        $weekdayData = [0, 0, 0, 0, 0, 0, 0];

        // Use database aggregation instead of loading all records
        $weekdayExpenses = Expense::where('user_id', $userId)
            ->whereBetween('date', [$startOfPeriod, $endOfPeriod])
            ->where('type', 'debit')
            ->selectRaw("CAST(strftime('%w', date) AS INTEGER) as day_of_week, SUM(amount) as total")
            ->groupBy('day_of_week')
            ->pluck('total', 'day_of_week')
            ->toArray();

        foreach ($weekdayExpenses as $dayOfWeek => $total) {
            $weekdayData[$dayOfWeek] = (float) $total;
        }

        $weekdayLabels = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

        // Classification Budget Breakdown (summed for period)
        $classificationBudgets = [];
        if ($totalBudget > 0 || $budgetNeeds > 0 || $budgetWants > 0 || $budgetSavings > 0 || $budgetInvestments > 0) {
            $classificationBudgets = [
                [
                    'name' => 'Needs',
                    'budget' => $budgetNeeds,
                    'spent' => $classifications['Needs'],
                    'percent' => $budgetNeeds > 0 ? min(($classifications['Needs'] / $budgetNeeds) * 100, 100) : 0,
                ],
                [
                    'name' => 'Wants',
                    'budget' => $budgetWants,
                    'spent' => $classifications['Wants'],
                    'percent' => $budgetWants > 0 ? min(($classifications['Wants'] / $budgetWants) * 100, 100) : 0,
                ],
                [
                    'name' => 'Savings',
                    'budget' => $budgetSavings,
                    'spent' => $classifications['Savings'],
                    'percent' => $budgetSavings > 0 ? min(($classifications['Savings'] / $budgetSavings) * 100, 100) : 0,
                ],
                [
                    'name' => 'Investments',
                    'budget' => $budgetInvestments,
                    'spent' => $classifications['Investments'],
                    'percent' => $budgetInvestments > 0 ? min(($classifications['Investments'] / $budgetInvestments) * 100, 100) : 0,
                ],
            ];
        }

        // Recent Transactions: from selected period, sorted by transaction date
        $expensesForRecent = Expense::where('user_id', $userId)
            ->where('type', 'debit')
            ->whereBetween('date', [$startOfPeriod, $endOfPeriod])
            ->with('category:id,name')
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->limit(20)
            ->get();

        $creditsForRecent = Credit::where('user_id', $userId)
            ->whereBetween('date', [$startOfPeriod, $endOfPeriod])
            ->with('category:id,name')
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->limit(20)
            ->get();

        $recentTransactions = collect()
            ->merge($expensesForRecent->map(fn ($e) => (object) [
                'date' => $e->date,
                'description' => $e->description ?? 'No description',
                'category_name' => $e->category->name ?? 'Unknown',
                'type' => 'debit',
                'amount' => (float) $e->amount,
                'currency_symbol' => $e->currency_symbol ?? '₹',
                'sort_at' => $e->date->format('Y-m-d').'-'.str_pad((string) $e->id, 10, '0', STR_PAD_LEFT),
            ]))
            ->merge($creditsForRecent->map(fn ($c) => (object) [
                'date' => $c->date,
                'description' => $c->description ?? 'No description',
                'category_name' => $c->category->name ?? 'Unknown',
                'type' => 'credit',
                'amount' => (float) $c->amount,
                'currency_symbol' => $c->currency_symbol ?? '₹',
                'sort_at' => $c->date->format('Y-m-d').'-'.str_pad((string) $c->id, 10, '0', STR_PAD_LEFT),
            ]))
            ->sortByDesc('sort_at')
            ->take(8)
            ->values();

        $periodLabel = DateRangeHelper::periodLabels()[$this->periodFilter] ?? $this->periodFilter;

        $this->dispatch('init-charts');

        return view('livewire.dashboard', [
            'monthlyIncome' => (float) $monthlyIncome,
            'monthlyExpenses' => (float) $monthlyExpenses,
            'monthlySavings' => (float) $monthlySavings,
            'lifetimeBalance' => (float) $lifetimeBalance,
            'avgDailySpending' => (float) $avgDailySpending,
            'projectedExpenses' => (float) $projectedExpenses,
            'budgetUsedPercent' => (float) $budgetUsedPercent,
            'savingsRate' => (float) $savingsRate,
            'totalBudget' => (float) $totalBudget,
            'moneyLeft' => (float) $moneyLeft,
            'moneyLeftPercent' => (float) $moneyLeftPercent,
            'classifications' => $classifications,
            'trendLabels' => $trendLabels,
            'trendIncome' => $trendData['income'],
            'trendExpenses' => $trendData['expenses'],
            'dailyLabels' => $dailyLabels,
            'dailyData' => $dailyData,
            'topCategories' => $topCategories,
            'weekdayLabels' => $weekdayLabels,
            'weekdayData' => array_map('floatval', $weekdayData),
            'classificationBudgets' => $classificationBudgets,
            'recentTransactions' => $recentTransactions,
            'currentMonth' => $periodLabel,
            'periodFilter' => $this->periodFilter,
            'periodOptions' => DateRangeHelper::periodLabels(),
            'isSingleMonth' => $isSingleMonth,
        ]);
    }
}
