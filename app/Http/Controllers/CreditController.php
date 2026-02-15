<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Credit;
use App\Support\DateRangeHelper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CreditController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->get('search', '');
        $categoryFilter = $request->get('category', '');
        $periodFilter = $request->get('period', '');
        $dateFrom = $request->get('date_from', '');
        $dateTo = $request->get('date_to', '');

        $credits = Credit::where('user_id', Auth::id())
            ->with(['category'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('description', 'like', '%'.$search.'%')
                        ->orWhereHas('category', function ($catQ) use ($search) {
                            $catQ->where('name', 'like', '%'.$search.'%');
                        });
                });
            })
            ->when($categoryFilter !== '', fn ($query) => $query->where('category_id', $categoryFilter))
            ->when($periodFilter !== '' && $periodFilter !== 'custom', function ($query) use ($periodFilter) {
                [$start, $end] = DateRangeHelper::rangeForPeriod($periodFilter);
                $query->whereBetween('date', [$start, $end]);
            })
            ->when($periodFilter === 'custom', function ($query) use ($dateFrom, $dateTo) {
                if ($dateFrom !== '') {
                    $query->where('date', '>=', $dateFrom);
                }
                if ($dateTo !== '') {
                    $query->where('date', '<=', $dateTo);
                }
            })
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        $categories = Category::getCachedForUser(Auth::id());
        $periodOptions = array_merge(['' => 'All time'], DateRangeHelper::periodLabels(), ['custom' => 'Custom date range']);
        $availableCurrencies = config('currencies.available', []);
        $user = Auth::user();

        $editingCredit = null;
        if ($request->has('edit')) {
            $editingCredit = Credit::where('user_id', Auth::id())->find($request->get('edit'));
        }

        return view('pages.credits', [
            'credits' => $credits,
            'categories' => $categories,
            'periodOptions' => $periodOptions,
            'availableCurrencies' => $availableCurrencies,
            'defaultCurrency' => $user->currency ?? 'INR',
            'defaultCurrencySymbol' => $user->currency_symbol ?? '₹',
            'editingCredit' => $editingCredit,
            'search' => $search,
            'categoryFilter' => $categoryFilter,
            'periodFilter' => $periodFilter,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $currencies = config('currencies.available', []);
        $request->merge(['currency_symbol' => $currencies[$request->input('currency')]['symbol'] ?? '₹']);
        $validated = $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'currency' => 'required|string|max:3',
            'currency_symbol' => 'required|string|max:10',
        ]);

        $validated['user_id'] = Auth::id();
        Credit::create($validated);

        return redirect()->route('credits')->with('success', 'Credit created successfully');
    }

    public function update(Request $request, Credit $credit): RedirectResponse
    {
        $this->authorizeCredit($credit);
        $currencies = config('currencies.available', []);
        $request->merge(['currency_symbol' => $currencies[$request->input('currency')]['symbol'] ?? '₹']);
        $validated = $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'currency' => 'required|string|max:3',
            'currency_symbol' => 'required|string|max:10',
        ]);

        $credit->update($validated);

        return redirect()->route('credits')->with('success', 'Credit updated successfully');
    }

    public function destroy(Credit $credit): RedirectResponse
    {
        $this->authorizeCredit($credit);
        $credit->delete();

        return redirect()->route('credits')->with('success', 'Credit deleted successfully');
    }

    private function authorizeCredit(Credit $credit): void
    {
        if ($credit->user_id !== Auth::id()) {
            abort(404);
        }
    }
}
