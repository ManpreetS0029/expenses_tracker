<?php

namespace App\Livewire;

use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
#[Layout('layouts.app')]
class SubCategories extends Component
{
    public function placeholder()
    {
        return view('livewire.placeholders.skeleton');
    }

    public $name = '';

    public $parent_id = '';

    public $categoryId = null;

    public $isOpen = false;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'parent_id' => 'required|exists:categories,id',
        ];
    }

    public function openModal($id = null)
    {
        $this->reset(['name', 'parent_id', 'categoryId']);

        if ($id) {
            $category = Category::where('user_id', Auth::id())->findOrFail($id);
            $this->categoryId = $category->id;
            $this->name = $category->name;
            $this->parent_id = $category->parent_id;
        }

        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->reset(['name', 'parent_id', 'categoryId']);
    }

    public function save()
    {
        $this->validate();

        if ($this->categoryId) {
            $category = Category::where('user_id', Auth::id())->findOrFail($this->categoryId);
            $category->update([
                'name' => $this->name,
                'parent_id' => $this->parent_id,
            ]);
            $message = 'Sub-category updated successfully';
        } else {
            Category::create([
                'user_id' => Auth::id(),
                'name' => $this->name,
                'parent_id' => $this->parent_id,
            ]);
            $message = 'Sub-category created successfully';
        }

        $this->closeModal();
        $this->dispatch('alert-success', ['message' => $message]);
    }

    public function delete($id)
    {
        Category::where('user_id', Auth::id())->findOrFail($id)->delete();
        $this->dispatch('alert-success', ['message' => 'Sub-category deleted successfully']);
    }

    public function render()
    {
        $subCategories = Category::where('user_id', Auth::id())
            ->whereNotNull('parent_id')
            ->with('parent')
            ->get();

        $parentCategories = Category::where('user_id', Auth::id())
            ->whereNull('parent_id')
            ->get();

        return view('livewire.sub-categories', [
            'subCategories' => $subCategories,
            'parentCategories' => $parentCategories,
        ]);
    }
}
