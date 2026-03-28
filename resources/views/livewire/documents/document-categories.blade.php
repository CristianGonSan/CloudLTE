<div>
    {{-- Cabecera de la tarjeta --}}
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Categorias de documentos</h3>
            <div class="card-tools d-flex align-items-center">
                <div class="input-group input-group-sm mr-2" style="width: 220px;">
                    <input type="text" wire:model.live.debounce.400ms="search" class="form-control"
                        placeholder="Buscar categoria...">
                    <div class="input-group-append">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                    </div>
                </div>
                <button type="button" wire:click="openCreate" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus mr-1"></i> Nueva categoria
                </button>
            </div>
        </div>

        {{-- Tabla --}}
        <div class="card-body p-0">
            <table class="table table-striped table-hover table-sm mb-0">
                <thead class="thead-light">
                    <tr>
                        <th style="width: 60px;">#</th>
                        <th>Nombre</th>
                        <th class="text-center" style="width: 120px;">Estado</th>
                        <th class="text-center" style="width: 120px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($this->categories as $category)
                        <tr wire:key="{{ $category->id }}">
                            <td>{{ $category->id }}</td>
                            <td>{{ $category->name }}</td>
                            <td class="text-center">
                                <button type="button" wire:click="toggleActive({{ $category->id }})"
                                    wire:loading.attr="disabled" wire:target="toggleActive({{ $category->id }})"
                                    class="badge border-0 {{ $category->is_active ? 'badge-success' : 'badge-secondary' }}"
                                    title="{{ $category->is_active ? 'Desactivar' : 'Activar' }}">
                                    {{ $category->is_active ? 'Activo' : 'Inactivo' }}
                                </button>
                            </td>
                            <td class="text-center">
                                <button type="button" wire:click="openEdit({{ $category->id }})"
                                    class="btn btn-xs btn-info" title="Editar">
                                    <i class="fas fa-pencil-alt"></i>
                                </button>
                                <button type="button" wire:click="confirmDelete({{ $category->id }})"
                                    class="btn btn-xs btn-danger" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-3">
                                No se encontraron categorias.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($this->categories->hasPages())
            <div class="card-footer pb-0">
                {{ $this->categories->links() }}
            </div>
        @endif
    </div>

    {{-- Modal: crear / editar --}}
    <div wire:ignore.self class="modal fade" id="category-form-modal" tabindex="-1" role="dialog"
        aria-labelledby="categoryFormModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="categoryFormModalLabel">
                        {{ $editingId ? 'Editar categoria' : 'Nueva categoria' }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="category-name">Nombre <span class="text-danger">*</span></label>
                        <input type="text" id="category-name" wire:model="name"
                            class="form-control @error('name') is-invalid @enderror"
                            placeholder="Nombre de la categoria" autocomplete="off">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-0">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="category-is-active"
                                wire:model="is_active">
                            <label class="custom-control-label" for="category-is-active">Activo</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="button" wire:click="save" wire:loading.attr="disabled" wire:target="save"
                        class="btn btn-primary">
                        <span wire:loading wire:target="save" class="spinner-border spinner-border-sm mr-1"
                            role="status"></span>
                        {{ $editingId ? 'Guardar cambios' : 'Crear categoria' }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal: confirmacion de eliminacion --}}
    <div wire:ignore.self class="modal fade" id="category-delete-modal" tabindex="-1" role="dialog"
        aria-labelledby="categoryDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white" id="categoryDeleteModalLabel">
                        <i class="fas fa-exclamation-triangle mr-1"></i> Eliminar categoria
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <p class="mb-1">Esta a punto de eliminar:</p>
                    <strong>{{ $confirmingDeleteName }}</strong>
                    <p class="mt-2 mb-0 text-muted small">Esta accion no se puede deshacer.</p>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="button" wire:click="delete" wire:loading.attr="disabled" wire:target="delete"
                        class="btn btn-danger btn-sm">
                        <span wire:loading wire:target="delete" class="spinner-border spinner-border-sm mr-1"
                            role="status"></span>
                        Eliminar
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- Control de modales via eventos Livewire --}}
@push('js')
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('open-modal', ({
                id
            }) => {
                $(`#${id}`).modal('show');
            });

            Livewire.on('close-modal', ({
                id
            }) => {
                $(`#${id}`).modal('hide');
            });
        });
    </script>
@endpush
