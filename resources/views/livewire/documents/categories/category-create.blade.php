<div>
    <form wire:submit='save'>
        <div class="card">
            <div class="card-body form-row">
                <x-adminlte-input fgroup-class="col-md-12" name="name" label="Nombre *" placeholder="ejemplo: suplemento"
                    type="text" wire:model="name" maxlength="64" required autofocus />

                <div class="col-12">
                    <hr>
                </div>

                <div class="col-12">
                    <x-checkbox name="createAnother" label="Guardar y crear otra"
                        title="Permite ingresar otra categoria tras guardar" wire:model='createAnother' />
                </div>
            </div>
        </div>

        <div class="mb-3">
            <x-livewire.loading-button type="submit" label="Guardar" class="mr-1" />

            <a href="{{ route('document-categories.index') }}" class="btn btn-outline-secondary" wire:loading.attr="disabled">
                Cancelar
            </a>
        </div>
    </form>
</div>
