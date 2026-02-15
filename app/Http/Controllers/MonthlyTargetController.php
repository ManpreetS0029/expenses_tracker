<?php

namespace App\Http\Controllers;

use App\Models\MonthlyTarget;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MonthlyTargetController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->get('search', '');
        $yearFilter = $request->get('year', '');

        $query = MonthlyTarget::where('user_id', Auth::id())
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($subQ) use ($search) {
                    $subQ->where('month', 'like', '%'.$search.'%')
                        ->orWhere('total_income', 'like', '%'.$search.'%');
                });
            })
            ->when($yearFilter !== '', fn ($q) => $q->whereYear('month', $yearFilter))
            ->orderBy('month', 'desc');

        $availableYears = MonthlyTarget::where('user_id', Auth::id())
            ->selectRaw("strftime('%Y', month) as year")
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        $targets = $query->paginate(10)->withQueryString();

        $editingTarget = null;
        if ($request->has('edit')) {
            $editingTarget = MonthlyTarget::where('user_id', Auth::id())->find($request->get('edit'));
        }

        return view('pages.monthly-targets', [
            'targets' => $targets,
            'availableYears' => $availableYears,
            'editingTarget' => $editingTarget,
            'search' => $search,
            'yearFilter' => $yearFilter,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateTarget($request);

        MonthlyTarget::create([
            'user_id' => Auth::id(),
            'month' => $validated['month_year'].'-01',
            'total_income' => $validated['total_income'],
            'needs' => $validated['needs'],
            'wants' => $validated['wants'],
            'savings' => $validated['savings'],
            'investments' => $validated['investments'],
        ]);

        return redirect()->route('monthly-targets')->with('success', 'Target created successfully');
    }

    public function update(Request $request, MonthlyTarget $monthlyTarget): RedirectResponse
    {
        if ($monthlyTarget->user_id !== Auth::id()) {
            abort(404);
        }

        $validated = $this->validateTarget($request);

        $monthlyTarget->update([
            'month' => $validated['month_year'].'-01',
            'total_income' => $validated['total_income'],
            'needs' => $validated['needs'],
            'wants' => $validated['wants'],
            'savings' => $validated['savings'],
            'investments' => $validated['investments'],
        ]);

        return redirect()->route('monthly-targets')->with('success', 'Target updated successfully');
    }

    public function destroy(MonthlyTarget $monthlyTarget): RedirectResponse
    {
        if ($monthlyTarget->user_id !== Auth::id()) {
            abort(404);
        }
        $monthlyTarget->delete();

        return redirect()->route('monthly-targets')->with('success', 'Target deleted successfully');
    }

    /**
     * @return array<string, mixed>
     */
    private function validateTarget(Request $request): array
    {
        return $request->validate([
            'month_year' => 'required|string',
            'total_income' => 'required|numeric|min:0',
            'needs' => 'required|numeric|min:0',
            'wants' => 'required|numeric|min:0',
            'savings' => 'required|numeric|min:0',
            'investments' => 'required|numeric|min:0',
        ]);
    }
}
