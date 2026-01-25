<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Expense;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
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
    public $isOpen = false;
    public $expenseId = null;

    protected $rules = [
        'date' => 'required|date',
        'amount' => 'required|numeric|min:0.01',
        'description' => 'nullable|string|max:255',
        'type' => 'required|in:credit,debit',
        'classification' => 'nullable|in:Needs,Wants,Savings,Investments',
        'category_id' => 'required|exists:categories,id',
    ];

    public function mount()
    {
        $this->date = date('Y-m-d');
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

        if ($id) {
            $expense = Expense::where('user_id', Auth::id())->findOrFail($id);
            $this->expenseId = $expense->id;
            $this->date = $expense->date->format('Y-m-d');
            $this->amount = $expense->amount;
            $this->description = $expense->description;
            $this->type = $expense->type;
            $this->classification = $expense->classification;
            $this->category_id = $expense->category_id;
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
            'classification' => $this->type === 'debit' ? $this->classification : null, // Only save classification for debits ideally, but user didn't specify. I'll save it if set. actually user said "Needs, Wants..." are types.
            'category_id' => $this->category_id,
        ];

        if ($this->expenseId) {
            Expense::where('user_id', Auth::id())->findOrFail($this->expenseId)->update($data);
        } else {
            Expense::create($data);
        }

        $this->closeModal();
        $this->dispatch('expense-saved');
    }

    public function delete($id)
    {
        Expense::where('user_id', Auth::id())->findOrFail($id)->delete();
        $this->dispatch('expense-deleted');
    }

    public $search = '';
    public $categoryFilter = '';
    public $typeFilter = '';

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

    public function render()
    {
        $expenses = Expense::where('user_id', Auth::id())
            ->with(['category'])
            ->when($this->search, function ($query) {
                $query->where('description', 'like', '%' . $this->search . '%')
                    ->orWhereHas('category', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    });
            })
            ->when($this->categoryFilter, function ($query) {
                $query->where('category_id', $this->categoryFilter);
            })
            ->when($this->typeFilter, function ($query) {
                $query->where('type', $this->typeFilter);
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
