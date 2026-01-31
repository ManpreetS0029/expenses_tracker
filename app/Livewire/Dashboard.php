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

        // Income vs Expenses Trend (Last 6 Months)
        $trendData = [];
        $trendLabels = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();
            $trendLabels[] = $month->format('M Y');

            $income = (float) MonthlyTarget::where('user_id', $userId)
                ->whereBetween('month', [$monthStart, $monthEnd])
                ->sum('total_income');
            $income += (float) Credit::where('user_id', $userId)
                ->whereBetween('date', [$monthStart, $monthEnd])
                ->sum('amount');

            $expenses = Expense::where('user_id', $userId)
                ->whereBetween('date', [$monthStart, $monthEnd])
                ->where('type', 'debit')
                ->sum('amount');

            $trendData['income'][] = (float) $income;
            $trendData['expenses'][] = (float) $expenses;
        }

        // Daily Spending (Selected Month)
        $dailyData = [];
        $dailyLabels = [];
        $daysToShow = $isCurrentMonth ? $daysElapsed : $now->daysInMonth;
        for ($day = 1; $day <= $daysToShow; $day++) {
            $date = $now->copy()->day($day);
            $dailyLabels[] = $day;
            $amount = Expense::where('user_id', $userId)
                ->whereDate('date', $date->toDateString())
                ->where('type', 'debit')
                ->sum('amount');
            $dailyData[] = (float) $amount;
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

        // Spending by Day of Week
        $weekdayData = [0, 0, 0, 0, 0, 0, 0];
        $expenses = Expense::where('user_id', $userId)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->where('type', 'debit')
            ->get();

        foreach ($expenses as $expense) {
            $dayOfWeek = Carbon::parse($expense->date)->dayOfWeek;
            $weekdayData[$dayOfWeek] += $expense->amount;
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

        // Get available years for filter (from both expenses and credits)
        $expenseYears = Expense::where('user_id', $userId)
            ->selectRaw("strftime('%Y', date) as year")
            ->distinct()
            ->pluck('year');
        $creditYears = Credit::where('user_id', $userId)
            ->selectRaw("strftime('%Y', date) as year")
            ->distinct()
            ->pluck('year');
        $availableYears = $expenseYears->merge($creditYears)->unique()->sortDesc()->values();

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
