<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MonthlyTarget;
use Carbon\Carbon;
use Livewire\WithPagination;

class MonthlyTargets extends Component
{
    use WithPagination;

    public $month_year;
    public $total_income = 0;
    public $needs = 0;
    public $wants = 0;
    public $savings = 0;
    public $investments = 0;

    // Percentages
    public $needs_percent = 50;
    public $wants_percent = 20;
    public $savings_percent = 20;
    public $investments_percent = 10;

    public $targetId = null;
    public $isOpen = false;
    public $search = '';

    protected $rules = [
        'month_year' => 'required',
        'total_income' => 'required|numeric|min:0',
        'needs' => 'required|numeric|min:0',
        'wants' => 'required|numeric|min:0',
        'savings' => 'required|numeric|min:0',
        'investments' => 'required|numeric|min:0',
        'needs_percent' => 'required|numeric|min:0|max:100',
        'wants_percent' => 'required|numeric|min:0|max:100',
        'savings_percent' => 'required|numeric|min:0|max:100',
        'investments_percent' => 'required|numeric|min:0|max:100',
    ];

    public $yearFilter = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedYearFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = MonthlyTarget::where('user_id', auth()->id());

        // Get available years for the filter dropdown
        $availableYears = MonthlyTarget::where('user_id', auth()->id())
            ->selectRaw("strftime('%Y', month) as year")
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        $targets = $query
            ->when($this->search, function ($q) {
                $q->where(function ($subQ) {
                    $subQ->where('month', 'like', '%' . $this->search . '%')
                        ->orWhere('total_income', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->yearFilter, function ($q) {
                $q->whereYear('month', $this->yearFilter);
            })
            ->orderBy('month', 'desc')
            ->paginate(10);

        return view('livewire.monthly-targets', [
            'targets' => $targets,
            'availableYears' => $availableYears
        ]);
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    private function resetInputFields()
    {
        $this->month_year = now()->format('Y-m');
        $this->total_income = 0;
        $this->needs = 0;
        $this->wants = 0;
        $this->savings = 0;
        $this->investments = 0;
        $this->targetId = null;

        $this->needs_percent = 50;
        $this->wants_percent = 20;
        $this->savings_percent = 20;
        $this->investments_percent = 10;
    }

    public function edit($id)
    {
        $target = MonthlyTarget::findOrFail($id);
        $this->targetId = $id;
        $this->month_year = Carbon::parse($target->month)->format('Y-m');
        $this->total_income = $target->total_income;
        $this->needs = $target->needs;
        $this->wants = $target->wants;
        $this->savings = $target->savings;
        $this->investments = $target->investments;

        // Recalculate percentages based on loaded values if income > 0
        if ($this->total_income > 0) {
            $this->needs_percent = round(($this->needs / $this->total_income) * 100);
            $this->wants_percent = round(($this->wants / $this->total_income) * 100);
            $this->savings_percent = round(($this->savings / $this->total_income) * 100);
            $this->investments_percent = round(($this->investments / $this->total_income) * 100);
        } else {
            $this->needs_percent = 50;
            $this->wants_percent = 20;
            $this->savings_percent = 20;
            $this->investments_percent = 10;
        }

        $this->openModal();
    }

    public function delete($id)
    {
        MonthlyTarget::find($id)->delete();
        session()->flash('message', 'Target deleted successfully.');
    }

    public function updatedTotalIncome()
    {
        $this->calculateValues();
    }

    public function updatedNeedsPercent()
    {
        $this->calculateValues();
    }
    public function updatedWantsPercent()
    {
        $this->calculateValues();
    }
    public function updatedSavingsPercent()
    {
        $this->calculateValues();
    }
    public function updatedInvestmentsPercent()
    {
        $this->calculateValues();
    }

    public function calculateValues()
    {
        $income = (float) $this->total_income;
        if ($income > 0) {
            $this->needs = number_format($income * ($this->needs_percent / 100), 2, '.', '');
            $this->wants = number_format($income * ($this->wants_percent / 100), 2, '.', '');
            $this->savings = number_format($income * ($this->savings_percent / 100), 2, '.', '');
            $this->investments = number_format($income * ($this->investments_percent / 100), 2, '.', '');
        } else {
            $this->needs = 0;
            $this->wants = 0;
            $this->savings = 0;
            $this->investments = 0;
        }
    }

    public function save()
    {
        $this->validate();

        MonthlyTarget::updateOrCreate(
            ['id' => $this->targetId],
            [
                'user_id' => auth()->id(),
                'month' => $this->month_year . '-01', // Append day to make it Y-m-d
                'total_income' => $this->total_income,
                'needs' => $this->needs,
                'wants' => $this->wants,
                'savings' => $this->savings,
                'investments' => $this->investments,
            ]
        );

        session()->flash('message', 'Targets saved successfully.');
        $this->closeModal();
        $this->resetInputFields();
    }
}
