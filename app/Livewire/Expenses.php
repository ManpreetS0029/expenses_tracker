<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Expense;
use App\Support\DateRangeHelper;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Component;
use Livewire\WithPagination;

#[Lazy]
#[Layout('layouts.app')]
class Expenses extends Component
{
    use WithPagination;

    public $date;

    public $amount;

    public $description;

    public $classification = 'Needs'; // default

    public $category_id;

    public $currency;

    public $currency_symbol;

    public $isOpen = false;

    public $expenseId = null;

    public $availableCurrencies = [
        'INR' => ['name' => 'Indian Rupee', 'symbol' => '₹'],
        'USD' => ['name' => 'US Dollar', 'symbol' => '$'],
        'EUR' => ['name' => 'Euro', 'symbol' => '€'],
        'GBP' => ['name' => 'British Pound', 'symbol' => '£'],
        'JPY' => ['name' => 'Japanese Yen', 'symbol' => '¥'],
        'AUD' => ['name' => 'Australian Dollar', 'symbol' => 'A$'],
        'CAD' => ['name' => 'Canadian Dollar', 'symbol' => 'C$'],
        'CHF' => ['name' => 'Swiss Franc', 'symbol' => 'CHF'],
        'CNY' => ['name' => 'Chinese Yuan', 'symbol' => '¥'],
        'AED' => ['name' => 'UAE Dirham', 'symbol' => 'د.إ'],
    ];

    protected $rules = [
        'date' => 'required|date',
        'amount' => 'required|numeric|min:0.01',
        'description' => 'nullable|string|max:255',
        'classification' => 'nullable|in:Needs,Wants',
        'category_id' => 'required|exists:categories,id',
        'currency' => 'required|string|max:3',
        'currency_symbol' => 'required|string|max:10',
    ];

    public function mount()
    {
        $this->date = date('Y-m-d');
        $user = Auth::user();
        $this->currency = $user->currency ?? 'INR';
        $this->currency_symbol = $user->currency_symbol ?? '₹';
    }

    public function updatedCurrency($value)
    {
        if (isset($this->availableCurrencies[$value])) {
            $this->currency_symbol = $this->availableCurrencies[$value]['symbol'];
        }
    }

    public function placeholder()
    {
        return view('livewire.placeholders.skeleton');
    }

    public function openModal($id = null)
    {
        $this->resetValidation();
        $this->reset(['amount', 'description', 'classification', 'category_id', 'expenseId']);
        $this->date = date('Y-m-d');
        $user = Auth::user();
        $this->currency = $user->currency ?? 'INR';
        $this->currency_symbol = $user->currency_symbol ?? '₹';

        if ($id) {
            $expense = Expense::where('user_id', Auth::id())->where('type', 'debit')->findOrFail($id);
            $this->expenseId = $expense->id;
            $this->date = $expense->date->format('Y-m-d');
            $this->amount = $expense->amount;
            $this->description = $expense->description;
            $this->classification = in_array($expense->classification, ['Needs', 'Wants'], true)
                ? $expense->classification
                : 'Needs';
            $this->category_id = $expense->category_id;
            $this->currency = $expense->currency ?? $user->currency;
            $this->currency_symbol = $expense->currency_symbol ?? $user->currency_symbol;
        }

        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->reset(['amount', 'description', 'classification', 'category_id', 'expenseId']);
    }

    public function save()
    {
        $this->validate();

        $data = [
            'user_id' => Auth::id(),
            'date' => $this->date,
            'amount' => $this->amount,
            'description' => $this->description,
            'type' => 'debit',
            'classification' => $this->classification,
            'category_id' => $this->category_id,
            'currency' => $this->currency,
            'currency_symbol' => $this->currency_symbol,
        ];

        if ($this->expenseId) {
            Expense::where('user_id', Auth::id())->findOrFail($this->expenseId)->update($data);
            $message = 'Expense updated successfully';
        } else {
            Expense::create($data);
            $message = 'Expense created successfully';
        }

        $this->closeModal();
        $this->dispatch('alert-success', ['message' => $message]);
    }

    public function delete($id)
    {
        Expense::where('user_id', Auth::id())->where('type', 'debit')->findOrFail($id)->delete();
        $this->dispatch('alert-success', ['message' => 'Expense deleted successfully']);
    }

    public $search = '';

    public $categoryFilter = '';

    public $classificationFilter = '';

    public $periodFilter = '';

    public $dateFrom = '';

    public $dateTo = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedCategoryFilter()
    {
        $this->resetPage();
    }

    public function updatedPeriodFilter()
    {
        $this->resetPage();
    }

    public function updatedClassificationFilter()
    {
        $this->resetPage();
    }

    public function updatedDateFrom()
    {
        $this->resetPage();
    }

    public function updatedDateTo()
    {
        $this->resetPage();
    }

    public function render()
    {
        $expenses = Expense::where('user_id', Auth::id())
            ->where('type', 'debit')
            ->with(['category'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('description', 'like', '%'.$this->search.'%')
                        ->orWhereHas('category', function ($catQ) {
                            $catQ->where('name', 'like', '%'.$this->search.'%');
                        });
                });
            })
            ->when($this->categoryFilter, function ($query) {
                $query->where('category_id', $this->categoryFilter);
            })
            ->when($this->classificationFilter, function ($query) {
                $query->where('classification', $this->classificationFilter);
            })
            ->when($this->periodFilter && $this->periodFilter !== 'custom', function ($query) {
                [$start, $end] = DateRangeHelper::rangeForPeriod($this->periodFilter);
                $query->whereBetween('date', [$start, $end]);
            })
            ->when($this->periodFilter === 'custom', function ($query) {
                if ($this->dateFrom) {
                    $query->where('date', '>=', $this->dateFrom);
                }
                if ($this->dateTo) {
                    $query->where('date', '<=', $this->dateTo);
                }
            })
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(10);

        $categories = Category::where('user_id', Auth::id())
            ->get();

        $periodOptions = array_merge(['' => 'All time'], DateRangeHelper::periodLabels(), ['custom' => 'Custom date range']);

        return view('livewire.expenses', [
            'expenses' => $expenses,
            'categories' => $categories,
            'periodOptions' => $periodOptions,
        ]);
    }
}
