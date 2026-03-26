<?php

namespace App\Livewire\Documents\Categories;

use App\Models\DocumentCategory;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;

use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;

class CategoryCreate extends Component
{
    use Toast, FlashToast;

    public string $name;

    public bool $createAnother = false;

    public function render(): View
    {
        return view('livewire.documents.categories.category-create');
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:64', Rule::unique('document_categories')],
        ]);

        $category = DocumentCategory::create($validated);

        if ($this->createAnother) {
            $this->reset([
                'name',
            ]);
            $this->toastSuccess('Categoría creada');
        } else {
            $this->flashToastSuccess('Categoría creada');
            redirect()->route('document-categories.show', $category->id);
        }
    }
}
