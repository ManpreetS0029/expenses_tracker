<?php

namespace App\Livewire\Settings;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class CurrencySettings extends Component
{
    public $currency;

    public $currency_symbol;

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

    public function mount()
    {
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

    public function save()
    {
        $user = Auth::user();
        $user->update([
            'currency' => $this->currency,
            'currency_symbol' => $this->currency_symbol,
        ]);

        $this->dispatch('alert-success', ['message' => 'Currency settings updated successfully']);
    }

    public function render()
    {
        return view('livewire.settings.currency-settings');
    }
}
