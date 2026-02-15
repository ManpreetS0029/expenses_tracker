<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Expense;
use App\Support\DateRangeHelper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->get('search', '');
        $categoryFilter = $request->get('category', '');
        $classificationFilter = $request->get('classification', '');
        $periodFilter = $request->get('period', '');
        $dateFrom = $request->get('date_from', '');
        $dateTo = $request->get('date_to', '');

        $baseQuery = Expense::where('user_id', Auth::id())
            ->where('type', 'debit')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('description', 'like', '%' . $search . '%')
                        ->orWhereHas('category', function ($catQ) use ($search) {
                            $catQ->where('name', 'like', '%' . $search . '%');
                        });
                });
            })
            ->when($categoryFilter !== '', fn($query) => $query->where('category_id', $categoryFilter))
            ->when($classificationFilter !== '', fn($query) => $query->where('classification', $classificationFilter))
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
            });

        // Compute classification totals from the same filtered query
        $classificationTotals = (clone $baseQuery)
            ->selectRaw('classification, SUM(amount) as total')
            ->groupBy('classification')
            ->pluck('total', 'classification');

        $totalNeeds = (float) ($classificationTotals['Needs'] ?? 0);
        $totalWants = (float) ($classificationTotals['Wants'] ?? 0);
        $totalAmount = (float) (clone $baseQuery)->sum('amount');

        $expenses = (clone $baseQuery)
            ->with(['category'])
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        $categories = Category::getCachedForUser(Auth::id());
        $periodOptions = array_merge(['' => 'All time'], DateRangeHelper::periodLabels(), ['custom' => 'Custom date range']);
        $availableCurrencies = config('currencies.available', []);
        $user = Auth::user();

        $editingExpense = null;
        if ($request->has('edit')) {
            $editingExpense = Expense::where('user_id', Auth::id())->where('type', 'debit')->find($request->get('edit'));
        }

        return view('pages.expenses', [
            'expenses' => $expenses,
            'categories' => $categories,
            'periodOptions' => $periodOptions,
            'availableCurrencies' => $availableCurrencies,
            'defaultCurrency' => $user->currency ?? 'INR',
            'defaultCurrencySymbol' => $user->currency_symbol ?? '₹',
            'editingExpense' => $editingExpense,
            'search' => $search,
            'categoryFilter' => $categoryFilter,
            'classificationFilter' => $classificationFilter,
            'periodFilter' => $periodFilter,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'totalNeeds' => $totalNeeds,
            'totalWants' => $totalWants,
            'totalAmount' => $totalAmount,
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
            'classification' => 'nullable|in:Needs,Wants',
            'category_id' => 'required|exists:categories,id',
            'currency' => 'required|string|max:3',
            'currency_symbol' => 'required|string|max:10',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['type'] = 'debit';
        $validated['classification'] = $validated['classification'] ?? 'Needs';

        Expense::create($validated);

        return redirect()->route('expenses')->with('success', 'Expense created successfully');
    }

    public function update(Request $request, Expense $expense): RedirectResponse
    {
        $this->authorizeExpense($expense);
        $currencies = config('currencies.available', []);
        $request->merge(['currency_symbol' => $currencies[$request->input('currency')]['symbol'] ?? '₹']);
        $validated = $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
            'classification' => 'nullable|in:Needs,Wants',
            'category_id' => 'required|exists:categories,id',
            'currency' => 'required|string|max:3',
            'currency_symbol' => 'required|string|max:10',
        ]);

        $validated['type'] = 'debit';
        $validated['classification'] = $validated['classification'] ?? 'Needs';
        $expense->update($validated);

        return redirect()->route('expenses')->with('success', 'Expense updated successfully');
    }

    public function destroy(Expense $expense): RedirectResponse
    {
        $this->authorizeExpense($expense);
        $expense->delete();

        return redirect()->route('expenses')->with('success', 'Expense deleted successfully');
    }

    private function authorizeExpense(Expense $expense): void
    {
        if ($expense->user_id !== Auth::id() || $expense->type !== 'debit') {
            abort(404);
        }
    }
}
