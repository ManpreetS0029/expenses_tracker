<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->get('search', '');
        $categories = Category::where('user_id', Auth::id())
            ->when($search !== '', function ($query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%');
            })
            ->orderBy('name', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('pages.categories', [
            'categories' => $categories,
            'search' => $search,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Category::create([
            'user_id' => Auth::id(),
            'name' => $validated['name'],
        ]);

        return redirect()->route('categories')->with('success', 'Category created successfully');
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $this->authorizeCategory($category);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category->update(['name' => $validated['name']]);

        return redirect()->route('categories')->with('success', 'Category updated successfully');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $this->authorizeCategory($category);
        $category->delete();

        return redirect()->route('categories')->with('success', 'Category deleted successfully');
    }

    private function authorizeCategory(Category $category): void
    {
        if ($category->user_id !== Auth::id()) {
            abort(404);
        }
    }
}
