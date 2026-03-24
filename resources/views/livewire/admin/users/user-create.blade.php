<div>
    <form wire:submit="save">
        <div class="card">
            <div class="card-body form-row">
                <x-adminlte-input fgroup-class="col-md-6" name="name" label="Nombre *"
                    placeholder="Escribe el nombre completo" type="text" maxlength="255" wire:model="name" required
                    autofocus />

                <x-adminlte-input fgroup-class="col-md-6" name="email" label="Correo electrónico *"
                    placeholder="ejemplo@gmail.com" type="email" maxlength="191" wire:model="email" required />

                <x-form.select-wire-ignore fgroup-class="col-12" name="roles" label="Roles"
                    wire:loading.attr='disabled' wire:target='save' multiple>
                    <x-adminlte-options :options="$roles" />
                </x-form.select-wire-ignore>

                <x-adminlte-input fgroup-class="col-md-6" class="show-p" name="password" label="Contraseña *"
                    placeholder="Mínimo 8 caracteres" type="password" maxlength="64" wire:model="password" required />

                <x-adminlte-input fgroup-class="col-md-6" class="show-p" name="password_confirmation"
                    label="Confirmar Contraseña *" placeholder="Repetir contraseña" type="password" maxlength="64"
                    wire:model="password_confirmation" required />

                <div class="col-12 mb-0">
                    <x-checkbox name="show_passwords" label="Mostrar contraseñas" />
                </div>

                <div class="col-12">
                    <hr>
                </div>

                <div class="col-12">
                    <x-checkbox name="createAnother" label="Guardar y crear otro"
                        title="Permite ingresar otra categoria tras guardar" wire:model='createAnother' />
                </div>
            </div>
        </div>

        <div class="mb-3">
            <x-livewire.loading-button type='submit' label="Guardar" />

            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary ml-1">
                Cancelar
            </a>
        </div>
    </form>
</div>

@push('js')
    <script>
        $(document).ready(function() {
            $('#show_passwords').on('change', function() {
                const type = this.checked ? 'text' : 'password';
                $('.show-p').attr('type', type);
            });
        });

        document.addEventListener("livewire:initialized", () => {
            let $wire = Livewire.first();

            const roles = $('#roles').select2({
                placeholder: 'Seleccione los roles...',
                theme: 'bootstrap4',
                allowClear: true,
                width: '100%',
                language: 'es',
                dropdownAutoWidth: true,
            });

            roles.on('change', function(e) {
                const value = $(this).val();
                $wire.set('selectedRoles', value, false);
            });

            Livewire.on('reset', () => {
                roles.val(null).trigger('change');
            });
        });
    </script>
@endpush
