<div>
    <form wire:submit='save'>
        <div class="card">
            <div class="card-body">
                <x-adminlte-input name="name" label="Nombre *" placeholder="ejemplo: suplemento" type="text"
                    wire:model="name" maxlength="64" required autofocus />
            </div>
        </div>

        <div class="mb-3">
            <x-livewire.loading-button label="Guardar cambios" type="submit" class="mr-1" />

            <a href="{{ route('document-categories.show', $categoryId) }}" class="btn btn-outline-secondary"
                wire:loading.attr="disabled">
                Cancelar
            </a>
        </div>
    </form>
</div>
