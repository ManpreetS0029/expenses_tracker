<?php

namespace App\Livewire;

use App\Models\Credit;
use App\Models\Expense;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
#[Layout('layouts.app')]
class Reports extends Component
{
    use \Livewire\WithPagination;

    public $search = '';

    public $yearFilter = '';

    public $monthFilter = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedYearFilter()
    {
        $this->resetPage();
    }

    public function updatedMonthFilter()
    {
        $this->resetPage();
    }

    protected function applySearch($query): void
    {
        if (! $this->search) {
            return;
        }
        $query->where(function ($q) {
            $q->where('description', 'like', '%'.$this->search.'%')
                ->orWhereHas('category', function ($catQ) {
                    $catQ->where('name', 'like', '%'.$this->search.'%');
                });
        });
    }

    protected function applyDateFilters($query): void
    {
        if ($this->yearFilter) {
            $query->whereYear('date', $this->yearFilter);
        }
        if ($this->monthFilter) {
            $query->whereMonth('date', $this->monthFilter);
        }
    }

    public function exportToCsv()
    {
        $userId = Auth::id();

        $expenseQuery = Expense::where('user_id', $userId)->where('type', 'debit')->with('category:id,name');
        $this->applySearch($expenseQuery);
        $this->applyDateFilters($expenseQuery);
        $expenses = $expenseQuery->orderBy('date', 'desc')->orderBy('id', 'desc')->get();

        $creditQuery = Credit::where('user_id', $userId)->with('category:id,name');
        $this->applySearch($creditQuery);
        $this->applyDateFilters($creditQuery);
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

    public function mount()
    {
        $this->yearFilter = date('Y');
        $this->monthFilter = '';
    }

    public function placeholder()
    {
        return view('livewire.placeholders.reports-skeleton');
    }

    public function render()
    {
        $userId = Auth::id();

        $expenseQuery = Expense::where('user_id', $userId)->where('type', 'debit')->with('category:id,name');
        $this->applySearch($expenseQuery);
        $this->applyDateFilters($expenseQuery);

        $creditQuery = Credit::where('user_id', $userId)->with('category:id,name');
        $this->applySearch($creditQuery);
        $this->applyDateFilters($creditQuery);

        $totalDebit = (float) (clone $expenseQuery)->sum('amount');
        $totalCredit = (float) (clone $creditQuery)->sum('amount');

        $allExpenses = (clone $expenseQuery)->get();
        $needs = $allExpenses->where('classification', 'Needs')->sum('amount');
        $wants = $allExpenses->where('classification', 'Wants')->sum('amount');
        $savings = $allExpenses->where('classification', 'Savings')->sum('amount');
        $investments = $allExpenses->where('classification', 'Investments')->sum('amount');
        $unclassified = $totalDebit - ($needs + $wants + $savings + $investments);

        $expenses = $expenseQuery->orderBy('date', 'desc')->orderBy('id', 'desc')->get();
        $credits = $creditQuery->orderBy('date', 'desc')->orderBy('id', 'desc')->get();

        $allRows = collect();
        foreach ($expenses as $e) {
            $allRows->push((object) [
                'date' => $e->date,
                'description' => $e->description ?? 'No description',
                'category_name' => $e->category->name ?? 'Unknown',
                'classification' => $e->classification,
                'type' => 'debit',
                'amount' => (float) $e->amount,
                'currency_symbol' => $e->currency_symbol ?? '₹',
                'sort_at' => $e->created_at->format('Y-m-d H:i:s'),
            ]);
        }
        foreach ($credits as $c) {
            $allRows->push((object) [
                'date' => $c->date,
                'description' => $c->description ?? 'No description',
                'category_name' => $c->category->name ?? 'Unknown',
                'classification' => null,
                'type' => 'credit',
                'amount' => (float) $c->amount,
                'currency_symbol' => $c->currency_symbol ?? '₹',
                'sort_at' => $c->created_at->format('Y-m-d H:i:s'),
            ]);
        }
        $allRows = $allRows->sortByDesc('sort_at')->values();

        $page = max(1, (int) request()->get('page', 1));
        $perPage = 10;
        $transactions = new LengthAwarePaginator(
            $allRows->forPage($page, $perPage)->values(),
            $allRows->count(),
            $perPage,
            $page,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(), 'query' => request()->query()]
        );

        $expenseYears = Expense::where('user_id', $userId)
            ->selectRaw("strftime('%Y', date) as year")
            ->distinct()
            ->pluck('year');
        $creditYears = Credit::where('user_id', $userId)
            ->selectRaw("strftime('%Y', date) as year")
            ->distinct()
            ->pluck('year');
        $years = $expenseYears->merge($creditYears)->unique()->sortDesc()->values();

        return view('livewire.reports', [
            'years' => $years,
            'totalDebit' => $totalDebit,
            'totalCredit' => $totalCredit,
            'needs' => $needs,
            'wants' => $wants,
            'savings' => $savings,
            'investments' => $investments,
            'unclassified' => $unclassified,
            'transactions' => $transactions,
        ]);
    }
}
