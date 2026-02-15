<?php

namespace App\Http\Controllers;

use App\Models\Credit;
use App\Models\Expense;
use App\Models\MonthlyTarget;
use App\Support\DateRangeHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->get('search', '');
        $periodFilter = $request->get('period', 'this_month');
        $dateFrom = $request->get('date_from', '');
        $dateTo = $request->get('date_to', '');

        $userId = Auth::id();

        $expenseQuery = Expense::where('user_id', $userId)->where('type', 'debit');
        $this->applySearch($expenseQuery, $search);
        $this->applyDateFilters($expenseQuery, $periodFilter, $dateFrom, $dateTo);

        $creditQuery = Credit::where('user_id', $userId);
        $this->applySearch($creditQuery, $search);
        $this->applyDateFilters($creditQuery, $periodFilter, $dateFrom, $dateTo);

        $totalDebit = (float) (clone $expenseQuery)->sum('amount');
        $totalCredit = (float) (clone $creditQuery)->sum('amount');

        $classificationTotals = (clone $expenseQuery)
            ->selectRaw('classification, SUM(amount) as total')
            ->groupBy('classification')
            ->pluck('total', 'classification');
        $needs = (float) ($classificationTotals['Needs'] ?? 0);
        $wants = (float) ($classificationTotals['Wants'] ?? 0);
        $savings = (float) ($classificationTotals['Savings'] ?? 0);
        $investments = (float) ($classificationTotals['Investments'] ?? 0);
        $unclassified = $totalDebit - ($needs + $wants + $savings + $investments);

        $expenseUnionQuery = Expense::where('expenses.user_id', $userId)->where('expenses.type', 'debit')
            ->join('categories', 'expenses.category_id', '=', 'categories.id')
            ->select(
                'expenses.date as date',
                'expenses.description as description',
                'categories.name as category_name',
                'expenses.classification as classification',
                'expenses.amount as amount',
                'expenses.currency_symbol as currency_symbol',
                'expenses.created_at as created_at'
            )
            ->selectRaw("'debit' as type");
        $this->applyDateFilters($expenseUnionQuery, $periodFilter, $dateFrom, $dateTo);
        $this->applySearchToJoinedQuery($expenseUnionQuery, 'expenses.description', $search);

        $creditUnionQuery = Credit::where('credits.user_id', $userId)
            ->join('categories', 'credits.category_id', '=', 'categories.id')
            ->select(
                'credits.date as date',
                'credits.description as description',
                'categories.name as category_name',
                'credits.amount as amount',
                'credits.currency_symbol as currency_symbol',
                'credits.created_at as created_at'
            )
            ->selectRaw('NULL as classification')
            ->selectRaw("'credit' as type");
        $this->applyDateFilters($creditUnionQuery, $periodFilter, $dateFrom, $dateTo);
        $this->applySearchToJoinedQuery($creditUnionQuery, 'credits.description', $search);

        $transactions = $expenseUnionQuery->union($creditUnionQuery)
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $range = $this->getPeriodStartEnd($periodFilter, $dateFrom, $dateTo);
        $monthlyTargetIncome = 0;
        if ($range) {
            [$startOfPeriod, $endOfPeriod] = $range;
            $monthlyTargetIncome = (float) MonthlyTarget::where('user_id', $userId)
                ->where('month', '>=', $startOfPeriod->toDateString())
                ->where('month', '<=', $endOfPeriod->toDateString())
                ->sum('total_income');
        }

        $totalIncome = $monthlyTargetIncome + $totalCredit;
        $moneyLeft = $totalIncome - $totalDebit;
        $moneyLeftPercent = $totalIncome > 0 ? ($moneyLeft / $totalIncome) * 100 : 0;

        $periodLabel = $periodFilter === 'custom'
            ? ($dateFrom && $dateTo ? $dateFrom.' to '.$dateTo : 'Custom')
            : (DateRangeHelper::periodLabels()[$periodFilter] ?? $periodFilter);

        $periodOptions = array_merge(['' => 'All time'], DateRangeHelper::periodLabels(), ['custom' => 'Custom date range']);

        return view('pages.reports', [
            'periodOptions' => $periodOptions,
            'totalDebit' => $totalDebit,
            'totalCredit' => $totalCredit,
            'totalIncome' => $totalIncome,
            'moneyLeft' => $moneyLeft,
            'moneyLeftPercent' => $moneyLeftPercent,
            'periodLabel' => $periodLabel,
            'needs' => $needs,
            'wants' => $wants,
            'savings' => $savings,
            'investments' => $investments,
            'unclassified' => $unclassified,
            'transactions' => $transactions,
            'search' => $search,
            'periodFilter' => $periodFilter,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        $search = $request->get('search', '');
        $periodFilter = $request->get('period', 'this_month');
        $dateFrom = $request->get('date_from', '');
        $dateTo = $request->get('date_to', '');

        $userId = Auth::id();

        $expenseQuery = Expense::where('user_id', $userId)->where('type', 'debit')->with('category:id,name');
        $this->applySearch($expenseQuery, $search);
        $this->applyDateFilters($expenseQuery, $periodFilter, $dateFrom, $dateTo);
        $expenses = $expenseQuery->orderBy('date', 'desc')->orderBy('id', 'desc')->get();

        $creditQuery = Credit::where('user_id', $userId)->with('category:id,name');
        $this->applySearch($creditQuery, $search);
        $this->applyDateFilters($creditQuery, $periodFilter, $dateFrom, $dateTo);
        $credits = $creditQuery->orderBy('date', 'desc')->orderBy('id', 'desc')->get();

        $rows = collect();
        foreach ($expenses as $e) {
            $rows->push((object) [
                'date' => $e->date,
                'description' => $e->description ?? 'No description',
                'category' => $e->category->name ?? 'Unknown',
                'classification' => $e->classification ?? '-',
                'type' => 'Debit',
                'amount' => $e->amount,
                'symbol' => $e->currency_symbol ?? '₹',
                'sort_at' => $e->created_at->format('Y-m-d H:i:s'),
            ]);
        }
        foreach ($credits as $c) {
            $rows->push((object) [
                'date' => $c->date,
                'description' => $c->description ?? 'No description',
                'category' => $c->category->name ?? 'Unknown',
                'classification' => '-',
                'type' => 'Credit',
                'amount' => $c->amount,
                'symbol' => $c->currency_symbol ?? '₹',
                'sort_at' => $c->created_at->format('Y-m-d H:i:s'),
            ]);
        }
        $rows = $rows->sortByDesc('sort_at')->values();

        $filename = 'transactions_report_'.date('Y-m-d_His').'.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($rows) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, ['Date', 'Description', 'Category', 'Classification', 'Type', 'Amount']);

            foreach ($rows as $r) {
                fputcsv($file, [
                    $r->date->format('Y-m-d'),
                    $r->description,
                    $r->category,
                    $r->classification,
                    $r->type,
                    $r->symbol.number_format($r->amount, 2, '.', ''),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder<Expense>|\Illuminate\Database\Eloquent\Builder<Credit>  $query
     */
    private function applySearch($query, string $search): void
    {
        if ($search === '') {
            return;
        }
        $query->where(function ($q) use ($search) {
            $q->where('description', 'like', '%'.$search.'%')
                ->orWhereHas('category', function ($catQ) use ($search) {
                    $catQ->where('name', 'like', '%'.$search.'%');
                });
        });
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     */
    private function applyDateFilters($query, string $periodFilter, string $dateFrom, string $dateTo): void
    {
        if ($periodFilter !== '' && $periodFilter !== 'custom') {
            [$start, $end] = DateRangeHelper::rangeForPeriod($periodFilter);
            $query->whereBetween('date', [$start, $end]);
        }
        if ($periodFilter === 'custom') {
            if ($dateFrom !== '') {
                $query->where('date', '>=', $dateFrom);
            }
            if ($dateTo !== '') {
                $query->where('date', '<=', $dateTo);
            }
        }
    }

    /**
     * @return array{0: Carbon, 1: Carbon}|null
     */
    private function getPeriodStartEnd(string $periodFilter, string $dateFrom, string $dateTo): ?array
    {
        if ($periodFilter !== '' && $periodFilter !== 'custom') {
            return DateRangeHelper::rangeForPeriod($periodFilter);
        }
        if ($periodFilter === 'custom' && $dateFrom !== '' && $dateTo !== '') {
            return [Carbon::parse($dateFrom)->startOfDay(), Carbon::parse($dateTo)->endOfDay()];
        }

        return null;
    }

    /**
     * @param  \Illuminate\Database\Query\Builder  $query
     */
    private function applySearchToJoinedQuery($query, string $descriptionColumn, string $search): void
    {
        if ($search === '') {
            return;
        }
        $search = '%'.$search.'%';
        $query->where(function ($q) use ($descriptionColumn, $search) {
            $q->where($descriptionColumn, 'like', $search)
                ->orWhere('categories.name', 'like', $search);
        });
    }
}
