<?php

namespace App\Livewire;

use App\Models\Credit;
use App\Models\Expense;
use App\Models\MonthlyTarget;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
#[Layout('layouts.app')]
class Dashboard extends Component
{
    public $monthFilter = '';

    public $yearFilter = '';

    public function mount()
    {
        $this->yearFilter = date('Y');
        $this->monthFilter = date('m');
    }

    public function updatedMonthFilter()
    {
        // Reset to first page if pagination exists
    }

    public function updatedYearFilter()
    {
        // Reset to first page if pagination exists
    }

    public function placeholder()
    {
        return view('livewire.placeholders.dashboard-skeleton');
    }

    public function render()
    {
        $userId = Auth::id();

        // Use filters if set, otherwise use current month
        if ($this->yearFilter && $this->monthFilter) {
            $selectedDate = Carbon::createFromDate($this->yearFilter, $this->monthFilter, 1);
            $startOfMonth = $selectedDate->copy()->startOfMonth();
            $endOfMonth = $selectedDate->copy()->endOfMonth();
            $now = $selectedDate;
        } else {
            $now = Carbon::now();
            $startOfMonth = $now->copy()->startOfMonth();
            $endOfMonth = $now->copy()->endOfMonth();
        }

        // Current Month Target
        $monthlyTarget = MonthlyTarget::where('user_id', $userId)
            ->where('month', '>=', $startOfMonth->toDateString())
            ->where('month', '<=', $endOfMonth->toDateString())
            ->first();

        // Monthly Income (Monthly Target + Credits for the month)
        $monthlyTargetIncome = $monthlyTarget ? (float) $monthlyTarget->total_income : 0;
        $monthlyCredits = (float) Credit::where('user_id', $userId)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('amount');
        $monthlyIncome = $monthlyTargetIncome + $monthlyCredits;

        // Monthly Expenses
        $monthlyExpenses = Expense::where('user_id', $userId)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->where('type', 'debit')
            ->sum('amount');

        // Monthly Savings
        $monthlySavings = $monthlyIncome - $monthlyExpenses;

        // Lifetime Balance (Total Income = MonthlyTarget + Credits; Total Debits = Expenses)
        $totalIncome = (float) MonthlyTarget::where('user_id', $userId)->sum('total_income');
        $totalCredits = (float) Credit::where('user_id', $userId)->sum('amount');
        $totalDebits = (float) Expense::where('user_id', $userId)->where('type', 'debit')->sum('amount');
        $lifetimeBalance = ($totalIncome + $totalCredits) - $totalDebits;

        // Average Daily Spending
        // If viewing a past month, use all days. If current month, use days elapsed
        $isCurrentMonth = $now->isCurrentMonth();
        $daysElapsed = $isCurrentMonth ? Carbon::now()->day : $now->daysInMonth;
        $avgDailySpending = $daysElapsed > 0 ? $monthlyExpenses / $daysElapsed : 0;
        $daysInMonth = $now->daysInMonth;
        $daysRemaining = $daysInMonth - $daysElapsed;
        $projectedExpenses = $isCurrentMonth ? ($monthlyExpenses + ($avgDailySpending * $daysRemaining)) : $monthlyExpenses;

        // Budget Progress
        $totalBudget = $monthlyTarget ? ($monthlyTarget->needs + $monthlyTarget->wants) : 0;
        $budgetUsedPercent = $totalBudget > 0 ? ($monthlyExpenses / $totalBudget) * 100 : 0;

        // Savings Rate
        $savingsRate = $monthlyIncome > 0 ? ($monthlySavings / $monthlyIncome) * 100 : 0;

        // Money Left (Income - Expenses)
        $moneyLeft = $monthlyIncome - $monthlyExpenses;
        $moneyLeftPercent = $monthlyIncome > 0 ? ($moneyLeft / $monthlyIncome) * 100 : 0;

        // Expense by Classification (Doughnut Chart)
        $classificationData = Expense::where('user_id', $userId)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
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

        // Income vs Expenses Trend (Last 6 Months) - Optimized with grouped queries
        $trendData = ['income' => [], 'expenses' => []];
        $trendLabels = [];

        // Calculate date range for last 6 months
        $sixMonthsAgo = $now->copy()->subMonths(5)->startOfMonth();
        $currentMonthEnd = $now->copy()->endOfMonth();

        // Build labels array
        for ($i = 5; $i >= 0; $i--) {
            $trendLabels[] = $now->copy()->subMonths($i)->format('M Y');
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
            $monthKey = $now->copy()->subMonths($i)->format('Y-m');
            $targetIncome = (float) ($monthlyTargetIncomes[$monthKey] ?? 0);
            $credits = (float) ($creditsByMonth[$monthKey] ?? 0);
            $expenses = (float) ($expensesByMonth[$monthKey] ?? 0);

            $trendData['income'][] = $targetIncome + $credits;
            $trendData['expenses'][] = $expenses;
        }

        // Daily Spending (Selected Month) - Optimized with single grouped query
        $dailyData = [];
        $dailyLabels = [];
        $daysToShow = $isCurrentMonth ? $daysElapsed : $now->daysInMonth;

        // Fetch all daily expenses in one query
        $dailyExpenses = Expense::where('user_id', $userId)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->where('type', 'debit')
            ->selectRaw("CAST(strftime('%d', date) AS INTEGER) as day, SUM(amount) as total")
            ->groupBy('day')
            ->pluck('total', 'day')
            ->toArray();

        // Build daily data arrays
        for ($day = 1; $day <= $daysToShow; $day++) {
            $dailyLabels[] = $day;
            $dailyData[] = (float) ($dailyExpenses[$day] ?? 0);
        }

        // Top Categories
        $topCategories = Expense::where('expenses.user_id', $userId)
            ->whereBetween('expenses.date', [$startOfMonth, $endOfMonth])
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
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->where('type', 'debit')
            ->selectRaw("CAST(strftime('%w', date) AS INTEGER) as day_of_week, SUM(amount) as total")
            ->groupBy('day_of_week')
            ->pluck('total', 'day_of_week')
            ->toArray();

        foreach ($weekdayExpenses as $dayOfWeek => $total) {
            $weekdayData[$dayOfWeek] = (float) $total;
        }

        $weekdayLabels = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

        // Classification Budget Breakdown
        $classificationBudgets = [];
        if ($monthlyTarget) {
            $classificationBudgets = [
                [
                    'name' => 'Needs',
                    'budget' => (float) $monthlyTarget->needs,
                    'spent' => $classifications['Needs'],
                    'percent' => $monthlyTarget->needs > 0 ? min(($classifications['Needs'] / $monthlyTarget->needs) * 100, 100) : 0,
                ],
                [
                    'name' => 'Wants',
                    'budget' => (float) $monthlyTarget->wants,
                    'spent' => $classifications['Wants'],
                    'percent' => $monthlyTarget->wants > 0 ? min(($classifications['Wants'] / $monthlyTarget->wants) * 100, 100) : 0,
                ],
                [
                    'name' => 'Savings',
                    'budget' => (float) $monthlyTarget->savings,
                    'spent' => $classifications['Savings'],
                    'percent' => $monthlyTarget->savings > 0 ? min(($classifications['Savings'] / $monthlyTarget->savings) * 100, 100) : 0,
                ],
                [
                    'name' => 'Investments',
                    'budget' => (float) $monthlyTarget->investments,
                    'spent' => $classifications['Investments'],
                    'percent' => $monthlyTarget->investments > 0 ? min(($classifications['Investments'] / $monthlyTarget->investments) * 100, 100) : 0,
                ],
            ];
        }

        // Recent Transactions (expenses + credits, sorted by date desc, latest first)
        $expensesForRecent = Expense::where('user_id', $userId)
            ->where('type', 'debit')
            ->with('category:id,name')
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->limit(20)
            ->get();

        $creditsForRecent = Credit::where('user_id', $userId)
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
                'sort_at' => $e->created_at->format('Y-m-d H:i:s'),
            ]))
            ->merge($creditsForRecent->map(fn ($c) => (object) [
                'date' => $c->date,
                'description' => $c->description ?? 'No description',
                'category_name' => $c->category->name ?? 'Unknown',
                'type' => 'credit',
                'amount' => (float) $c->amount,
                'currency_symbol' => $c->currency_symbol ?? '₹',
                'sort_at' => $c->created_at->format('Y-m-d H:i:s'),
            ]))
            ->sortByDesc('sort_at')
            ->take(8)
            ->values();

        // Get available years for filter (from both expenses and credits) - Combined with UNION
        $availableYears = collect(
            \Illuminate\Support\Facades\DB::select("
                SELECT DISTINCT strftime('%Y', date) as year FROM expenses WHERE user_id = ?
                UNION
                SELECT DISTINCT strftime('%Y', date) as year FROM credits WHERE user_id = ?
                ORDER BY year DESC
            ", [$userId, $userId])
        )->pluck('year')->values();

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
            'currentMonth' => $now->format('F Y'),
            'availableYears' => $availableYears,
        ]);
    }
}
