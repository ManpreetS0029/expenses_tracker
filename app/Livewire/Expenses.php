<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Expense;
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

    public $type = 'debit'; // default to debit

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
        'type' => 'required|in:credit,debit',
        'classification' => 'nullable|in:Needs,Wants,Savings,Investments',
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
        $this->reset(['amount', 'description', 'type', 'classification', 'category_id', 'expenseId']);
        $this->date = date('Y-m-d');
        $user = Auth::user();
        $this->currency = $user->currency ?? 'INR';
        $this->currency_symbol = $user->currency_symbol ?? '₹';

        if ($id) {
            $expense = Expense::where('user_id', Auth::id())->findOrFail($id);
            $this->expenseId = $expense->id;
            $this->date = $expense->date->format('Y-m-d');
            $this->amount = $expense->amount;
            $this->description = $expense->description;
            $this->type = $expense->type;
            $this->classification = $expense->classification;
            $this->category_id = $expense->category_id;
            $this->currency = $expense->currency ?? $user->currency;
            $this->currency_symbol = $expense->currency_symbol ?? $user->currency_symbol;
        }

        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->reset(['amount', 'description', 'type', 'classification', 'category_id', 'expenseId']);
    }

    public function save()
    {
        $this->validate();

        $data = [
            'user_id' => Auth::id(),
            'date' => $this->date,
            'amount' => $this->amount,
            'description' => $this->description,
            'type' => $this->type,
            'classification' => $this->type === 'debit' ? $this->classification : null,
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
        Expense::where('user_id', Auth::id())->findOrFail($id)->delete();
        $this->dispatch('alert-success', ['message' => 'Expense deleted successfully']);
    }

    public $search = '';

    public $categoryFilter = '';

    public $typeFilter = '';

    public $monthFilter = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedCategoryFilter()
    {
        $this->resetPage();
    }

    public function updatedTypeFilter()
    {
        $this->resetPage();
    }

    public function updatedMonthFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $expenses = Expense::where('user_id', Auth::id())
            ->with(['category'])
            ->when($this->search, function ($query) {
                $query->where('description', 'like', '%'.$this->search.'%')
                    ->orWhereHas('category', function ($q) {
                        $q->where('name', 'like', '%'.$this->search.'%');
                    });
            })
            ->when($this->categoryFilter, function ($query) {
                $query->where('category_id', $this->categoryFilter);
            })
            ->when($this->typeFilter, function ($query) {
                $query->where('type', $this->typeFilter);
            })
            ->when($this->monthFilter, function ($query) {
                $query->whereMonth('date', $this->monthFilter);
            })
            ->orderBy('date', 'desc')
            ->paginate(10);

        $categories = Category::where('user_id', Auth::id())
            ->get();

        return view('livewire.expenses', [
            'expenses' => $expenses,
            'categories' => $categories,
        ]);
    }
}
