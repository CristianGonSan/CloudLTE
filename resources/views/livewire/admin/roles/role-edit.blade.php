<div>
    <form wire:submit="save">
        <div class="card">
            <div class="card-body">
                <x-adminlte-input name="name" label="Nombre *" placeholder="Nombre del rol" type="text" maxlength="64"
                    wire:model="name" required autofocus />

                <x-form.select-wire-ignore name="permissions" label="Permisos" wire:loading.attr='disabled'
                    wire:target='save' multiple>
                    <x-adminlte-options :options="$permissions" :selected="$selectedPermissions" />
                </x-form.select-wire-ignore>
            </div>

        </div>
        <div class="mb-3">
            <x-livewire.loading-button type='submit' label=" Guardar cambios" />

            <a href="{{ route('admin.roles.show', $roleId) }}" class="btn btn-outline-secondary ml-1">
                Cancelar
            </a>
        </div>
    </form>
</div>


@push('js')
    <script>
        document.addEventListener("livewire:initialized", () => {
            let $wire = Livewire.first();

            const permisions = $('#permissions').select2({
                placeholder: 'Seleccione los permisos...',
                theme: 'bootstrap4',
                allowClear: true,
                width: '100%',
                language: 'es',
                dropdownAutoWidth: true,
            });

            permisions.on('change', function(e) {
                const value = $(this).val();
                $wire.set('selectedPermissions', value);
            });
        });
    </script>
@endpush
