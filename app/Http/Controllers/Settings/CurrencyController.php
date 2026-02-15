<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CurrencyController extends Controller
{
    public function edit(): View
    {
        $user = Auth::user();

        return view('pages.settings.currency', [
            'currency' => $user->currency ?? 'INR',
            'currency_symbol' => $user->currency_symbol ?? '₹',
            'availableCurrencies' => config('currencies.available', []),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $currencies = config('currencies.available', []);
        $validated = $request->validate([
            'currency' => 'required|string|max:3|in:' . implode(',', array_keys($currencies)),
        ]);

        $code = $validated['currency'];
        $symbol = $currencies[$code]['symbol'] ?? '₹';

        Auth::user()->update([
            'currency' => $code,
            'currency_symbol' => $symbol,
        ]);

        return redirect()->route('currency.edit')->with('success', 'Currency settings updated successfully');
    }
}
