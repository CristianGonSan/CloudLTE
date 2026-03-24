<div>
    <form wire:submit="save">
        <div class="card">
            <div class="card-body">
                <x-adminlte-input name="name" label="Nombre *" placeholder="Nombre de usuario" autocomplete="username"
                    type="text" maxlength="255" wire:model="name" required />

                <x-adminlte-input name="email" label="Correo electrónico *" placeholder="ejemplo@gmail.com"
                    autocomplete="email" type="email" maxlength="191" wire:model="email" required />

                <x-form.select-wire-ignore name="roles" label="Roles" wire:loading.attr='disabled' wire:target='save'
                    multiple>
                    <x-adminlte-options :options="$roles" :selected="$selectedRoles" />
                </x-form.select-wire-ignore>
            </div>
        </div>

        <div class="mb-3">
            <x-livewire.loading-button type='submit' label="Guardar cambios" />

            <a href="{{ route('admin.users.show', $userId) }}" class="btn btn-outline-secondary ml-1">
                Cancelar
            </a>
        </div>
    </form>

    <h2 class="h5">Cambiar contraseña</h2>
    <form wire:submit="changePassword">
        <div class="card">
            <div class="card-body">
                <x-adminlte-input name="password" label="Contraseña *" class="show-p" placeholder="Nueva Contraseña"
                    autocomplete="new-password" type="password" maxlength="64" wire:model="password" required />

                <x-adminlte-input name="password_confirmation" label="Confirmar contraseña *" class="show-p"
                    placeholder="Confirmar Nueva Contraseña" autocomplete="new-password" type="password" maxlength="64"
                    wire:model="password_confirmation" required />

                <x-checkbox name="show_passwords" label="Mostrar contraseñas" />
            </div>
        </div>
        <x-livewire.loading-button type='submit' label="Guardar contraseña" class="mb-3" />
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
        });
    </script>
@endpush
