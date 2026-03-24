<form wire:submit="logoutOtherDevices">
    <div class="card">
        <div class="card-body">
            <div>
                @foreach ($sessions as $session)
                    <div class="d-flex mb-3">
                        <div class="text-muted text-center" style="min-width: 40px;">
                            <i class="{{ $session['icon'] }}"></i>
                        </div>
                        <div>
                            {{ $session['user_agent'] }}
                            <div>
                                <small class="mb-0 text-muted">{{ $session['ip_address'] }}</small>
                                <br>
                                @if ($session['is_current_device'])
                                    <small class="text-success">Este dispositivo</small>
                                @else
                                    <small class="text-muted">Última actividad: {{ $session['last_activity'] }}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <hr>

            <p>Introduce tu contraseña para cerrar todas las sesiones activas en otros dispositivos.</p>

            <input type="email" name="email" class="d-none" autocomplete="username">

            <x-adminlte-input name="session_password" placeholder="Contraseña Actual" autocomplete="current-password"
                type="password" maxlength="64" wire:model="currentPassword" required />
        </div>
    </div>

    <x-livewire.loading-button type='submit' label="Cerrar sesiones" class="mb-3" icon="sign-out-alt" />
</form>
