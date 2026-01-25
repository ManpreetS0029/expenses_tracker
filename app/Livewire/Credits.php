<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Credit;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Component;
use Livewire\WithPagination;

#[Lazy]
#[Layout('layouts.app')]
class Credits extends Component
{
    use WithPagination;

    public $date;

    public $amount;

    public $description;

    public $category_id;

    public $currency;

    public $currency_symbol;

    public $isOpen = false;

    public $creditId = null;

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
        'category_id' => 'required|exists:categories,id',
        'currency' => 'required|string|max:3',
        'currency_symbol' => 'required|string|max:10',
    ];

    public $search = '';

    public $categoryFilter = '';

    public $monthFilter = '';

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
        $this->reset(['amount', 'description', 'category_id', 'creditId']);
        $this->date = date('Y-m-d');
        $user = Auth::user();
        $this->currency = $user->currency ?? 'INR';
        $this->currency_symbol = $user->currency_symbol ?? '₹';

        if ($id) {
            $credit = Credit::where('user_id', Auth::id())->findOrFail($id);
            $this->creditId = $credit->id;
            $this->date = $credit->date->format('Y-m-d');
            $this->amount = $credit->amount;
            $this->description = $credit->description;
            $this->category_id = $credit->category_id;
            $this->currency = $credit->currency ?? $user->currency;
            $this->currency_symbol = $credit->currency_symbol ?? $user->currency_symbol;
        }

        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->reset(['amount', 'description', 'category_id', 'creditId']);
    }

    public function save()
    {
        $this->validate();

        $data = [
            'user_id' => Auth::id(),
            'date' => $this->date,
            'amount' => $this->amount,
            'description' => $this->description,
            'category_id' => $this->category_id,
            'currency' => $this->currency,
            'currency_symbol' => $this->currency_symbol,
        ];

        if ($this->creditId) {
            Credit::where('user_id', Auth::id())->findOrFail($this->creditId)->update($data);
            $message = 'Credit updated successfully';
        } else {
            Credit::create($data);
            $message = 'Credit created successfully';
        }

        $this->closeModal();
        $this->dispatch('alert-success', ['message' => $message]);
    }

    public function delete($id)
    {
        Credit::where('user_id', Auth::id())->findOrFail($id)->delete();
        $this->dispatch('alert-success', ['message' => 'Credit deleted successfully']);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedCategoryFilter()
    {
        $this->resetPage();
    }

    public function updatedMonthFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $credits = Credit::where('user_id', Auth::id())
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
            ->when($this->monthFilter, function ($query) {
                $query->whereMonth('date', $this->monthFilter);
            })
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(10);

        $categories = Category::where('user_id', Auth::id())->get();

        return view('livewire.credits', [
            'credits' => $credits,
            'categories' => $categories,
        ]);
    }
}
