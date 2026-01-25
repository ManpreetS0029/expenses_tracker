<?php

namespace App\Livewire;

use App\Models\Expense;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;

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


    public function mount()
    {
        $this->yearFilter = date('Y');
        $this->monthFilter = '';
    }

    public function render()
    {
        $query = Expense::where('user_id', Auth::id());

        // Apply Search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('description', 'like', '%' . $this->search . '%')
                    ->orWhereHas('category', function ($catQ) {
                        $catQ->where('name', 'like', '%' . $this->search . '%');
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
