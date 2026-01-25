<?php

namespace App\Livewire;

use App\Models\Expense;
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

    public function exportToCsv()
    {
        $query = Expense::where('user_id', Auth::id());

        // Apply Search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('description', 'like', '%'.$this->search.'%')
                    ->orWhereHas('category', function ($catQ) {
                        $catQ->where('name', 'like', '%'.$this->search.'%');
                    });
            });
        }

        // Apply Date Filters
        if ($this->yearFilter) {
            $query->whereYear('date', $this->yearFilter);
        }
        if ($this->monthFilter) {
            $query->whereMonth('date', $this->monthFilter);
        }

        $expenses = $query->orderBy('date', 'desc')->with('category:id,name')->get();

        $filename = 'expenses_report_'.date('Y-m-d_His').'.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($expenses) {
            $file = fopen('php://output', 'w');

            // Add BOM for proper Excel UTF-8 handling
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Add CSV headers
            fputcsv($file, ['Date', 'Description', 'Category', 'Classification', 'Type', 'Amount (₹)']);

            // Add data rows
            foreach ($expenses as $expense) {
                fputcsv($file, [
                    $expense->date->format('Y-m-d'),
                    $expense->description ?: 'No description',
                    $expense->category->name ?? 'Unknown',
                    $expense->classification ?? '-',
                    ucfirst($expense->type),
                    number_format($expense->amount, 2, '.', ''),
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
        $query = Expense::where('user_id', Auth::id());

        // Apply Search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('description', 'like', '%'.$this->search.'%')
                    ->orWhereHas('category', function ($catQ) {
                        $catQ->where('name', 'like', '%'.$this->search.'%');
                    });
            });
        }

        // Apply Date Filters
        if ($this->yearFilter) {
            $query->whereYear('date', $this->yearFilter);
        }
        if ($this->monthFilter) {
            $query->whereMonth('date', $this->monthFilter);
        }

        // Get Available Years for Filter
        $years = Expense::where('user_id', Auth::id())
            ->selectRaw("strftime('%Y', date) as year")
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // Clone query for totals calculation (we need all records, not just paginated page)
        $allExpenses = $query->clone()->get();

        // Calculate Totals form all matching records
        $totalDebit = $allExpenses->where('type', 'debit')->sum('amount');
        $totalCredit = $allExpenses->where('type', 'credit')->sum('amount');

        // breakdown by classification
        $needs = $allExpenses->where('type', 'debit')->where('classification', 'Needs')->sum('amount');
        $wants = $allExpenses->where('type', 'debit')->where('classification', 'Wants')->sum('amount');
        $savings = $allExpenses->where('type', 'debit')->where('classification', 'Savings')->sum('amount');
        $investments = $allExpenses->where('type', 'debit')->where('classification', 'Investments')->sum('amount');

        $unclassified = $totalDebit - ($needs + $wants + $savings + $investments);

        // Get Paginated Expenses for Listing
        $expenses = $query->orderBy('date', 'desc')->paginate(10);

        return view('livewire.reports', [
            'years' => $years,
            'totalDebit' => $totalDebit,
            'totalCredit' => $totalCredit,
            'needs' => $needs,
            'wants' => $wants,
            'savings' => $savings,
            'investments' => $investments,
            'unclassified' => $unclassified,
            'expenses' => $expenses,
        ]);
    }
}
