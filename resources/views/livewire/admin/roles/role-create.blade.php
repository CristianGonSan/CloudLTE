<div>
    <form wire:submit="save">
        <div class="card">
            <div class="card-body form-row">
                <x-adminlte-input fgroup-class="col-md-12" name="name" label="Nombre *" placeholder="Nombre del rol"
                    type="text" maxlength="64" wire:model="name" required autofocus />

                <x-form.select-wire-ignore fgroup-class="col-md-12" name="permissions" label="Permisos"
                    wire:loading.attr='disabled' wire:target='save' multiple>
                    <x-adminlte-options :options="$permissions" :selected="$selectedPermissions" />
                </x-form.select-wire-ignore>

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
            <x-livewire.loading-button type='submit' label="Guardar" />

            <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary ml-1">
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
                $wire.set('selectedPermissions', value, false);
            });

            Livewire.on('reset', () => {
                permisions.val(null).trigger('change');
            });
        });
    </script>
@endpush
