<?php

namespace App\Livewire\Documents;

use App\Models\DocumentCategory;
use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class DocumentCategories extends Component
{
    use Toast, WithPagination;

    #[Rule(['required', 'string', 'max:255', 'unique:document_categories,name'])]
    public string $name = '';

    #[Rule(['required', 'boolean'])]
    public bool $is_active = true;

    public string $search = '';

    public ?int $editingId = null;

    public ?int $confirmingDeleteId = null;

    public string $confirmingDeleteName = '';

    /**
     * Resetea la pagina al cambiar el termino de busqueda.
     */
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Abre el modal de creacion con el formulario limpio.
     */
    public function openCreate(): void
    {
        $this->resetForm();
        $this->dispatch('open-modal', id: 'category-form-modal');
    }

    /**
     * Carga los datos de una categoria en el formulario y abre el modal de edicion.
     */
    public function openEdit(int $id): void
    {
        $category = DocumentCategory::findOrFail($id);

        $this->editingId  = $category->id;
        $this->name       = $category->name;
        $this->is_active  = $category->is_active;

        $this->resetValidation();
        $this->dispatch('open-modal', id: 'category-form-modal');
    }

    /**
     * Guarda o actualiza una categoria segun si se esta editando o creando.
     */
    public function save(): void
    {
        $uniqueRule = $this->editingId
            ? "unique:document_categories,name,{$this->editingId}"
            : 'unique:document_categories,name';

        $this->validate([
            'name'      => ['required', 'string', 'max:255', $uniqueRule],
            'is_active' => ['required', 'boolean'],
        ]);

        if ($this->editingId !== null) {
            $category = DocumentCategory::findOrFail($this->editingId);
            $category->update([
                'name'      => $this->name,
                'is_active' => $this->is_active,
            ]);

            $this->toastSuccess('La categoria fue actualizada correctamente.', 'Actualizado');
        } else {
            DocumentCategory::create([
                'name'      => $this->name,
                'is_active' => $this->is_active,
            ]);

            $this->toastSuccess('La categoria fue creada correctamente.', 'Creado');
        }

        $this->resetForm();
        $this->dispatch('close-modal', id: 'category-form-modal');
    }

    /**
     * Muestra el modal de confirmacion antes de eliminar.
     */
    public function confirmDelete(int $id): void
    {
        $category = DocumentCategory::findOrFail($id);

        if ($category->isInUse()) {
            $this->toastWarning('No se puede eliminar una categoria que esta en uso.', 'Advertencia');
            return;
        }

        $this->confirmingDeleteId   = $category->id;
        $this->confirmingDeleteName = $category->name;

        $this->dispatch('open-modal', id: 'category-delete-modal');
    }

    /**
     * Elimina la categoria confirmada.
     */
    public function delete(): void
    {
        if ($this->confirmingDeleteId === null) {
            return;
        }

        $category = DocumentCategory::findOrFail($this->confirmingDeleteId);

        if ($category->isInUse()) {
            $this->toastWarning('No se puede eliminar una categoria que esta en uso.', 'Advertencia');
            $this->dispatch('close-modal', id: 'category-delete-modal');
            return;
        }

        $category->delete();

        $this->confirmingDeleteId   = null;
        $this->confirmingDeleteName = '';

        $this->toastSuccess('La categoria fue eliminada correctamente.', 'Eliminado');
        $this->dispatch('close-modal', id: 'category-delete-modal');
    }

    /**
     * Alterna el estado activo/inactivo de una categoria.
     */
    public function toggleActive(int $id): void
    {
        $category = DocumentCategory::findOrFail($id);
        $category->update(['is_active' => ! $category->is_active]);

        $label = $category->is_active ? 'activada' : 'desactivada';
        $this->toastInfo("La categoria fue {$label}.", 'Estado actualizado');
    }

    /**
     * Cancela el formulario y cierra el modal.
     */
    #[On('close-modal')]
    public function onCloseModal(): void
    {
        $this->resetForm();
    }

    /**
     * Retorna el listado paginado aplicando el filtro de busqueda.
     */
    #[Computed]
    public function categories(): LengthAwarePaginator
    {
        return DocumentCategory::query()
            ->when(
                $this->search !== '',
                fn($q) => $q->where('name', 'like', "%{$this->search}%")
            )
            ->orderBy('name')
            ->paginate(10);
    }

    public function render(): View
    {
        return view('livewire.documents.document-categories');
    }

    // -------------------------------------------------------------------------
    // Helpers privados
    // -------------------------------------------------------------------------

    /**
     * Limpia el formulario y el estado de edicion.
     */
    private function resetForm(): void
    {
        $this->editingId = null;
        $this->name      = '';
        $this->is_active = true;
        $this->resetValidation();
    }
}
