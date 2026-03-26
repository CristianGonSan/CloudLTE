<?php

namespace App\Livewire\Documents\Categories;

use App\Models\DocumentCategory;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;

use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;

class CategoryEdit extends Component
{
    use Toast, FlashToast;

    public int $categoryId;

    public string $name;

    public function mount(int $categoryId): void
    {
        $this->categoryId   = $categoryId;

        $category           = $this->category();

        $this->name         = $category->name;
    }

    public function render(): View
    {
        return view('livewire.documents.categories.category-edit');
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name'          => ['required', 'string', 'max:64', Rule::unique('document_categories')->ignore($this->categoryId)],
        ]);

        $this->category()->update($validated);

        $this->toastSuccess('Categoria actualizada');
    }

    private ?DocumentCategory $category = null;

    private function category(): DocumentCategory
    {
        return $this->category ??= DocumentCategory::findOrFail($this->categoryId);
    }
}
