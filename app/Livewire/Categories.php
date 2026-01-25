<?php

namespace App\Livewire;

use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
#[Layout('layouts.app')]
class Categories extends Component
{
    use \Livewire\WithPagination;

    public function placeholder()
    {
        return view('livewire.placeholders.skeleton');
    }

    public $name = '';

    public $categoryId = null;

    public $isOpen = false;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
        ];
    }

    public function openModal($id = null)
    {
        $this->reset(['name', 'categoryId']);

        if ($id) {
            $category = Category::where('user_id', Auth::id())->findOrFail($id);
            $this->categoryId = $category->id;
            $this->name = $category->name;
        }

        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->reset(['name', 'categoryId']);
    }

    public function save()
    {
        $this->validate();

        if ($this->categoryId) {
            $category = Category::where('user_id', Auth::id())->findOrFail($this->categoryId);
            $category->update([
                'name' => $this->name,
            ]);
            $message = 'Category updated successfully';
        } else {
            Category::create([
                'user_id' => Auth::id(),
                'name' => $this->name,
            ]);
            $message = 'Category created successfully';
        }

        $this->closeModal();
        $this->dispatch('alert-success', ['message' => $message]);
    }

    public function delete($id)
    {
        Category::where('user_id', Auth::id())->findOrFail($id)->delete();
        $this->dispatch('alert-success', ['message' => 'Category deleted successfully']);
    }

    public $search = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $categories = Category::where('user_id', Auth::id())
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%');
            })
            ->orderBy('name', 'asc')
            ->paginate(10);

        return view('livewire.categories', [
            'categories' => $categories,
        ]);
    }
}
