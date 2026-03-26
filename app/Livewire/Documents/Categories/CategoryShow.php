<?php

namespace App\Livewire\Documents\Categories;

use App\Models\DocumentCategory;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class CategoryShow extends Component
{
    use Toast, FlashToast;

    #[Locked]
    public int $categoryId;

    public function mount(int $categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    public function render(): View
    {
        return view('livewire.documents.categories.category-show', [
            'category' => $this->category()
        ]);
    }

    public function toggleActive(): void
    {
        if (cannot('categories.toggle')) {
            $this->toastError('No tienes permiso para realizar esta acción');
            return;
        }

        $this->toastSuccess(
            $this->category()->toggleActive()
                ? 'Categoría activada'
                : 'Categoría desactivada'
        );
    }

    public function delete(): void
    {
        if (cannot('categories.delete')) {
            $this->toastError('No tienes permiso para realizar esta acción');
            return;
        }

        $category = $this->category();

        if ($category->isInUse()) {
            $this->toastError(
                'No se puede eliminar: la categoría está en uso'
            );
        } else {
            $category->delete();
            $this->flashToastSuccess('Categoría eliminada');
            redirect()->route('document-categories.index');
        }
    }

    private ?DocumentCategory $category = null;

    private function category(): DocumentCategory
    {
        return $this->category ??= DocumentCategory::findOrFail($this->categoryId);
    }
}
